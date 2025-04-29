<?php

declare(strict_types=1);

return [
    'notifications' => [
        'create' => [
            'message' => 'link[:auth_url](:auth_fullname) dodał/a spłatę inwestycji link[:investment_url](#:investment_id) o numerze #:investment_repayment_id, której inwestorem jest link[:investment_investor_url](:investment_investor_fullname)',
        ],
        'update' => [
            'message' => 'link[:auth_url](:auth_fullname) edytował/a spłatę inwestycji link[:investment_url](#:investment_id) o numerze #:investment_repayment_id, której inwestorem jest link[:investment_investor_url](:investment_investor_fullname)',
        ],
        'delete' => [
            'message' => 'link[:auth_url](:auth_fullname) usunął/a spłatę inwestycji link[:investment_url](#:investment_id) o numerze #:investment_repayment_id, której inwestorem jest link[:investment_investor_url](:investment_investor_fullname)',
        ],
        'restore' => [
            'message' => 'link[:auth_url](:auth_fullname) przywrócił/a spłatę inwestycji link[:investment_url](#:investment_id) o numerze #:investment_repayment_id, której inwestorem jest link[:investment_investor_url](:investment_investor_fullname)',
        ],
    ],
];