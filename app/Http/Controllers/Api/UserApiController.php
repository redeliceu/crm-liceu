<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserApiController extends Controller
{
    private const ADMIN_FLAG = 2;

    public function getAllUsers()
    {
        // Só os campos que a tela de usuários usa
        $data = User::select('id', 'name', 'email', 'user_flag_id', 'created_at')->get();
        return response()->json([ 'data' => $data ], 200);
    }

    public function setUserFlag(Request $request, $id, $flag_id)
    {
        // Somente administradores podem alterar o perfil de outro usuário
        if ((int) $request->user()->user_flag_id !== self::ADMIN_FLAG) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        if (! DB::table('user_flags')->where('id', $flag_id)->exists()) {
            return response()->json(['message' => 'Perfil inválido.'], 422);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado!'], 404);
        }

        $user->user_flag_id = $flag_id;
        $user->save();

        Log::info('Perfil de usuário alterado', [
            'by_user_id' => $request->user()->id,
            'target_user_id' => $user->id,
            'flag_id' => (int) $flag_id,
            'ip' => $request->ip(),
        ]);

        return response()->json(['message' => 'Usuário atualizado com sucesso!'], 200);
    }

}
