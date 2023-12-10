<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function tokenLog(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken('Personal Access Token')->accessToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }



    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string',],
            'role' => 'user',
        ]);
        // if (!$request) {
        //     return response()->json([
        //         'warning' => [
        //             'code' => 201,
        //             'message' => "Регистрация не удалась",
        //         ]
        //     ])->setStatusCode(201);
        // } else {
        //     return response()->json([
        //         'content' => [
        //             'code' => 200,
        //             'message' => "Регистрация прошла успешно",
        //         ]
        //     ])->setStatusCode(200);
        // }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
        ]);



        Auth::login($user);
        $token = $user->createToken('main')->plainTextToken;
        return response()->json([
            'user_token' => $token
        ], 201);

        // if ($user->role == 'admin') {

        //     return redirect()->intended('/profile/admin');
        // } else {
        //     return redirect()->intended('/profile');
        // }
    }

    // public static $getToken = [AuthController::class, "register($request)"];

    public function login(Request $request)
    {
        $user = $request->validate([
            "email" => ['required'],
            "password" => ['required']
        ]);
        // if (Auth::attempt($user)) {
        //     $request->session()->regenerate();

        //     $user = Auth::user();
        //     if ($user->role === 'admin') {
        //         return redirect()->intended('/admin_profile');
        //     } else {
        //         return redirect()->intended('/');
        //     }
        // }
        $user = $request->only("email", "password");
        if (!Auth::attempt($user)) {
            return response()->json([
                'warning' => [
                    'code' => '401',
                    'message' => 'Неудачный вход',
                ]
            ])->setStatusCode(401);
        } else {

            $user = Auth::user();
            $token = $user->createToken('main')->plainTextToken;
            return response()->json($token);
        }
    }

    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
