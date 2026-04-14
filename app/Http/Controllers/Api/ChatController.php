<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRequest;
use App\Models\Bot;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Chatbot Developer API',
    description: 'API documentation for the secure, backend-only Chatbot service.'
)]
#[OA\Server(
    url: 'http://chatbot-api.test',
    description: 'Herd Local Development Server'
)]
class ChatController extends Controller
{
    #[OA\Post(
        path: '/api/chat',
        summary: 'Send a message to a specific bot',
        description: 'Uses a Bot Key to resolve the system instructions silently from the database.',
        tags: ['Chat'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['message', 'bot_key'],
                properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'Hello!'),
                    new OA\Property(property: 'bot_key', type: 'string', example: 'uuid-key-goes-here'),
                    new OA\Property(
                        property: 'history',
                        type: 'array',
                        items: new OA\Items(properties: [
                            new OA\Property(property: 'role', type: 'string', enum: ['user', 'model']),
                            new OA\Property(property: 'parts', type: 'array', items: new OA\Items(properties: [
                                new OA\Property(property: 'text', type: 'string'),
                            ])),
                        ])
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'response', type: 'string')])
            ),
            new OA\Response(response: 404, description: 'Bot not found'),
            new OA\Response(response: 500, description: 'Gemini API Error'),
        ]
    )]
    public function chat(ChatRequest $request): JsonResponse
    {
        // 1. Resolve Bot from DB
        $bot = Bot::where('key', $request->bot_key)->first();

        if (! $bot) {
            return response()->json(['error' => 'Bot configuration not found for provided key'], 404);
        }

        // 2. Build messages array in OpenAI format
        $messages = [];

        // System instruction (text)
        if ($bot->instruction) {
            $messages[] = ['role' => 'system', 'content' => $bot->instruction];
        }

        // Knowledge base files — prepend as a system-level user message
        if (! empty($bot->instruction_files)) {
            $knowledgeParts = [['type' => 'text', 'text' => 'SYSTEM REFERENCE FILES (FOLLOW THESE INSTRUCTIONS STRICTLY):']];
            foreach ($bot->instruction_files as $fileEntry) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($fileEntry['path'])) {
                    $base64 = base64_encode(\Illuminate\Support\Facades\Storage::disk('public')->get($fileEntry['path']));
                    $knowledgeParts[] = [
                        'type' => 'image_url',
                        'image_url' => ['url' => "data:{$fileEntry['mime']};base64,{$base64}"],
                    ];
                }
            }
            if (count($knowledgeParts) > 1) {
                $messages[] = ['role' => 'user', 'content' => $knowledgeParts];
                $messages[] = [
                    'role' => 'assistant',
                    'content' => 'System reference files acknowledged. I will follow the instructions provided.',
                ];
            }
        }

        // 3. Convert history from Gemini format {role, parts[]} to OpenAI format {role, content}
        foreach ($request->input('history', []) as $turn) {
            $role = $turn['role'] === 'model' ? 'assistant' : $turn['role'];
            $content = implode('', array_column($turn['parts'], 'text'));
            $messages[] = ['role' => $role, 'content' => $content];
        }

        // 4. Current user message (with optional file attachments)
        if ($request->hasFile('files')) {
            $userContent = [['type' => 'text', 'text' => $request->message]];
            foreach ($request->file('files') as $file) {
                $mimeType = $file->getMimeType();
                $base64 = base64_encode(file_get_contents($file->path()));
                $userContent[] = [
                    'type' => 'image_url',
                    'image_url' => ['url' => "data:{$mimeType};base64,{$base64}"],
                ];
            }
        } else {
            $userContent = $request->message;
        }

        $messages[] = ['role' => 'user', 'content' => $userContent];

        // 5. Call OpenRouter API (OpenAI-compatible)
        $apiKey = config('services.openrouter.key');
        $model = config('services.openrouter.model');

        $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])
            ->connectTimeout(15)
            ->timeout(60)
            ->retry(3, 200, fn ($e) => $e instanceof \Illuminate\Http\Client\ConnectionException)
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $model,
                'messages' => $messages,
            ]);

        if ($response->failed()) {
            return response()->json(['error' => 'OpenRouter connection failed', 'details' => $response->json()], 500);
        }

        // 6. Parse response
        $data = $response->json();
        $text = $data['choices'][0]['message']['content'] ?? 'No response generated.';

        return response()->json(['response' => $text]);
    }
}
