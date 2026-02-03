<?php

declare(strict_types = 1);

return [
    'system' => [
        'app' => 'An unexpected error occurred in the application.',
        'external_service' => 'Failed to communicate with external service: :service.',
        'http' => [
            'access_denied' => 'Access denied to resource.',
            'bad_request' => 'Bad request made to resource.',
            'gateway_timeout' => 'The server, while acting as a gateway or proxy, did not receive a timely response from the upstream server.',
            'internal_server_error' => 'The server encountered an internal error while accessing resource.',
            'method_not_allowed' => 'The HTTP method used is not allowed for resource.',
            'not_found' => 'The requested resource was not found.',
            'service_unavailable' => 'The server is currently unavailable to handle requests.',
            'session_expired' => 'The user session has expired while accessing resource.',
            'too_many_requests' => 'Too many requests have been made to resource.',
            'unauthorized' => 'Unauthorized access attempt to resource.',
            'unprocessable_entity' => 'The server cannot process the request due to semantic errors.',
        ],
    ],

    'user' => [
        'app' => 'An unexpected error occurred in the application.',
        'external_service' => 'Failed to communicate with external service: :service.',
        'http' => [
            'access_denied' => 'You do not have permission to access the resource.',
            'bad_request' => 'The request made was invalid for the resource.',
            'gateway_timeout' => 'The server is taking too long to respond. Please try again later.',
            'internal_server_error' => 'The server encountered an error while processing your request. Please try again later.',
            'method_not_allowed' => 'The method used is not allowed for this resource.',
            'not_found' => 'The requested resource could not be found.',
            'service_unavailable' => 'The server is currently unavailable. Please try again later.',
            'session_expired' => 'Your session has expired. Please log in again.',
            'too_many_requests' => 'You have made too many requests in a short period. Please slow down.',
            'unauthorized' => 'You need to be logged in to access this resource.',
            'unprocessable_entity' => 'There were issues with your request. Please check and try again.',
        ],
    ],
];
