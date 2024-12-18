<?php

namespace App\Http\Controllers;

use App\Models\GameRoom;
use App\Models\GameScore;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\GeneralResponse;
use Illuminate\Support\Facades\DB;

class PDFReportController extends Controller
{
    use GeneralResponse;

    public function generateReportTeacherGameRoom(Request $request)
    {
        DB::beginTransaction();

        try {
            $game_room_id = $request->input('game_room_id');

            $fullNameTeacher = Auth::user()->name . ' ' . Auth::user()->last_name;
            $scores = GameScore::where('game_room_id', $game_room_id)->with('users')->get();

            if(count($scores) == 0){
                return $this->generalResponse(null, 'El informe no está disponible porque todavía no hay datos de juego de los estudiantes.', 404); 
            }

            $gameRoom = GameRoom::find($game_room_id);

            $questions = $gameRoom->questions;

            $totalQuestions = count($questions);

            $averageScore = $scores->avg('score');
            $highestScorer = $scores->sortByDesc('score')->first();

            $highestScore = $highestScorer->score ?? 0;

            $highestScorers = $scores->filter(function ($score) use ($highestScore) {
                return $score->score === $highestScore;
            });

            if ($highestScorers->count() > 1) {
                $highestScorer = $highestScorers->sortBy('duration')->first();
            } else {
                $highestScorer = $highestScorers->first();
            }

            $highestScore = $highestScorer->score ?? 0;

            $reportDetails = $scores->map(function ($score) {
                $answeredQuestions = json_encode($score->answered_questions, true);
                $data = json_decode($answeredQuestions, true);

                $correct = collect($data)->reduce(function ($carry, $question) {
                    return $carry +
                        ($question['correct_variable'] === true ? 1 : 0) +
                        ($question['correct_value'] === true ? 1 : 0) +
                        ($question['correct_recomend'] === true ? 1 : 0);
                }, 0);

                $incorrect = collect($data)->reduce(function ($carry, $question) {
                    return $carry +
                        ($question['correct_variable'] === false ? 1 : 0) +
                        ($question['correct_value'] === false ? 1 : 0) +
                        ($question['correct_recomend'] === false ? 1 : 0);
                }, 0);

                $totalQuestions = $incorrect + $correct;

                return [
                    'user_id' => $score->users->id,
                    'last_name' => $score->users->last_name,
                    'name' => $score->users->name,
                    'total_questions' => $totalQuestions,
                    'correct' => $correct,
                    'incorrect' => $incorrect,
                    'score' => $score->score,
                    'duration' => $score->duration,
                    'created_at' => $score->created_at,
                    //'effectiveness' => round(($correct / $totalQuestions) * 100, 2) . '%',
                ];
            });

            $data = [
                'code' => $gameRoom->code,
                'fullNameTeacher' => $fullNameTeacher,
                'total_questions' => $totalQuestions,
                'average_score' => round($averageScore, 2),
                'highest_score' => $highestScore,
                'highest_scorer_name' => $highestScorer->users->last_name . ' ' . $highestScorer->users->name ?? 'N/A',
                'highest_scorer_id' => $highestScorer->users->id ?? 'N/A',
                'details' => $reportDetails,
                'generated_at' => now()->format('d/m/Y H:i:s'),
            ];


            $pdf = PDF::loadView('reports/game_room_report', $data);
            $fileName = 'REPORTE-SALA-' . $gameRoom->code . '-' . date('Y-m-d_H-i-s') . '.pdf';

            //return $pdf->download($fileName);
            DB::commit();
            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->generalResponse(null, $th->getMessage(), 500); 
        }
    }
}
