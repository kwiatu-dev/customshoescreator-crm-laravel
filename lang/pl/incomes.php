<?php

declare(strict_types=1);

return [
    'notifications' => [
        'create' => [
            'related_with_user' => [
                'message' => 'link[:auth_url](:auth_fullname) dodał/a przychód link[:income_url](:income_title) o statusie :income_status_name',
            ],
            'related_with_project' => [
                'message' => 'Został dodany przychód link[:income_url](:income_title), który jest powiązany z link[:project_url](:project_title)',
            ]
        ],
        'update' => [
            'related_with_user' => [
                'message' => 'link[:auth_url](:auth_fullname) edytował/a przychód link[:income_url](:income_title)',
            ],
            'related_with_project' => [
                'message' => 'Został edytowany przychód link[:income_url](:income_title), który jest powiązany z link[:project_url](:project_title)',
            ]
        ],
        'delete' => [
            'related_with_user' => [
                'message' => 'link[:auth_url](:auth_fullname) usunął/a przychód link[:income_url](:income_title)',
            ],
            'related_with_project' => [
                'message' => 'Został usunięty przychód link[:income_url](:income_title), który jest powiązany z link[:project_url](:project_title)',
            ]
        ],
        'restore' => [
            'related_with_user' => [
                'message' => 'link[:auth_url](:auth_fullname) przywrócił/a przychód link[:income_url](:income_title)',
            ],
            'related_with_project' => [
                'message' => 'Został przywrócony przychód link[:income_url](:income_title), który jest powiązany z link[:project_url](:project_title)',
            ],
        ],
        'status' => [
            'related_with_user' => [
                'message' => 'link[:auth_url](:auth_fullname) zmienił/a status przychodu link[:income_url](:income_title) na :income_status_name',
            ],
            'related_with_project' => [
                'message' => 'Został zmieniony status przychodu link[:income_url](:income_title) na :income_status_name, który jest powiązany z link[:project_url](:project_title)',
            ]
        ]
    ],
];