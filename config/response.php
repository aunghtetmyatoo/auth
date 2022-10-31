<?php

use Symfony\Component\HttpFoundation\Response;


return [

    /*
    |-----------------------------------------------------------------------------------------------------------
    | Error code to message mapping
    |-----------------------------------------------------------------------------------------------------------
    |
    */
    'map' => [
        Response::HTTP_OK => 'responses.success',
        Response::HTTP_CREATED => 'responses.created',
        Response::HTTP_NO_CONTENT => 'responses.no_content',
        Response::HTTP_FOUND => 'responses.found',
        Response::HTTP_BAD_REQUEST => 'responses.bad_request',
        Response::HTTP_UNAUTHORIZED => 'responses.unauthorized',
        Response::HTTP_FORBIDDEN => 'responses.forbidden',
        Response::HTTP_NOT_FOUND => 'responses.not_found',
        Response::HTTP_METHOD_NOT_ALLOWED => 'responses.method_not_allow',
        Response::HTTP_NOT_ACCEPTABLE => 'responses.not_acceptable',
        Response::HTTP_REQUEST_TIMEOUT => 'responses.timeout',
        Response::HTTP_UNPROCESSABLE_ENTITY => 'responses.invalid_parameters',
        Response::HTTP_TOO_MANY_REQUESTS => 'responses.too_many_attempts',
        Response::HTTP_INTERNAL_SERVER_ERROR => 'responses.server_error',
    ],
];
