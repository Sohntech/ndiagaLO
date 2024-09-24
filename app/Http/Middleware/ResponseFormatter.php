<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\StateEnum;
use App\Exceptions\UserException;
use Illuminate\Validation\ValidationException;

class ResponseFormatter
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $response = $next($request);

            $content = $response->getContent();
            $decodedContent = json_decode($content, true);

            if ($response->exception instanceof UserException) {
                $exception = $response->exception;
                $formattedResponse = [
                    'data' => $exception->getData(),
                    'status' => $exception->getCode() < 400 ? StateEnum::SUCCESS->value : StateEnum::ECHEC->value,
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage()
                ];
            } else {
                $formattedResponse = [
                    'data' => $decodedContent ?? null,
                    'status' => $response->isSuccessful() ? StateEnum::SUCCESS->value : StateEnum::ECHEC->value,
                    'code' => $response->getStatusCode(),
                    'message' => $response->isSuccessful() ? 'Opération réussie' : 'Une erreur est survenue'
                ];
            }

            return response()->json($formattedResponse, $formattedResponse['code']);
        } catch (ValidationException $e) {
            $formattedResponse = [
                'data' => $e->errors(),
                'status' => StateEnum::ECHEC->value,
                'code' => 422,
                'message' => "Erreur de validation"
            ];
            return response()->json($formattedResponse, 422);
        }
    }
}