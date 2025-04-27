<?php

declare(strict_types=1);

return [
    'notifications' => [
        'create' => [
            'message' => 'link[:auth_url](:auth_fullname) dodał/a projekt link[:project_url](:project_title) i przypisał go do link[:recipient_url](:recipient_fullname)',
        ],
        'update' => [
            'message' => 'link[:auth_url](:auth_fullname) edytował/a projekt link[:project_url](:project_title), który jest przypisany do link[:recipient_url](:recipient_fullname)',
        ],
        'delete' => [
            'message' => 'link[:auth_url](:auth_fullname) usunął/a projekt link[:project_url](:project_title), który jest przypisany do link[:recipient_url](:recipient_fullname)',
        ],
        'restore' => [
            'message' => 'link[:auth_url](:auth_fullname) przywrócił/a projekt link[:project_url](:project_title), który jest przypisany do link[:recipient_url](:recipient_fullname)',
        ],
        'status' => [
            'message' => 'link[:auth_url](:auth_fullname) zmienił/a status projektu link[:project_url](:project_title), który jest przypisany do link[:recipient_url](:recipient_fullname) na :status_name',
        ]
    ],
];