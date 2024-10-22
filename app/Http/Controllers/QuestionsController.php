<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Validation\ValidationException;
use App\Services\QuestionService;
use App\GeneralResponse;

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
                'sala_de_juego' => 'required|string|unique:questions,sala_de_juego'
            ]);

            $salaDeJuego = $request->input('sala_de_juego');
            
            $this->questionService->getHeadings($request->file('archivo'), $salaDeJuego);
            Excel::import($this->questionService, $request->file('archivo'));
            return $this->generalResponse(null, 'Archivo importado correctamente', 200);  

        } catch (ValidationException $e) { 
            return $this->generalResponse(null, $e->errors(), 422); 
        } catch (Throwable $e) {
            return $this->generalResponse(null, $e->getMessage(), 500); 
        }
    }

    public function questionsBySala(Request $request)
    {
        try {
            $request->validate([
                'sala_de_juego' => 'required|string',
            ]);

            $salaDeJuego = $request->input('sala_de_juego');
            $questions = $this->questionService->getQuestionsBySalaDeJuego($salaDeJuego); 
            return $this->generalResponse($questions, 'Preguntas obtenidas correctamente', 200);
        } catch (ValidationException $e) {
            return $this->generalResponse(null, $e->errors(), 422);
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
