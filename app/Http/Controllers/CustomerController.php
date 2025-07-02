<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    // Lihat daftar customer
    public function index()
    {
        $customers = User::where('role', 'customer')->get();
        return view('admin.customers.index', compact('customers'));
    }

    // Lihat detail/profil customer
    public function show($id)
    {
        $customer = User::findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }
}
