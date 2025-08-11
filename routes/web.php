<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\frontend\pegawaiController;

Route::get('/', function () {
    return view('maintenance');
});

Route::get('pegawai', [pegawaiController::class, 'index'])
    ->name('pegawai.index');

Route::get('/test-fonnte', function () {
    $response = Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])
        ->asMultipart()
        ->post('https://api.fonnte.com/send', [
            'target'      => '085263216699', // nomor tujuan
            'message'     => 'test message dari Laravel 12',
            'countryCode' => '62',
        ]);

    return [
        'ok'     => $response->successful(),
        'status' => $response->status(),
        'body'   => $response->body(),
        'json'   => $response->json(),
    ];
});