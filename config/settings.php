<?php
return [
    'settings' => [

        // Symfony Mailer
        'mail' => [
            'host' => $_ENV['MAIL_HOST'],
            'port' => $_ENV['MAIL_PORT'],
            'from' => [
                'name' => $_ENV['MAIL_FROM_NAME'],
                'address' => $_ENV['MAIL_FROM_ADDRESS'],
            ],
            'username' => $_ENV['MAIL_USERNAME'],
            'password' => $_ENV['MAIL_PASSWORD'],
        ],

    ]
];