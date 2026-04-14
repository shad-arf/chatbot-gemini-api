# Tavyar Chatbot API

This project contains the secure, backend proxy API for interacting with the Google Gemini AI models. 

## Architectural Overview

This API acts as the middleman between your frontend (e.g., Vue 3/HTML client) and Google's Gemini servers. This offers several immense benefits:
1. **Security:** Your actual Gemini API Key is stored securely on this server. It is never exposed to the frontend, preventing malicious actors from stealing it and draining your quota.
2. **Simplicity:** The frontend only needs to send `{ "message": "...", "instruction": "..." }`. The backend constructs the huge, complex JSON structures Gemini requires.
3. **Data Scrubbing:** Gemini returns massive JSON files containing token counts, safety ratings, and metadata. This backend API strips all of that away and returns only the clean `{"response": "..."}`.

## API Documentation

Swagger API documentation has been configured and is available at:
`http://localhost:8000/api/documentation` or via Laravel Herd at `http://chatbot-api.test/api/documentation`

### Endpoint

- **POST** `/api/chat`
  - Body:
    ```json
    {
      "message": "The actual prompt or question the user wants to ask.",
      "instruction": "The system command telling the AI how to behave."
    }
    ```

## Setup Instructions

1. Ensure your `.env` file has the Gemini API Key configured:
   ```env
   GEMINI_API_KEY=your_google_ai_studio_api_key_here
   ```
2. Set up the config variables within `config/services.php` to read the key:
   ```php
   'gemini' => [
       'key' => env('GEMINI_API_KEY'),
   ],
   ```
3. Run the Swagger Generator to build documentation:
   ```bash
   php artisan l5-swagger:generate
   ```
4. Access via Postman or your web frontend.

> **CRUCIAL: Security Warning**
> Because this proxy makes interacting with Gemini extremely easy, you MUST secure it before production release to prevent anonymous users from exploiting the endpoint and exhausting your quota. Implement Sanctum auth or strict rate-limiting.
