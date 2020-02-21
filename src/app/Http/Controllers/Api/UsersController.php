<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Index as UsersIndex;
use App\Http\Requests\Users\Destroy as UsersDestroy;
use App\Http\Requests\Users\Restore as UsersRestore;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UsersController extends Controller
{
    public function index(UsersIndex $request)
    {
        $limit = $request->get('limit') ?? 10;
        $users = User::withTrashed()
            ->when($request->get('q'), function(Builder $query) use (&$request) {
                $query->where('id', 'LIKE', '%'. $request->get('q') .'%')
                    ->orWhere('name', 'LIKE', '%'. $request->get('q') .'%')
                    ->orWhere('email', 'LIKE', '%'. $request->get('q') .'%');
            })
            ->when($request->get('sort_by'), function(Builder $query) use (&$request) {
                $direction = $request->get('sort_desc');
                $query->orderBy($request->get('sort_by'), (isset($direction) && json_decode($direction)) ? 'DESC' : 'ASC');
            })
            ->where('id', '!=', auth()->user()->id)
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        return response()->api($users);
    }

    public function destroy(UsersDestroy $request, $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->api($user);
    }

    public function restore(UsersRestore $request, $id)
    {
        $user = User::withTrashed()
            ->findOrFail($id);

        $user->restore();

        return response()->api($user);
    }
}
