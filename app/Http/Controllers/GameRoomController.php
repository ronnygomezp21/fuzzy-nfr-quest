<?php

namespace App\Http\Controllers;

use App\GeneralResponse;
use App\Models\GameRoom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GameRoomController extends Controller
{
    use GeneralResponse;

    public function getGameRooms()
    {   
        $userId = Auth::id();
        $gameRooms = GameRoom::where('user_id_created', $userId)->get();
        return $this->generalResponse($gameRooms, 'Lista de salas de juego');
    }

    public function deleteGameRoom(Request $request)
    {
        $gameRoom = GameRoom::find($request->game_room_id);

        if (!$gameRoom) {
            return $this->generalResponse(null, 'Sala de juego no encontrada',404);
        }

        $gameRoom->status = $request->status;
        $gameRoom->save();

        return $this->generalResponse(null, 'Estado de la sala de juego actualizado');
    }
}
