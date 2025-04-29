<?php

declare(strict_types=1);

return [
    'notifications' => [
        'create' => [
            'message' => 'link[:auth_url](:auth_fullname) dodał/a inwestycje link[:investment_url](#:investment_id), której inwestorem jest link[:investment_investor_url](:investment_investor_fullname)',
        ],
        'update' => [
            'message' => 'link[:auth_url](:auth_fullname) edytował/a inwestycje link[:investment_url](#:investment_id), której inwestorem jest link[:investment_investor_url](:investment_investor_fullname)',
        ],
        'delete' => [
            'message' => 'link[:auth_url](:auth_fullname) usunął/a inwestycje link[:investment_url](#:investment_id), której inwestorem jest link[:investment_investor_url](:investment_investor_fullname)',
        ],
        'restore' => [
            'message' => 'link[:auth_url](:auth_fullname) przywrócił/a inwestycje link[:investment_url](#:investment_id), której inwestorem jest link[:investment_investor_url](:investment_investor_fullname)',
        ],
        'status' => [
            'message' => 'link[:auth_url](:auth_fullname) zmienił/a status inwestycji link[:investment_url](#:investment_id) na :investment_status_name, której inwestorem jest link[:investment_investor_url](:investment_investor_fullname)',
        ],
    ],
];