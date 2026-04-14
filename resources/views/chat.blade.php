<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>تاڤیار - چاتبۆتی کوردی</title>
    <link rel="icon" href="/storage/logo.png" type="image/png">
    <script src="https://cdn.tailwindcss.com?v={{ time() }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js?v={{ time() }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
        }

        html, body {
            height: 100dvh;
            width: 100dvw;
        }

        .message-enter {
            animation: slideInUp 0.3s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .typing-dot {
            width: 6px;
            height: 6px;
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
            0%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-6px);
            }
        }

        #messagesContainer {
            height: calc(100dvh - 120px);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        footer {
            height: auto;
            min-height: 120px;
        }

        input[type="text"] {
            font-size: 16px;
        }

        button {
            font-size: 16px;
        }
    </style>
</head>
<body class="overflow-hidden bg-gray-900 text-gray-100" style="background-color: #252728;">

    <header class="bg-gray-800 border-b border-gray-700 flex-shrink-0 shadow-lg" style="background-color: #1f2937;">
        <div class="max-w-4xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="/storage/logo.png" alt="Logo" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                <span class="text-xs text-green-400 flex items-center gap-2 whitespace-nowrap">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse flex-shrink-0"></span> ئۆنلاین
                </span>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="location.reload()" class="text-lg cursor-pointer hover:opacity-70 transition-opacity p-2 active:scale-95" title="نوێکردنەوە">
                    ↻
                </button>
                <button onclick="clearChat()" class="text-lg cursor-pointer hover:opacity-70 transition-opacity p-2 active:scale-95" title="سڕینەوە">
                    🗑️
                </button>
            </div>
        </div>
    </header>

    @if (!$bot)
        <main class="flex items-center justify-center p-4" style="height: calc(100dvh - 60px);">
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 sm:p-8 text-center max-w-md" style="background-color: #1f2937;">
                <div class="text-4xl mb-4">🤖</div>
                <h2 class="text-lg sm:text-2xl font-bold mb-2">{{ $error ?? 'چاتبۆت بەردەست نیە' }}</h2>
                <p class="text-sm sm:text-base text-gray-400 mb-6">تکایە بۆ <a href="/admin" class="text-blue-400 hover:text-blue-300">بەڕێوەبەری سیستەم</a> بڕۆ و چاتبۆتێک دروست بکە.</p>
            </div>
        </main>
    @else
        <main class="flex flex-col gap-3 sm:gap-4 p-3 sm:p-4 overflow-y-auto -webkit-overflow-scrolling-touch" id="messagesContainer" style="height: calc(100dvh - 180px);">
            <div class="flex gap-2 sm:gap-3 max-w-[90%] sm:max-w-[85%]">
                <img src="/storage/logo.png" alt="Bot" class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 object-cover">
                <div class="rounded-lg p-2 sm:p-3 text-xs sm:text-sm" style="background-color: #1f2937; border: 1px solid #374151; border-top-right-radius: 4px;">
                    <p>بەخێربێیت! چۆن دەتوانم ئەمڕۆ هاوکاریت بکەم؟</p>
                </div>
            </div>
        </main>

        <footer class="fixed bottom-0 left-0 right-0 w-full border-t border-gray-700" style="background-color: #252728;">
            <div class="max-w-4xl mx-auto px-3 sm:px-4 py-2 sm:py-3">
                <div class="flex gap-2">
                    <input type="text"
                           id="userInput"
                           placeholder="پەیامەکەت لێرە بنووسە..."
                           class="flex-1 bg-gray-800 border border-gray-700 text-white rounded-lg py-3 px-3 sm:px-4 focus:outline-none focus:border-[#020461] focus:ring-1 focus:ring-[#020461] transition-all text-base" style="background-color: #1f2937; border-color: #374151; -webkit-appearance: none; border-radius: 8px;"
                           onkeypress="if(event.key === 'Enter') sendMessage()"
                           autocomplete="off"
                           autocorrect="off"
                           autocapitalize="off"
                           spellcheck="false">
                    <button onclick="sendMessage()" id="sendBtn" class="bg-[#020461] hover:bg-[#030578] text-white rounded-lg px-3 sm:px-4 py-3 transition-all flex items-center justify-center flex-shrink-0 active:scale-95" title="بنێرە" style="min-height: 48px; min-width: 48px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5" style="transform: rotate(-90deg);">
                            <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                        </svg>
                    </button>
                </div>
                <p class="text-center text-[10px] text-gray-600 mt-2 py-1">
                    هێزگیراوە لەلایەن تاڤیار AI - ٢٠٢٦
                </p>
            </div>
        </footer>

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
                        <div class="flex gap-2 sm:gap-3 max-w-[90%] sm:max-w-[85%]">
                            <img src="/storage/logo.png" alt="Bot" class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 object-cover">
                            <div class="rounded-lg p-2 sm:p-3 text-xs sm:text-sm" style="background-color: #1f2937; border: 1px solid #374151; border-top-right-radius: 4px;">
                                <p>بەخێربێیت! چۆن دەتوانم ئەمڕۆ هاوکاریت بکەم؟</p>
                            </div>
                        </div>
                    `;
                }
            }

            function renderMarkdown(text) {
                if (typeof marked === 'undefined') {
                    return '<p>' + escapeHtml(text).replace(/\n/g, '<br>') + '</p>';
                }
                try {
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
                    messageDiv.className = 'flex gap-2 sm:gap-3 max-w-[90%] sm:max-w-[85%] message-enter';
                    messageDiv.innerHTML = `
                        <img src="/storage/logo.png" alt="Bot" class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 object-cover">
                        <div class="rounded-lg p-2 sm:p-3 text-xs sm:text-sm" style="background-color: #1f2937; border: 1px solid #374151; border-top-right-radius: 4px;">
                            ${htmlContent}
                        </div>
                    `;
                    if (save) {
                        const messages = loadMessages();
                        messages.push({ type: 'ai', text: text });
                        saveMessages(messages);
                    }
                } else if (type === 'user') {
                    messageDiv.className = 'flex gap-2 sm:gap-3 max-w-[90%] sm:max-w-[85%] ml-auto flex-row-reverse message-enter';
                    messageDiv.innerHTML = `
                        <div class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-xs sm:text-sm" style="background-color: #020461;">👤</div>
                        <div class="rounded-lg p-2 sm:p-3 text-xs sm:text-sm text-white break-words" style="background-color: #020461; border-top-left-radius: 4px;">
                            <p>${escapeHtml(text)}</p>
                        </div>
                    `;
                    if (save) {
                        const messages = loadMessages();
                        messages.push({ type: 'user', text: text });
                        saveMessages(messages);
                    }
                } else if (type === 'error') {
                    messageDiv.className = 'flex gap-2 sm:gap-3 max-w-[90%] sm:max-w-[85%] mx-auto message-enter';
                    messageDiv.innerHTML = `
                        <div class="rounded-lg p-2 sm:p-3 text-xs sm:text-sm text-red-300" style="background-color: rgba(127, 29, 29, 0.3); border: 1px solid rgba(239, 68, 68, 0.5);">
                            <p>${escapeHtml(text)}</p>
                        </div>
                    `;
                } else if (type === 'typing') {
                    messageDiv.className = 'flex gap-2 sm:gap-3 message-enter';
                    messageDiv.id = 'typingIndicator';
                    messageDiv.innerHTML = `
                        <img src="/storage/logo.png" alt="Bot" class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 object-cover">
                        <div class="flex gap-1 items-center p-2 sm:p-3">
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
                    const res = await fetch(`${API_BASE}/chat`, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' },
                        body: form
                    });

                    if (!res.ok) {
                        throw new Error(`HTTP Error: ${res.status}`);
                    }

                    const data = await res.json();
                    removeTypingIndicator();

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
                    input.disabled = false;
                    btn.disabled = false;
                }
            }

            window.addEventListener('load', function() {
                loadHistory();
                const savedMessages = loadMessages();
                const container = document.getElementById('messagesContainer');

                if (savedMessages.length > 0) {
                    container.innerHTML = '';

                    savedMessages.forEach(msg => {
                        const messageDiv = document.createElement('div');

                        if (msg.type === 'ai') {
                            const htmlContent = renderMarkdown(msg.text);
                            messageDiv.className = 'flex gap-2 sm:gap-3 max-w-[90%] sm:max-w-[85%]';
                            messageDiv.innerHTML = `
                                <img src="/storage/logo.png" alt="Bot" class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 object-cover">
                                <div class="rounded-lg p-2 sm:p-3 text-xs sm:text-sm" style="background-color: #1f2937; border: 1px solid #374151; border-top-right-radius: 4px;">
                                    ${htmlContent}
                                </div>
                            `;
                        } else if (msg.type === 'user') {
                            messageDiv.className = 'flex gap-2 sm:gap-3 max-w-[90%] sm:max-w-[85%] ml-auto flex-row-reverse';
                            messageDiv.innerHTML = `
                                <div class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-xs sm:text-sm" style="background-color: #020461;">👤</div>
                                <div class="rounded-lg p-2 sm:p-3 text-xs sm:text-sm text-white break-words" style="background-color: #020461; border-top-left-radius: 4px;">
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
