<?php

return [
    'token' => env('SIMTEPA_TOKEN', 'c51f86e55175331d47ef4d0b12533254'),
    'base'  => 'https://simtepa.mahkamahagung.go.id/share',

    // endpoint json yang akan diambil
    'paths' => [
        'profil_ketua',
        'profil_wakil',
        'profil_hakim',
        'profil_kepaniteraan',
        'profil_kesekretariatan',
        'profil_fungsional',
        'profil_pelaksana',
        // 'profil_ppnpn', // next...
    ],
];
