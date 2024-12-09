<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Validation\ValidationException;
use App\Services\QuestionService;
use App\GeneralResponse;
use App\Models\GameRoom;
use App\Models\GameScore;
use App\Models\Question;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
        try {
            $request->validate([
                'archivo' => 'required|file|mimes:xls,xlsx',
                //'sala_de_juego' => 'required|string|unique:questions,sala_de_juego'
            ]);

            //$salaDeJuego = $request->input('sala_de_juego');

            $code = Str::random(6);
            //TODO COLOCAR AAUI EL ID DEL USUARIO QUIEN CREA LA SALA
            $gameRoom = GameRoom::create(['code' => $code, 'expiration_date' => now()->addDays(7)]);
            
            $this->questionService->getHeadings($request->file('archivo'), $gameRoom->id,);
            Excel::import($this->questionService, $request->file('archivo'));

            return $this->generalResponse(
                null,
                'La sala de juegos se ha creado correctamente, ' .
                'Por favor comparte este código <strong>' . $gameRoom->code . '</strong> con tus estudiantes, ' .
                'Recuerda que esta sala expira el <strong>' . $gameRoom->expiration_date . '</strong>.'
            );

        } catch (ValidationException $e) { 
            return $this->generalResponse(null, $e->errors(), 422); 
        } catch (Throwable $e) {
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
                return $this->generalResponse(null, 'Esta sala de juego ya no está disponible porque ha expirado. Por favor, verifique con el docente o inicid una nueva sala', 403);
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

    public function createRoomGameQuestions(Request $request)
    {
        $request->validate([
            '*.nfr' => 'required|string',
            '*.variable' => 'required|string',
            '*.feedback1' => 'required|string',
            '*.value' => 'required|string',
            '*.feedback2' => 'required|string',
            '*.recomend' => 'required|string',
            '*.feedback3' => 'required|string',
            '*.validar' => 'required|string',
        ]);

        $code = Str::random(6);

        $gameRoom = GameRoom::create(['code' => $code,  'expiration_date' => now()->addDays(7),]);

        foreach ($request->all() as $questionData) {
            Question::create([
                'game_room_id' => $gameRoom->id,
                'nfr' => $questionData['nfr'],
                'variable' => $questionData['variable'],
                'feedback1' => $questionData['feedback1'],
                'value' => $questionData['value'],
                'feedback2' => $questionData['feedback2'],
                'recomend' => $questionData['recomend'],
                'feedback3' => $questionData['feedback3'],
                'validar' => $questionData['validar'],
            ]);
        }

        return $this->generalResponse(null, 'Sala de juego creada exitosamente, comparte el codigo ' . $gameRoom->code . ' con tus estudiantes', 201);

    }


    public function getInfoAllByCode(Request $request)
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
                return $this->generalResponse(null, 'Esta sala de juego ya no está disponible porque ha expirado. Por favor, verifique con el docente o inicid una nueva sala', 403);
            }

            $questions = $gameRoom->questions()
                ->select('id', 'nfr', 'variable', 'feedback1', 'value', 'feedback2', 'recomend', 'feedback3', 'validar')
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

    // public function getExcelHeadings(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'archivo' => 'required|file|mimes:xls,xlsx'
    //         ]);

    //         $headings = $this->questionService->getHeadings($request->file('archivo'));

    //         return response()->json(['encabezados' => $headings], 200);

    //     } catch (Throwable $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }


}
