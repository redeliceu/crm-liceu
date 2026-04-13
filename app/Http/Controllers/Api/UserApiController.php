<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    //
    public function getAllUsers()
    {
        $data = User::all();
        return response()->json([ 'data' => $data ], 200);
    }

    public function setUserFlag(Request $request, $id, $flag_id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado!'], 404);
        }

        $user->user_flag_id = $flag_id;
        $user->save();

        return response()->json(['message' => 'Usuário atualizado com sucesso!'], 200);
    }

}
