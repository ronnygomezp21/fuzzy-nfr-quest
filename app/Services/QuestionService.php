<?php

namespace App\Services;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;

class QuestionService implements ToModel, WithHeadingRow
{
    protected $headings;

    public function __construct()
    {
        $this->headings = [];
    }

    public function getHeadings($filePath)
    {
        $headings = Excel::toArray([], $filePath);
        $this->headings = $headings[0][0]; // Guardar los encabezados en la propiedad
    }

    public function model(array $row)
    {
        // dd($row);

        return new Question([
            'nfr' => trim($row['nfr']),
            'variable' => trim($row['variable']),
            'feedback1' => trim($row['feedback1']),
            'value' => trim($row['value']),
            'feedback2' => trim($row['feedback2']), 
            'recomend' => trim($row['recomendedvalues']),
            'feedback3' => trim($row['feedback3']), 
            'validar' => trim($row['validar']),
        ]);
    }

    public function headingRow(): int
    {
        return 1; 
    }

    // public function getHeadings($filePath)
    // {
    //     $headings = Excel::toArray([], $filePath);
    //     return $headings[0][0]; // Devuelve la primera fila, que son los encabezados
    // }
}