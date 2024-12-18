<?php

namespace App\Services;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;

class QuestionService implements ToModel, WithHeadingRow
{
    protected $headings;
    private $gameRoomId; 


    public function __construct()
    {
        $this->headings = [];
    }

    public function getHeadings($filePath, $gameRoomId)
    {
        $this->gameRoomId = $gameRoomId;
        $headings = Excel::toArray([], $filePath);
        $this->headings = $headings[0][0];
    }

    public function model(array $row)
    {
        return new Question([
            'nfr' => str_replace('.', '', trim($row['rnf'])),
            'variable' => str_replace('.', '', trim($row['linguistic_variable'])),
            'feedback1' => trim($row['feedback_linguistic_variable']),
            'value' => str_replace('.', '', trim($row['linguistic_value'])),
            'feedback2' => trim($row['feedback_linguistic_value']), 
            'recomend' => str_replace('.', '', trim($row['recommended_linguistic_value'])),
            'feedback3' => trim($row['feedback_recommended_linguistic_value']), 
            'other_recommended_values' => trim($row['other_linguistic_values']),
            'validar' => trim($row['weights']),
            'game_room_id' => $this->gameRoomId
        ]);
    }

    public function headingRow(): int
    {
        return 1; 
    }

    public function getQuestionsByCode(string $code)
    {
        return Question::where('code', trim($code))->get();
    }
}