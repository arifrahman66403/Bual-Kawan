<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 

class SuperAdminController extends Controller
{
    public function index()
    {
        $totalAdmins = User::where('role', 'admin')->count();
        $totalOperators = User::where('role', 'operator')->count();
        $totalUsers = $totalAdmins + $totalOperators + User::where('role', 'superadmin')->count();
        
        return view('superadmin.dashboard', compact('totalUsers', 'totalAdmins', 'totalOperators'));
    }

    public function manageUsers()
    {
        $users = User::whereIn('role', ['superadmin', 'admin', 'operator'])->paginate(10);
        return view('superadmin.users', compact('users'));
    }
    // ... Tambahkan storeUser, updateUser, destroyUser ...
}