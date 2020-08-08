<?php

namespace Bendt\Auth\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ApiController extends Controller
{
    /**
     * success response method.
     *
     * @param  array $data
     * @param  string $message
     * @param  integer $code
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($data = [], $message = null, $code = 200)
    {
        $response = [
            'success' => true,
        ];

        if($data) $response['data'] = $data;
        if(!is_null($message)) $response['message'] = $message;

        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @param  array $errors
     * @param  integer $code
     * @return \Illuminate\Http\Response
     */
    public function sendError($errors = [], $code = 400)
    {
        $response = [
            'success' => false,
            'error' => $errors,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    /**
     * return custom validation error response.
     *
     * @param  array $errors || string $errors
     * @param  integer $code
     * @return \Illuminate\Http\Response
     */
    public function sendValidationError($errors = [], $code = 422)
    {
        return response()->json($errors, $code);
    }

    /**
     * Return error response.
     *
     *
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function sendException(\Exception $exception)
    {


        if($exception instanceof ValidationException) {
            $response = [
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage(),
                    'type' => 'ValidationException',
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()
                ]
            ];

            return response()->json($response, 400);

        } else if($exception instanceof AuthorizationException) {

            $response = [
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage(),
                    'type' => 'AuthorizationException',
                    'code' => 401,
                ]
            ];
            return response()->json($response, 401);

        } else if($exception instanceof QueryException) {

            $response = [
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage(),
                    'type' => 'QueryException',
                    'code' => $exception->getCode(),
                ]
            ];
            return response()->json($response, 422);

        } else {

            $response = [
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage(),
                    'type' => 'Exception',
                    'code' => $exception->getCode(),
                    'trace_id' => 'xxx'
                ]
            ];

            return response()->json($response, 400);
        }
    }
}
