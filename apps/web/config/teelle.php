<?php

return [
    // DEC-019: owner-approved presentation baseline; never inserted as play events.
    'heartbeat_baseline' => 110,

    // A dedicated production secret may be supplied; APP_KEY remains a secure fallback.
    'free_play_ip_hash_key' => env('FREE_PLAY_IP_HASH_KEY') ?: env('APP_KEY'),
];
