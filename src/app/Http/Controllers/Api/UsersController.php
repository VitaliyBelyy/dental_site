<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Index as UsersIndex;
use App\Http\Requests\Users\Store as UsersStore;
use App\Http\Requests\Users\Show as UsersShow;
use App\Http\Requests\Users\Update as UsersUpdate;
use App\Http\Requests\Users\Destroy as UsersDestroy;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index(UsersIndex $request)
    {
        $limit = $request->get('limit') ?? 10;
        $users = User::query()
            ->when($request->get('q'), function(Builder $query) use (&$request) {
                $query->where('full_name', 'LIKE', '%'. $request->get('q') .'%');
            })
            ->when($request->get('sort_by'), function(Builder $query) use (&$request) {
                $direction = $request->get('sort_desc');
                $query->orderBy($request->get('sort_by'), (isset($direction) && json_decode($direction)) ? 'DESC' : 'ASC');
            })
            ->where('id', '!=', auth()->user()->id)
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        $users->getCollection()->transform(function ($item, $key) {
            if (isset($item->hash_file_name)) {
                $item->image_path = Storage::disk('user_avatars')->url($item->hash_file_name);
            }

            return $item;
        });

        return response()->api($users);
    }

    public function store(UsersStore $request) {
        $photo = $request->file('photo');
        $originalFileName = null;
        $hashFileName = null;

        if (isset($photo)) {
            $imagePath = $photo->store('/', 'user_avatars');

            if ($imagePath) {
                $originalFileName = $photo->getClientOriginalName();
                $hashFileName = basename($imagePath);
            }
        }

        $user = User::create([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => uniqid(Str::random(60)),
            'original_file_name' => $originalFileName,
            'hash_file_name' => $hashFileName
        ]);

        $user->assignRole('user');

        event(new Registered($user));

        return response()->api($user);
    }

    public function show(UsersShow $request, User $user) {
        if (isset($user->hash_file_name)) {
            $user->image_path = Storage::disk('user_avatars')->url($user->hash_file_name);
        }

        return response()->api($user);
    }

    public function update(UsersUpdate $request, User $user) {
        $photo = $request->file('photo');
        $originalFileName = $user->original_file_name;
        $hashFileName = $user->hash_file_name;

        if (isset($photo)) {
            if (isset($hashFileName)) {
                Storage::disk('user_avatars')->delete($hashFileName);
            }

            $imagePath = $photo->store('/', 'user_avatars');

            if ($imagePath) {
                $originalFileName = $photo->getClientOriginalName();
                $hashFileName = basename($imagePath);
            }
        }

        $user->update([
            'full_name' => $request->full_name ?? $user->full_name,
            'phone' => $request->phone ?? $user->phone,
            'email' => $request->email ?? $user->email,
            'password' => $request->new_password ? Hash::make($request->new_password) : $user->password,
            'original_file_name' => $originalFileName,
            'hash_file_name' => $hashFileName
        ]);

        return response()->api($user);
    }

    public function destroy(UsersDestroy $request, User $user)
    {
        $user->delete();

        return response()->api($user);
    }
}
