<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GetCategoryList;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

class UserController extends Controller
{
    use GetCategoryList;

    public function index()
    {
        $cats = $this->getList();
        $user = auth()->user();
        $users = User::paginate();

        return view('admin.user-list', compact(
            'cats',
            'user',
            'users'
        ));
    }

    public function updateRole(Request $request, string $enc_id)
    {
        $res = (object) request()->validate([
            'role' => 'required|string|in:admin,super,delivery,normal'
        ]);

        $user = User::findOrFail(Hashids::decode($enc_id)[0]);

        if ($res->role === 'admin') {
            abort_unless(auth()->user()->isAdmin(), 403);
            $user->role = User::AdminRole;
        } elseif ($res->role === 'super') {
            abort_unless(auth()->user()->isAdmin(), 403);
            $user->role = User::SuperRole;
        } elseif ($res->role === 'delivery') {
            $user->role = User::DeliveryRole;
        } else {
            $user->role = 0;
        }

        $updated = $user->update();

        return response()->json([
            'updated' => $updated
        ]);
    }
}
