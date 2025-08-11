<?php

return [
    'token' => env('SIMTEPA_TOKEN', 'YOUR_SIMTEPA_TOKEN'),
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
