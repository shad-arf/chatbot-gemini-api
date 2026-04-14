<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

try {
    $apiKey = config('services.gemini.key');
    $payload = [
        'contents' => [
            ['role' => 'user', 'parts' => [['text' => 'Hello']]],
        ],
    ];
    echo "Sending request...\n";
    $response = Http::timeout(60)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-pro-preview:generateContent?key={$apiKey}", $payload);
    echo 'Status: '.$response->status()."\n";
    print_r($response->json());
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
