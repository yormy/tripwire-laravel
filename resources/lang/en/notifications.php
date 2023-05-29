<?php

return [
    'mail' => [
        'subject' => 'Tripwire blocked a possible attack on :domain',
        'message' => 'A possible attack on :domain has been detected from :ip address. The ip address has been blocked',

    ],

    'slack' => [
        'message' => 'A possible attack on :domain has been detected.',
    ],
];
