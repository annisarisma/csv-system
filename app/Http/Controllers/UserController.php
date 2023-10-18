<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register_index()
    {
        return view('authentication.register', [
            'title' => 'Register'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function login_index()
    {
        return view('authentication.login', [
            'title' => 'Login'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register_store(Request $request)
    {
        // Validation data
        $request->validate(
            [
                'username' => 'required|unique:users',
                'email' => 'required|unique:users',
                'password' => 'required',
                'confirm_password' => 'required',
            ],
            [
                'username' => 'Username should be filled',
                'email.required' => 'Email should be filled',
                'password.required' => 'Password should be filled',
                'confirm_password.required' => 'Confirmation password should be filled',
            ]
        );

        // Checking password and confirmation password
        if ($request['password'] != $request['confirm_password']) {
            return back()->withErrors([
                'confirm_password' => ["Confirmation password doesn't match"]
            ])->withInput();
        }
        
        // Store user data to database
        $user = new User([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        try {
            $user->save();
            return redirect('/login')->with('success-alert', [
                'message' => $request->username . ' successfulliy created'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/register');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login_store(Request $request)
    {
        $remember = $request->has('remember') ? true : false;
        $request->validate(
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => 'Email harus diisi',
                'password.required' => 'Password harus diisi',
            ]
        );

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success-alert', [
                'message' => $request->username . ' successfully login'
            ]);
        }

        // if not succeed
        return back()->withErrors([
            'password' => ["Username or password doesn't match"]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
