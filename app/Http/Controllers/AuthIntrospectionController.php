<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;

class AuthIntrospectionController extends Controller
{
    public function introspect(Request $request)
    {
         $tokenString = $request->input('token');

        if (!$tokenString) {
            return response()->json(['active' => false], 400);
        }

        // El token de Sanctum es "id|plainText"
        $parts = explode('|', $tokenString, 2);
        if (count($parts) !== 2) {
            return response()->json(['active' => false], 400);
        }

        [$id, $plainText] = $parts;

        $token = PersonalAccessToken::find($id);

        if (!$token) {
            return response()->json(['active' => false], 200);
        }

        // Verificar hash
        if (! hash_equals($token->token, hash('sha256', $plainText))) {
            return response()->json(['active' => false], 200);
        }

        // Verificar expiración si la usas
        if ($token->expires_at && $token->expires_at->isPast()) {
            return response()->json(['active' => false], 200);
        }

        $user = $token->tokenable; // el usuario dueño del token

        return response()->json([
            'active' => true,
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
            'abilities' => $token->abilities,
        ]);

    }
}