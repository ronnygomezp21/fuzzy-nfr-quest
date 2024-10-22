<?php

namespace App;

trait GeneralResponse
{
    /**
     * Devuelve una respuesta JSON estándar.
     *
     * @param mixed  $data      Los datos a devolver en la respuesta.
     * @param string $message   El mensaje de la respuesta.
     * @param int    $status    El código de estado HTTP.
     * @return \Illuminate\Http\JsonResponse
     */
    public function generalResponse($data = null, $message = '', $status = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message
        ], $status);
    }

    public function generalResponseWithErrors($message = '', $status = 500)
    {
        return response()->json([
            'data' => null,
            'message' => $message,
        ], $status);
    }
}
