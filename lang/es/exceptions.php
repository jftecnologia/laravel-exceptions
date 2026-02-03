<?php

declare(strict_types = 1);

return [
    'system' => [
        'app' => 'Ocurrió un error inesperado en la aplicación.',
        'external_service' => 'Error al comunicarse con el servicio externo: :service.',
        'http' => [
            'access_denied' => 'Acceso denegado al recurso.',
            'bad_request' => 'Solicitud incorrecta realizada al recurso.',
            'gateway_timeout' => 'El servidor, actuando como gateway o proxy, no recibió una respuesta oportuna del servidor upstream.',
            'internal_server_error' => 'El servidor encontró un error interno al acceder al recurso.',
            'method_not_allowed' => 'El método HTTP utilizado no está permitido para el recurso.',
            'not_found' => 'El recurso solicitado no fue encontrado.',
            'service_unavailable' => 'El servidor no está disponible actualmente para manejar solicitudes.',
            'session_expired' => 'La sesión del usuario ha expirado al acceder al recurso.',
            'too_many_requests' => 'Se han realizado demasiadas solicitudes al recurso.',
            'unauthorized' => 'Intento de acceso no autorizado al recurso.',
            'unprocessable_entity' => 'El servidor no puede procesar la solicitud debido a errores semánticos.',
        ],
    ],

    'user' => [
        'app' => 'Ocurrió un error inesperado en la aplicación.',
        'external_service' => 'Error al comunicarse con el servicio externo: :service.',
        'http' => [
            'access_denied' => 'No tienes permiso para acceder a este recurso.',
            'bad_request' => 'La solicitud realizada fue inválida para el recurso.',
            'gateway_timeout' => 'El servidor está tardando demasiado en responder. Por favor, inténtalo de nuevo más tarde.',
            'internal_server_error' => 'El servidor encontró un error al procesar tu solicitud. Por favor, inténtalo de nuevo más tarde.',
            'method_not_allowed' => 'El método utilizado no está permitido para este recurso.',
            'not_found' => 'El recurso solicitado no pudo ser encontrado.',
            'service_unavailable' => 'El servidor no está disponible actualmente. Por favor, inténtalo de nuevo más tarde.',
            'session_expired' => 'Tu sesión ha expirado. Por favor, inicia sesión de nuevo.',
            'too_many_requests' => 'Has realizado demasiadas solicitudes en un corto período. Por favor, reduce la velocidad.',
            'unauthorized' => 'Necesitas iniciar sesión para acceder a este recurso.',
            'unprocessable_entity' => 'Hubo problemas con tu solicitud. Por favor, verifica e inténtalo de nuevo.',
        ],
    ],
];
