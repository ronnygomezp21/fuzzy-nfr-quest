<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Validation\ValidationException;
use App\Services\QuestionService;
use App\GeneralResponse;
use App\Http\Requests\CreateGameRoomRNFRequest;
use App\Models\GameRoom;
use App\Models\GameScore;
use App\Models\Question;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionsController extends Controller
{
    protected $questionService;

    use GeneralResponse;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function import(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xls,xlsx',
        ]);
        DB::beginTransaction();

        try {
            

            $userId = Auth::id();

            $code = Str::random(6);

            $gameRoom = GameRoom::create(['code' => $code, 'user_id_created' => $userId, 'expiration_date' => now()->addDays(7)]);

            $this->questionService->getHeadings($request->file('archivo'), $gameRoom->id);
            Excel::import($this->questionService, $request->file('archivo'));

            DB::commit();

            return $this->generalResponse(
                null,
                'La sala de juegos se ha creado correctamente, ' .
                    'Por favor comparte este código <strong>' . $gameRoom->code . '</strong> con tus estudiantes, ' .
                    'Recuerda que esta sala expira el <strong>' . $gameRoom->expiration_date . '</strong>.'
            );
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->generalResponse(null, $e->errors(), 422);
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->generalResponse(null, $e->getMessage(), 500);
        }
    }

    public function questionsByCode(Request $request)
    {
        try {

            $code = $request->input('code');
            $userId = Auth::id();
            $gameRoom = GameRoom::where('code', $code)->first();

            if (!$gameRoom) {
                return $this->generalResponse(null, 'La sala de juego que estás buscando no existe. Verifica el código e inténtalo nuevamente.', 404);
            }

            $gameScore = GameScore::where('game_room_id', $gameRoom->id)
                ->where('user_id', $userId)
                ->first();

            if ($gameScore) {
                return $this->generalResponse(null, 'Ya completaste el juego en esta sala. Por favor, únete a otra sala.', 403);
            }

            if (!$gameRoom->status) {
                return $this->generalResponse(null, 'Esta sala de juego ya no está disponible. Por favor, verifique con su docente.', 403);
            }

            if ($gameRoom->expiration_date < now()) {
                return $this->generalResponse(null, 'Esta sala de juego ya no está disponible porque ha expirado. Por favor, verifique con el docente o inicie una nueva sala', 403);
            }

            $questions = $gameRoom->questions()
                ->select('id', 'nfr', 'other_recommended_values')
                ->get();

            return response()->json([
                'message' => 'Requerimientos no funcionales encontrados.',
                'game_room_id' => $gameRoom->id,
                'questions' => $questions
            ], 200);
        } catch (Throwable $e) {
            return $this->generalResponse(null, $e->getMessage(), 500);
        }
    }

    public function createRoomGameQuestions(CreateGameRoomRNFRequest $request)
    {
        try {

            $data = $request->validated();
            $questions = $data['questions'];

            $userId = Auth::id();
            $code = Str::random(6);

            $gameRoom = GameRoom::create([
                'code' => $code,
                'user_id_created' => $userId,
                'expiration_date' => $data["expiration_date"] //now()->addDays(7)
            ]);


            foreach ($questions as $questionData) {
                Question::create([
                    'game_room_id' => $gameRoom->id,
                    'nfr' => str_replace('.', '', trim($questionData['nfr'])),
                    'variable' => trim($questionData['variable']),
                    'feedback1' => trim($questionData['feedback1']),
                    'value' => trim($questionData['value']),
                    'feedback2' => trim($questionData['feedback2']),
                    'recomend' => trim($questionData['recomend']),
                    'other_recommended_values' => trim($questionData["other_recommended_values"]),
                    'feedback3' => trim($questionData['feedback3']),
                    'validar' => trim($questionData['validar']),
                ]);
            }


            return $this->generalResponse(
                null,
                'La sala de juegos se ha creado correctamente, ' .
                    'Por favor comparte este código <strong>' . $gameRoom->code . '</strong> con tus estudiantes, ' .
                    'Recuerda que esta sala expira el <strong>' . $gameRoom->expiration_date . '</strong>.'
            );
        } catch (\Throwable $th) {
            return $this->generalResponseWithErrors($th);
        }
    }
}
