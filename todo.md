# Tavyar Chatbot API Todo List

Here are the specific pieces that have been built or still need to be addressed to realize the architecture:

- [x] **1. Endpoint Design:**
  - `POST /api/chat` route has been created and mapped to `ChatController@chat`. This is the single entrypoint for clients.
  - Swagger documentation has been configured for the API point if you access `/api/documentation`.

- [x] **2. Receiving Simple Data:**
  - `ChatRequest` class validates that only the bare minimum `message` and `instruction` are accepted.

- [x] **3. Payload Transformation (The Magic):**
  - Inside `ChatController`, the simple inputs are transformed into the complex nested JSON payload expected by the Gemini API (`contents[role=user][parts][text]` and `system_instruction[parts][text]`).

- [x] **4. Sending the Request to Google:**
  - An HTTP request is dispatched in the background to the `gemini-2.5-flash` endpoint using Laravel's HTTP Client.

- [x] **5. Response Parsing:**
  - The massive JSON response from Google is parsed in `ChatController`, drilling down into `candidates[0].content.parts[0].text` to extract just the AI content.

- [x] **6. Returning a Clean Response:**
  - The API returns a simple `{"response": "Extracted text"}` JSON payload.

### Next Steps for You:
- [ ] Add `GEMINI_API_KEY=your_key_here` to your `.env` file to authorize background requests.
- [ ] Implement rate-limiting/security on the `/api/chat` route to prevent abuse (quota draining).
- [ ] Deploy and verify with Postman.
