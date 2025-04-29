<?php

declare(strict_types=1);

return [
    'notifications' => [
        'create' => [
            'message' => 'link[:auth_url](:auth_fullname) dodał/a użytkownika link[:user_url](:user_fullname)',
        ],
        'update' => [
            'message' => 'link[:auth_url](:auth_fullname) edytował/a użytkownika link[:user_url](:user_fullname)',
        ],
        'delete' => [
            'message' => 'link[:auth_url](:auth_fullname) usunął/a użytkownika link[:user_url](:user_fullname)',
        ],
        'restore' => [
            'message' => 'link[:auth_url](:auth_fullname) przywrócił/a użytkownika link[:user_url](:user_fullname)',
        ],
        'password_reset_link' => [
            'message' => 'link[:auth_url](:auth_fullname) wysłał/a link do zresetowania hasła użytkownikowi link[:user_url](:user_fullname)',
        ],
        'password_reset' => [
            'message' => 'link[:user_url](:user_fullname) zresetował/a hasło',
        ],
    ],
];