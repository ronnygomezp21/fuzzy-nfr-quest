<?php

namespace App\Http\Controllers;

use App\GeneralResponse;
use App\Models\GameScore;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    use GeneralResponse;

    public function store(Request $request)
    {
        $userResponses = $request->all();
        $userId = Auth::id();

        $totalScore = 0;
        $maxScore = 0;
        $result = [];

        foreach ($userResponses["answers"] as $response) {

            $question = Question::find($response['id']);

            if (!$question) {
                return response()->json(['error' => 'Pregunta no encontrada'], 404);
            }

            $weights = explode('/', $question->validar);

            $weightVariable = isset($weights[0]) ? floatval($weights[0]) : 0;
            $weightValue = isset($weights[1]) ? floatval($weights[1]) : 0;
            $weightRecomend = isset($weights[2]) ? floatval($weights[2]) : 0;

            $correctVariable = $question->variable === trim($response['variable']);
            $correctValue = $question->value === trim($response['value']);
            $correctRecomend = $question->recomend === trim($response['recomend']);

            $scoreVariable = $correctVariable ? $weightVariable : 0;
            $scoreValue = $correctValue ? $weightValue : 0;
            $scoreRecomend = $correctRecomend ? $weightRecomend : 0;

            $score =  $scoreVariable +   $scoreValue + $scoreRecomend;

            $totalScore += $score;

            $maxScore +=  $weightVariable + $weightValue + $weightRecomend;

            $feedbackVariable = null;
            $feedbackValue = null;
            $feedbackRecomend = null;

            if (!$correctVariable) {
                $feedbackVariable = $question->feedback1;
            }
            if (!$correctValue) {
                $feedbackValue = $question->feedback2;
            }
            if (!$correctRecomend) {
                $feedbackRecomend = $question->feedback3;
            }

            $result[] = [
                'id' => $response['id'],
                'nfr' => $question->nfr,
                //'variable' => $question->variable,
                'user_variable' => $response['variable'],
                'feedback_variable' => $feedbackVariable,
                'correct_variable' => $correctVariable,
                //'variable_score' => $scoreVariable . '/' . $weightVariable,
                //'value' => $question->value,
                'user_value' => $response['value'],
                'feedback_value' => $feedbackValue,
                'correct_value' => $correctValue,
                //'value_score' => $scoreValue . '/' . $weightValue,
                //'recomend' => $question->recomend,
                'user_recomend' => $response['recomend'],
                'feedback_recomend' => $feedbackRecomend,
                'correct_recomend' => $correctRecomend,
                //'recomend_score' => $scoreRecomend . '/' . $weightRecomend,
                //'total_score_nfr' => round($score, 2),
            ];
        }

        $finalScore = round($maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0, 2);

       GameScore::create([
            'user_id' => $userId,
            'score' => $finalScore,
            'duration' => $userResponses["duration"],
            'game_room_id' => $userResponses["game_room_id"],
            'answered_questions' => $result, 
        ]);
        

        return $this->generalResponse(['total_score' => $finalScore, 'result' => $result], 'Cuestionario completado con éxito.');
    }

    public function getGameHistory()
    {
        $userId = Auth::id();

        $gameScores = GameScore::where('user_id', $userId)
            ->with('gameRoom')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->generalResponse($gameScores, 'Historial de juegos recuperado con éxito.');
    }
}
