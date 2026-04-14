<?php

use App\Http\Controllers\Api\BotController;
use App\Http\Controllers\Api\ChatController;
use Illuminate\Support\Facades\Route;

// Temporary upload debug route
Route::post('/debug-upload', function (\Illuminate\Http\Request $request) {
    $info = [];
    foreach ($_FILES as $key => $fileData) {
        $info[$key] = $fileData;
    }
    return response()->json([
        'php_files' => $info,
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size'       => ini_get('post_max_size'),
        'has_files'           => $request->hasFile('instruction_files'),
    ]);
});

// Bot Lifecycle Management
Route::post('/bots', [BotController::class, 'store']);
Route::get('/bots/{bot}', [BotController::class, 'show']);
Route::put('/bots/{bot}', [BotController::class, 'update']);
Route::delete('/bots/{bot}', [BotController::class, 'destroy']);

// Chat API (Proxy to Gemini) - Rate limited to 30 requests per minute per IP
Route::post('/chat', [ChatController::class, 'chat'])->middleware('throttle:30,1');
