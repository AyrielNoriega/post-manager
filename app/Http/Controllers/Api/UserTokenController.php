<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UserTokenController extends Controller
{

    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password'  => 'required',
            'device_name'   => 'required',
        ]);

        $user = User::where('email', $request->get('email'))->first();


        if (!($user instanceof User)
            || !Hash::check($request->password, $user->password)
        ) {
            // throw ValidationException::withMessages([
            //     'email' => 'El email no existe o no coincide con nuestros registros',
            // ]);
            return response()->json([
                'message' => 'The credentials do not match our records',
            ], Response::HTTP_UNAUTHORIZED);

        }

        return response()->json([
            'data' => [
                'attributes' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ],
            'token' => $user->createToken($request->device_name)->plainTextToken,
        ], Response::HTTP_OK);
    }
}
