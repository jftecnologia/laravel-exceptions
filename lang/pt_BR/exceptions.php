<?php

declare(strict_types = 1);

return [
    'system' => [
        'app' => 'Ocorreu um erro inesperado na aplicação.',
        'external_service' => 'Falha ao se comunicar com o serviço externo: :service.',
        'http' => [
            'access_denied' => 'Acesso negado ao recurso.',
            'bad_request' => 'Requisição inválida feita ao recurso.',
            'gateway_timeout' => 'O servidor, atuando como gateway ou proxy, não recebeu uma resposta oportuna do servidor upstream.',
            'internal_server_error' => 'O servidor encontrou um erro interno ao acessar o recurso.',
            'method_not_allowed' => 'O método HTTP usado não é permitido para o recurso.',
            'not_found' => 'O recurso solicitado não foi encontrado.',
            'service_unavailable' => 'O servidor está atualmente indisponível para lidar com requisições.',
            'session_expired' => 'A sessão do usuário expirou ao acessar o recurso.',
            'too_many_requests' => 'Muitas requisições foram feitas ao recurso.',
            'unauthorized' => 'Tentativa de acesso não autorizado ao recurso.',
            'unprocessable_entity' => 'O servidor não pode processar a requisição devido a erros semânticos.',
        ],
    ],

    'user' => [
        'app' => 'Ocorreu um erro inesperado na aplicação.',
        'external_service' => 'Falha ao se comunicar com o serviço externo: :service.',
        'http' => [
            'access_denied' => 'Você não tem permissão para acessar este recurso.',
            'bad_request' => 'A requisição feita foi inválida para o recurso.',
            'gateway_timeout' => 'O servidor está demorando muito para responder. Por favor, tente novamente mais tarde.',
            'internal_server_error' => 'O servidor encontrou um erro ao processar sua requisição. Por favor, tente novamente mais tarde.',
            'method_not_allowed' => 'O método usado não é permitido para este recurso.',
            'not_found' => 'O recurso solicitado não pôde ser encontrado.',
            'service_unavailable' => 'O servidor está atualmente indisponível. Por favor, tente novamente mais tarde.',
            'session_expired' => 'Sua sessão expirou. Por favor, faça login novamente.',
            'too_many_requests' => 'Você fez muitas requisições em um curto período. Por favor, diminua o ritmo.',
            'unauthorized' => 'Você precisa estar logado para acessar este recurso.',
            'unprocessable_entity' => 'Houve problemas com sua requisição. Por favor, verifique e tente novamente.',
        ],
    ],
];
