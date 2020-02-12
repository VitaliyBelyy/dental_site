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
        $sortBy = $request->get('sort_by') ?? [];
        $sortDesc = $request->get('sort_desc') ?? [];
        $users = User::withTrashed()
            ->when($request->get('q'), function(Builder $query) use (&$request) {
                $query->where('id', 'LIKE', '%'. $request->get('q') .'%')
                    ->orWhere('fullname', 'LIKE', '%'. $request->get('q') .'%')
                    ->orWhere('email', 'LIKE', '%'. $request->get('q') .'%');
            })
            ->where('id', '!=', auth()->user()->id);

        foreach ($sortBy as $index => $column) {
            $direction = isset($sortDesc[$index]) ? json_decode($sortDesc[$index]) : false;
            $users->orderBy($column, $direction ? 'DESC' : 'ASC');
        }

        return response()->api($users->paginate($limit));
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
