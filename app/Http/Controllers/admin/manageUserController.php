<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Pastikan model User di-import
use App\Models\Role; // Jika Anda punya model Role dan user->role_id terhubung ke sana
use App\Models\StudentRegistration;

class manageUserController extends Controller
{
    public function index()
    {
        $users = User::whereHas('role', function ($query) {
            $query->where('name', 'user');
        })
            ->with('role', 'studentRegistration')
            ->latest()
            ->get();

        return view('admin.manage-user.index', compact('users'));
    }
}
