<?php

declare(strict_types=1);

return [
    'notifications' => [
        'create' => [
            'message' => 'link[:auth_url](:auth_fullname) dodał/a wydatek :expenses_title o numerze #:expenses_id',
        ],
        'update' => [
            'message' => 'link[:auth_url](:auth_fullname) edytował/a wydatek :expenses_title o numerze #:expenses_id',
        ],
        'delete' => [
            'message' => 'link[:auth_url](:auth_fullname) usunął/a wydatek :expenses_title o numerze #:expenses_id',
        ],
        'restore' => [
            'message' => 'link[:auth_url](:auth_fullname) przywrócił/a wydatek :expenses_title o numerze #:expenses_id',
        ],
    ],
];