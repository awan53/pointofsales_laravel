<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index(){
        $users = User::all();
        
        return view('users.index', compact('users')); 

        
    }

    public function create(){
        return view('users.create');

    }

    public function store(Request $request){
        $request -> validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6|confirmed',
            'role'      => 'required|in:admin,kasir,supply_chain'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>bcrypt($request->password) ,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'user berhasil dibuat');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'. $user->id,
            'role'      => 'required',
            'password'  => 'nullable|min:6'
        ]);

        $data = 
        [
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        ];

        if($request->filled('password')){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'userberhasil di update');

    }

    public function destroy(User $user){
        if(auth()->id() == $user->id){
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri');

        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil di hapus');
    }
}
