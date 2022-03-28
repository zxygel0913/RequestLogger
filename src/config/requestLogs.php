<?php

return [
    'channel' => 'daily', //Log Channel
    'logs' => [
        'headers' => true,
        'path' => true,
        'ip' => true,
        'request_method' => true,
        'request' => true,
        'response' => false,
        'request_except' => ['password'] //Hide specific request from logging
    ],
];

