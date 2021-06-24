<?php

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Http\Request;

class TokenController extends Controller
{
    public function generateToken(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $usuario = User::where('email', $request->email)->first();

        if (is_null($usuario)
            || !Hash::check($request->password, $usuario->password)
        ) {
            return response()->json('Invalid user', 401);
        }

        $token = JWT::encode(
            ['email' => $request->email],
            env('JWT_KEY')
        );

        return [
            'access_token' => $token
        ];
    }
}
