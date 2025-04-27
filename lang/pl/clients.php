<?php

declare(strict_types=1);

return [
    'notifications' => [
        'create' => [
            'message' => 'link[:auth_url](:auth_fullname) dodał/a klienta link[:client_url](:client_fullname)',
        ],
        'update' => [
            'message' => 'link[:auth_url](:auth_fullname) edytował/a klienta link[:client_url](:client_fullname)',
        ],
        'delete' => [
            'message' => 'link[:auth_url](:auth_fullname) usunął/a klienta link[:client_url](:client_fullname)',
        ],
        'restore' => [
            'message' => 'link[:auth_url](:auth_fullname) przywrócił/a klienta link[:client_url](:client_fullname)',
        ],
    ],
];