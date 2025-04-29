<?php

declare(strict_types=1);

return [
    'notifications' => [
        'create' => [
            'message' => 'link[:auth_url](:auth_fullname) dodał/a wydarzenie link[:user_event_url](:user_event_title) typu :user_event_type_name i przypisał go do link[:recipient_url](:recipient_fullname)',
        ],
        'update' => [
            'message' => 'link[:auth_url](:auth_fullname) edytował/a wydarzenie link[:user_event_url](:user_event_title) typu :user_event_type_name, które jest przypisane do link[:recipient_url](:recipient_fullname)',
        ],
        'delete' => [
            'message' => 'link[:auth_url](:auth_fullname) usunął/a wydarzenie link[:user_event_url](:user_event_title)typu :user_event_type_name, które jest przypisane do link[:recipient_url](:recipient_fullname)',
        ],
        'restore' => [
            'message' => 'link[:auth_url](:auth_fullname) przywrócił/a wydarzenie link[:user_event_url](:user_event_title) typu :user_event_type_name, które jest przypisane do link[:recipient_url](:recipient_fullname)',
        ],
    ],
];