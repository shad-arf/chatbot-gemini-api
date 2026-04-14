<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBotRequest;
use App\Http\Requests\UpdateBotRequest;
use App\Models\Bot;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class BotController extends Controller
{
    #[OA\Post(
        path: '/api/bots',
        summary: 'Register a new Bot configuration',
        description: 'Creates a bot with a specific instruction and returns a unique key for the frontend.',
        tags: ['Bots'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'instruction'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Friendly Support Bot'),
                    new OA\Property(property: 'instruction', type: 'string', example: 'You are a warm and friendly support assistant.'),
                    new OA\Property(property: 'origin_url', type: 'string', example: 'https://frontend.com'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Bot created successfully'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    public function store(StoreBotRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['instruction']) && ! $request->hasFile('instruction_files')) {
            return response()->json(['error' => 'Either text instruction or instruction files are required.'], 422);
        }

        if ($request->hasFile('instruction_files')) {
            $stored = [];
            foreach ($request->file('instruction_files') as $file) {
                $stored[] = [
                    'path' => $file->store('bot-instructions', 'public'),
                    'mime' => $file->getMimeType(),
                    'name' => $file->getClientOriginalName(),
                ];
            }
            $data['instruction_files'] = $stored;
            $data['instruction'] ??= 'Refer to the attached files for instructions.';
        }

        $bot = Bot::create($data);

        return response()->json([
            'message' => 'Bot registered successfully',
            'key' => $bot->key,
            'name' => $bot->name,
        ], 201);
    }

    #[OA\Get(
        path: '/api/bots/{key}',
        summary: 'Retrieve bot configuration',
        tags: ['Bots'],
        parameters: [
            new OA\Parameter(name: 'key', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 404, description: 'Bot not found'),
        ]
    )]
    public function show(Bot $bot): JsonResponse
    {
        return response()->json($bot);
    }

    #[OA\Put(
        path: '/api/bots/{key}',
        summary: 'Update bot configuration',
        description: 'Updates a bot with new name or instruction using its unique key.',
        tags: ['Bots'],
        parameters: [
            new OA\Parameter(name: 'key', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Friendly Support Bot (Updated)'),
                    new OA\Property(property: 'instruction', type: 'string', example: 'You are an even warmer support assistant.'),
                    new OA\Property(property: 'origin_url', type: 'string', example: 'https://new-frontend.com'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Bot updated successfully'),
            new OA\Response(response: 404, description: 'Bot not found'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    public function update(UpdateBotRequest $request, Bot $bot): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('instruction_files')) {
            $stored = [];
            foreach ($request->file('instruction_files') as $file) {
                $stored[] = [
                    'path' => $file->store('bot-instructions', 'public'),
                    'mime' => $file->getMimeType(),
                    'name' => $file->getClientOriginalName(),
                ];
            }
            $data['instruction_files'] = $stored;
        }

        $bot->update($data);

        return response()->json([
            'message' => 'Bot updated successfully',
            'bot' => $bot,
        ]);
    }

    #[OA\Delete(
        path: '/api/bots/{key}',
        summary: 'Delete bot configuration',
        tags: ['Bots'],
        parameters: [
            new OA\Parameter(name: 'key', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Bot deleted successfully'),
            new OA\Response(response: 404, description: 'Bot not found'),
        ]
    )]
    public function destroy(Bot $bot): JsonResponse
    {
        $bot->delete();

        return response()->json(['message' => 'Bot deleted successfully']);
    }
}
