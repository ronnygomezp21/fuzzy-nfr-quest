<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Validation\ValidationException;
use App\Services\QuestionService;
use App\GeneralResponse;
use App\Models\GameRoom;
use App\Models\Question;
use Illuminate\Support\Str;

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

            $gameRoom = GameRoom::create(['code' => $code]);
            
            $this->questionService->getHeadings($request->file('archivo'), $gameRoom->id,);
            Excel::import($this->questionService, $request->file('archivo'));
            return $this->generalResponse(null, 'Archivo importado correctamente', 200);  

        } catch (ValidationException $e) { 
            return $this->generalResponse(null, $e->errors(), 422); 
        } catch (Throwable $e) {
            return $this->generalResponse(null, $e->getMessage(), 500); 
        }
    }

    public function questionsByCode(Request $request)
    {
      // Buscar la sala de juego por el código
      $code = $request->input('code');
        $gameRoom = GameRoom::where('code', $code)->first();

        // Verificar si la sala de juego existe
        if (!$gameRoom) {
            return response()->json(['message' => 'Sala de juego no encontrada'], 404);
        }

        // Obtener todas las preguntas relacionadas con la sala de juego
        $questions = $gameRoom->questions;

        // Devolver las preguntas en formato JSON
        return response()->json([
            'message' => 'Preguntas encontradas',
            'questions' => $questions
        ], 200);
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

        $gameRoom = GameRoom::create(['code' => $code]);

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
