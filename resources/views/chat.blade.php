<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تاڤیار - چاتبۆتی کوردی</title>
    <link rel="icon" href="/storage/logo.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;400;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            font-family: 'Vazirmatn', sans-serif;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        header {
            flex-shrink: 0;
        }

        .chat-container {
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            padding: 1rem;
            gap: 1rem;
        }

        footer {
            flex-shrink: 0;
            width: 100%;
            border-top: 1px solid #1f2937;
        }

        .message-enter {
            animation: slideInUp 0.3s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-bubble {
            padding: 12px 16px;
            border-radius: 16px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.5;
        }

        .chat-bubble p, .chat-bubble ul, .chat-bubble ol {
            margin: 8px 0;
        }

        .chat-bubble p:first-child {
            margin-top: 0;
        }

        .chat-bubble p:last-child {
            margin-bottom: 0;
        }

        .chat-bubble strong {
            font-weight: 700;
            color: inherit;
        }

        .chat-bubble ul, .chat-bubble ol {
            padding-right: 20px;
        }

        .chat-bubble li {
            margin: 6px 0;
            list-style-position: inside;
        }

        .chat-bubble code {
            background: rgba(0, 0, 0, 0.2);
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }

        .ai-message {
            display: flex;
            gap: 12px;
            max-width: 80%;
            align-items: flex-start;
            margin-inline-end: auto;
        }

        .user-message {
            display: flex;
            gap: 12px;
            flex-direction: row-reverse;
            max-width: 80%;
            margin-inline-start: auto;
            align-items: flex-start;
        }

        .message-avatar {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .ai-bubble {
            background-color: #1f2937;
            color: #f3f4f6;
            border: 1px solid #374151;
            border-top-right-radius: 4px;
        }

        .user-bubble {
            background-color: #020461;
            color: white;
            border-top-left-radius: 4px;
        }

        .error-bubble {
            background-color: rgba(127, 29, 29, 0.3);
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #fca5a5;
        }

        .typing-animation {
            display: flex;
            gap: 6px;
            align-items: center;
            padding: 12px 16px;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background-color: #9CA3AF;
            border-radius: 50%;
            animation: bounce 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes bounce {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media (max-width: 768px) {
            .ai-message, .user-message {
                max-width: 95%;
            }

            footer {
                padding: 1rem 0;
            }

            .action-btn {
                padding: 6px 10px;
            }

            #userInput {
                padding: 10px 12px;
            }

            .header-left, .header-right {
                gap: 8px;
            }

            img.w-10 {
                width: 32px;
                height: 32px;
            }
        }
    </style>
</head>
<body class="text-gray-100" style="background-color: #252728;">

    <header class="bg-gray-800 border-b border-gray-700 sticky top-0 z-10 shadow-lg">
        <div class="max-w-4xl mx-auto px-4 py-4 header-content">
            <div class="header-left">
                <img src="/storage/logo.png" alt="Logo" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                <span class="text-xs text-green-400 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span> ئۆنلاین
                </span>
            </div>
            <div class="header-right">
                <button onclick="location.reload()" class="text-xl cursor-pointer hover:opacity-70 transition-opacity" title="نووێن کردن">
                    ↻
                </button>
                <button onclick="clearChat()" class="text-xl cursor-pointer hover:opacity-70 transition-opacity" title="پاکی کردن">
                    🗑️
                </button>
            </div>
        </div>
    </header>

    @if (!$bot)
        <main class="max-w-4xl mx-auto p-4 chat-container overflow-y-auto flex items-center justify-center">
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-8 text-center max-w-md">
                <div class="text-4xl mb-4">🤖</div>
                <h2 class="text-2xl font-bold mb-2">{{ $error ?? 'چاتبۆت بەردەست نیە' }}</h2>
                <p class="text-gray-400 mb-6">تکایە بۆ <a href="/admin" class="text-blue-400 hover:text-blue-300">بەڕێوەبێری سیستەم</a> بڕۆ و چاتبۆتێک دروست بکە.</p>
            </div>
        </main>
    @else
        <main class="chat-container scrollbar-hide" id="messagesContainer">
            <div class="ai-message">
                <img src="/storage/logo.png" alt="Bot" class="message-avatar object-cover">
                <div class="chat-bubble ai-bubble animate-fadeIn text-sm">
                    <p>بەخێربێیت! چۆن دەتوانم ئەمڕۆ هاوکاریت بکەم؟</p>
                </div>
            </div>
        </main>

        <footer class="border-t border-gray-800" style="background-color: #252728;">
            <div class="max-w-4xl mx-auto px-4 py-3">
                <div class="relative flex items-center gap-2">
                    <input type="text"
                           id="userInput"
                           placeholder="پەیامەکەت لێرە بنووسە..."
                           class="flex-1 bg-gray-800 border border-gray-700 text-white rounded-lg py-3 px-4 focus:outline-none focus:border-[#020461] focus:ring-1 focus:ring-[#020461] transition-all text-sm"
                           onkeypress="if(event.key === 'Enter') sendMessage()">

                    <button onclick="sendMessage()" id="sendBtn" class="action-btn bg-[#020461] hover:bg-[#030578] text-white flex-shrink-0" title="بنێرە">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -rotate-90">
                            <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                        </svg>
                    </button>
                </div>
                <p class="text-center text-[10px] text-gray-600 mt-2">
                    هێزگیراوە لەلایەن تاڤیار AI - ٢٠٢٦
                </p>
            </div>
        </footer>

        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
        </style>

        <script>
            const API_BASE = '/api';
            let history = [];
            let botKey = '{{ $bot->key }}';
            const STORAGE_KEY = `chat_history_${botKey}`;
            const MESSAGES_STORAGE_KEY = `chat_messages_${botKey}`;

            function generateUUID() {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    const r = Math.random() * 16 | 0;
                    const v = c === 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            }

            function getUserToken() {
                let token = localStorage.getItem('user_token');
                if (!token) {
                    token = generateUUID();
                    localStorage.setItem('user_token', token);
                }
                return token;
            }

            function saveHistory() {
                try {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(history));
                } catch (e) {
                    console.error('Failed to save history:', e);
                }
            }

            function loadHistory() {
                try {
                    const saved = localStorage.getItem(STORAGE_KEY);
                    if (saved) {
                        history = JSON.parse(saved);
                        return true;
                    }
                } catch (e) {
                    console.error('Failed to load history:', e);
                }
                return false;
            }

            function saveMessages(messages) {
                try {
                    localStorage.setItem(MESSAGES_STORAGE_KEY, JSON.stringify(messages));
                } catch (e) {
                    console.error('Failed to save messages:', e);
                }
            }

            function loadMessages() {
                try {
                    const saved = localStorage.getItem(MESSAGES_STORAGE_KEY);
                    if (saved) {
                        return JSON.parse(saved);
                    }
                } catch (e) {
                    console.error('Failed to load messages:', e);
                }
                return [];
            }

            function clearChat() {
                if (confirm('آیا دەتەوێت هەموو پیامەکان بسڕیتەوە؟')) {
                    localStorage.removeItem(STORAGE_KEY);
                    localStorage.removeItem(MESSAGES_STORAGE_KEY);
                    history = [];
                    const container = document.getElementById('messagesContainer');
                    container.innerHTML = `
                        <div class="ai-message">
                            <img src="/storage/logo.png" alt="Bot" class="message-avatar object-cover">
                            <div class="chat-bubble ai-bubble animate-fadeIn text-sm">
                                <p>بەخێربێیت! چۆن دەتوانم ئەمڕۆ هاوکاریت بکەم؟</p>
                            </div>
                        </div>
                    `;
                }
            }

            function renderMarkdown(text) {
                // Check if marked library is loaded
                if (typeof marked === 'undefined') {
                    // Fallback: escape HTML and preserve line breaks
                    return '<p>' + escapeHtml(text).replace(/\n/g, '<br>') + '</p>';
                }
                try {
                    // Use marked.parse() for newer versions, marked() for older
                    if (typeof marked.parse === 'function') {
                        return marked.parse(text);
                    }
                    return marked(text);
                } catch (e) {
                    console.error('Markdown render error:', e);
                    return '<p>' + escapeHtml(text).replace(/\n/g, '<br>') + '</p>';
                }
            }

            function addMessage(type, text, save = true) {
                const container = document.getElementById('messagesContainer');
                const messageDiv = document.createElement('div');

                if (type === 'ai') {
                    const htmlContent = renderMarkdown(text);
                    messageDiv.className = 'ai-message message-enter';
                    messageDiv.innerHTML = `
                        <img src="/storage/logo.png" alt="Bot" class="message-avatar object-cover">
                        <div class="chat-bubble ai-bubble text-sm">
                            ${htmlContent}
                        </div>
                    `;
                    if (save) {
                        const messages = loadMessages();
                        messages.push({ type: 'ai', text: text });
                        saveMessages(messages);
                    }
                } else if (type === 'user') {
                    messageDiv.className = 'user-message message-enter';
                    messageDiv.innerHTML = `
                        <div class="message-avatar text-white font-bold text-sm flex items-center justify-center" style="background-color: #020461;">👤</div>
                        <div class="chat-bubble user-bubble text-sm">
                            <p>${escapeHtml(text)}</p>
                        </div>
                    `;
                    if (save) {
                        const messages = loadMessages();
                        messages.push({ type: 'user', text: text });
                        saveMessages(messages);
                    }
                } else if (type === 'error') {
                    messageDiv.className = 'flex gap-3 message-enter justify-center w-full';
                    messageDiv.innerHTML = `
                        <div class="chat-bubble error-bubble text-sm max-w-[80%]">
                            <p>${escapeHtml(text)}</p>
                        </div>
                    `;
                } else if (type === 'typing') {
                    messageDiv.className = 'ai-message';
                    messageDiv.id = 'typingIndicator';
                    messageDiv.innerHTML = `
                        <img src="/storage/logo.png" alt="Bot" class="message-avatar object-cover">
                        <div class="typing-animation">
                            <span class="typing-dot"></span>
                            <span class="typing-dot"></span>
                            <span class="typing-dot"></span>
                        </div>
                    `;
                }

                container.appendChild(messageDiv);
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight;
                }, 0);
            }

            function removeTypingIndicator() {
                const typingDiv = document.getElementById('typingIndicator');
                if (typingDiv) typingDiv.remove();
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            async function sendMessage() {
                const message = document.getElementById('userInput').value.trim();
                const btn = document.getElementById('sendBtn');
                const input = document.getElementById('userInput');

                if (!message || !botKey) return;

                addMessage('user', message, true);
                input.value = '';
                addMessage('typing', '', false);

                // Disable both input and button while waiting for response
                input.disabled = true;
                btn.disabled = true;

                const form = new FormData();
                form.append('bot_key', botKey);
                form.append('message', message);

                history.forEach((turn, i) => {
                    form.append(`history[${i}][role]`, turn.role);
                    turn.parts.forEach((p, j) => form.append(`history[${i}][parts][${j}][text]`, p.text));
                });

                try {
                    console.log('Sending message to:', `${API_BASE}/chat`);
                    console.log('Bot Key:', botKey);

                    const res = await fetch(`${API_BASE}/chat`, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' },
                        body: form
                    });

                    console.log('Response status:', res.status);

                    if (!res.ok) {
                        throw new Error(`HTTP Error: ${res.status}`);
                    }

                    const data = await res.json();
                    removeTypingIndicator();

                    console.log('Response data:', data);

                    if (res.status === 429) {
                        addMessage('error', 'پێویست بوو چاوەڕێ بکە، بەزۆری پەیام نارسووە.', false);
                    } else if (data.response) {
                        addMessage('ai', data.response, true);
                        history.push({ role: 'user', parts: [{ text: message }] });
                        history.push({ role: 'model', parts: [{ text: data.response }] });
                        saveHistory();
                    } else if (data.error) {
                        addMessage('error', 'هەڵە: ' + data.error, false);
                    } else {
                        addMessage('error', 'هەڵە: بەڕێژ بەدەستنەکەوت', false);
                    }
                } catch (err) {
                    removeTypingIndicator();
                    console.error('Fetch Error:', err);
                    addMessage('error', 'پەیوەندی پچڕا: ' + err.message, false);
                } finally {
                    // Re-enable both input and button when response arrives
                    input.disabled = false;
                    btn.disabled = false;
                }
            }

            // Load chat history on page load
            window.addEventListener('load', function() {
                loadHistory();
                const savedMessages = loadMessages();
                const container = document.getElementById('messagesContainer');

                // Clear initial greeting if there are saved messages
                if (savedMessages.length > 0) {
                    container.innerHTML = '';

                    // Restore all messages without animation on initial load
                    savedMessages.forEach(msg => {
                        const messageDiv = document.createElement('div');

                        if (msg.type === 'ai') {
                            const htmlContent = renderMarkdown(msg.text);
                            messageDiv.className = 'ai-message';
                            messageDiv.innerHTML = `
                                <img src="/storage/logo.png" alt="Bot" class="message-avatar object-cover">
                                <div class="chat-bubble ai-bubble text-sm">
                                    ${htmlContent}
                                </div>
                            `;
                        } else if (msg.type === 'user') {
                            messageDiv.className = 'user-message';
                            messageDiv.innerHTML = `
                                <div class="message-avatar text-white font-bold text-sm flex items-center justify-center" style="background-color: #020461;">👤</div>
                                <div class="chat-bubble user-bubble text-sm">
                                    <p>${escapeHtml(msg.text)}</p>
                                </div>
                            `;
                        }

                        container.appendChild(messageDiv);
                    });
                    setTimeout(() => {
                        container.scrollTop = container.scrollHeight;
                    }, 0);
                }
            });

            getUserToken();
        </script>
    @endif

</body>
</html>
