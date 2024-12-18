<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte Académico</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0px;
        }
        .header h1 {
            margin: 0;
        }
        .header-logo {
            text-align: right;
        }
        .header-logo img {
            max-width: 150px;
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 5.5px; text-align: center; font-size: 14px; }
        th { background-color: #f4f4f4; font-weight: bold; }
        h2, h3 { text-align: center; }
        .footer {
            position: fixed;
            bottom: -0.75cm;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 10px 0px;
            background-color: #f4f4f4;
            font-size: 12px;
        }
        .signature-container {
            text-align: center;
            margin-top: 50px;
}
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #000;
            width: 300px;
            margin-left: auto;
            margin-right: auto;
        }
        .signature-label {
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
        }
        /* @page {
    margin-bottom: 35px; 
} */
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logo">
            <img src="{{ public_path('images/logo.png') }}" alt="Fuzzy NFR Quest">
        </div>
        <h1>Reporte Académico</h1>
    </div>
    <div class="footer">
        <p>Reporte generado por el sistema Fuzzy NFR Quest. {{ date('Y') }}</p>
    </div>

    <p><strong>Fecha de generación: </strong>{{ $generated_at }}</p>
    <p><strong>Docente: </strong>{{$fullNameTeacher}}</p>
    <p><strong>Sala: </strong>{{ $code }}</p>

    <h2>Resumen General</h2>
    <table>
        <tr>
            <th>Total de RNF</th>
            <th>Promedio</th>
            <th>Puntaje Más Alto</th>
            <th>Estudiante con el Puntaje Más Alto</th>
        </tr>
        <tr>
            <td>{{ $total_questions }}</td>
            <td>{{ $average_score }}</td>
            <td>{{ $highest_score }}</td>
            <td>(ID: {{ $highest_scorer_id }}) {{ $highest_scorer_name }}</td>
        </tr>
    </table>

    <h2>Detalle por Estudiante</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>Aciertos</th>
                <th>Desaciertos</th>
                <th>Total</th>
                <th>Puntaje</th>
                <th>Duración</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $detail)
                <tr>
                    <td>{{ $detail['user_id'] }}</td>
                    <td>{{ $detail['last_name'] }}</td>
                    <td>{{ $detail['name'] }}</td>
                    <td>{{ $detail['correct'] }}</td>
                    <td>{{ $detail['incorrect'] }}</td>
                    <td>{{ $detail['total_questions'] }}</td>
                    <td>{{ $detail['score'] }}</td>
                    <td>{{ $detail['duration'] }}</td>
                    <td>{{ $detail['created_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <div class="signature-container">
        <div class="signature-line"></div>
        <div class="signature-label">{{$fullNameTeacher}}</div>
    </div> --}}
</body>
</html>
