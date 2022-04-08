<?php

return [
    'channel' => 'daily', //Log Channel
    'logs' => [
        'url' => true,
        'ip' => true,
        'request' => true,
        'response' => false,
        'request_except' => ['password'] //Hide specific request from logging
    ],
];

