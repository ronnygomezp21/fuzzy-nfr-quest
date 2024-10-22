<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Throwable;
use App\Services\QuestionService;

class QuestionsController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'archivo' => 'required|file|mimes:xls,xlsx'
            ]);

            $this->questionService->getHeadings($request->file('archivo'));
            Excel::import($this->questionService, $request->file('archivo'));
            return response()->json(['mensaje' => 'Archivo importado correctamente'], 200);

        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getExcelHeadings(Request $request)
    {
        try {
            $request->validate([
                'archivo' => 'required|file|mimes:xls,xlsx'
            ]);

            $headings = $this->questionService->getHeadings($request->file('archivo'));

            return response()->json(['encabezados' => $headings], 200);

        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
