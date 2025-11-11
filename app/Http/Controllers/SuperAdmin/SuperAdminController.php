<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KisUser; 

class SuperAdminController extends Controller
{
    public function index()
    {
        $totalAdmins = KisUser::where('role', 'admin')->count();
        $totalOperators = KisUser::where('role', 'operator')->count();
        $totalUsers = $totalAdmins + $totalOperators + KisUser::where('role', 'superadmin')->count();
        
        return view('superadmin.dashboard', compact('totalUsers', 'totalAdmins', 'totalOperators'));
    }

    public function manageUsers()
    {
        $users = KisUser::whereIn('role', ['superadmin', 'admin', 'operator'])->paginate(10);
        return view('superadmin.users', compact('users'));
    }
    // ... Tambahkan storeUser, updateUser, destroyUser ...
}