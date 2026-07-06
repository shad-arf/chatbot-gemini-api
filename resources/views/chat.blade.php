<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>تاڤیار - چاتبۆتی کوردی</title>
    <link rel="icon" href="/storage/logo.png" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

        input[type="text"] {
            font-size: 16px;
        }

        button {
            font-size: 16px;
        }
    </style>
</head>
<body class="overflow-hidden bg-gray-950 text-gray-100 lg:p-6" style="background-color: #17191a;">
    <div class="mx-auto flex h-full w-full max-w-5xl flex-col overflow-hidden bg-[#252728] lg:h-[calc(100dvh-3rem)] lg:max-h-[900px] lg:rounded-3xl lg:border lg:border-gray-700/80 lg:shadow-2xl">

    <header class="flex-shrink-0 border-b border-gray-700/80 bg-gray-800 shadow-lg" style="background-color: #1f2937;">
        <div class="flex items-center justify-between px-4 py-3 sm:px-6">
            <div class="flex items-center gap-3">
                <img src="/storage/logo.png" alt="Logo" class="h-10 w-10 flex-shrink-0 rounded-full object-cover ring-1 ring-white/10">
                <div>
                    <h1 class="text-sm font-bold text-white sm:text-base">{{ $bot?->name ?? 'تاڤیار AI' }}</h1>
                    <span class="mt-0.5 flex items-center gap-1.5 whitespace-nowrap text-[11px] text-green-400">
                        <span class="h-1.5 w-1.5 flex-shrink-0 animate-pulse rounded-full bg-green-400"></span> ئۆنلاین
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="location.reload()" class="cursor-pointer rounded-lg p-2 text-lg transition-colors hover:bg-white/10 active:scale-95" title="نوێکردنەوە">
                    ↻
                </button>
                @if ($bot)
                    <button onclick="clearChat()" class="cursor-pointer rounded-lg p-2 text-lg transition-colors hover:bg-white/10 active:scale-95" title="سڕینەوە">
                        🗑️
                    </button>
                @endif
            </div>
        </div>
    </header>

    @if (!$bot)
        <main class="flex min-h-0 flex-1 items-center justify-center p-4">
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 sm:p-8 text-center max-w-md" style="background-color: #1f2937;">
                <div class="text-4xl mb-4">🤖</div>
                <h2 class="text-lg sm:text-2xl font-bold mb-2">{{ $error ?? 'چاتبۆت بەردەست نیە' }}</h2>
                <p class="text-sm sm:text-base text-gray-400 mb-6">تکایە بۆ <a href="/admin" class="text-blue-400 hover:text-blue-300">بەڕێوەبەری سیستەم</a> بڕۆ و چاتبۆتێک دروست بکە.</p>
            </div>
        </main>
    @else
        <main class="flex min-h-0 flex-1 flex-col gap-4 overflow-y-auto px-3 py-5 sm:px-6 lg:px-8" id="messagesContainer">
            <div class="ml-auto flex max-w-[88%] flex-row-reverse gap-2 sm:max-w-[75%] sm:gap-3">
                <img src="/storage/logo.png" alt="Bot" class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 object-cover">
                <div class="rounded-2xl rounded-tl-sm border border-gray-700 bg-gray-800 px-3.5 py-2.5 text-sm leading-7 shadow-sm">
                    <p>بەخێربێیت! چۆن دەتوانم ئەمڕۆ هاوکاریت بکەم؟</p>
                </div>
            </div>
        </main>

        <footer class="flex-shrink-0 border-t border-gray-700/80 bg-[#202223]">
            <div class="px-3 py-3 sm:px-6 sm:py-4 lg:px-8">
                <div class="flex gap-2">
                    <input type="text"
                           id="userInput"
                           placeholder="پەیامەکەت لێرە بنووسە..."
                           class="min-w-0 flex-1 rounded-xl border border-gray-600 bg-gray-800 px-3 py-3 text-base text-white shadow-inner transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:px-4"
                           onkeypress="if(event.key === 'Enter') sendMessage()"
                           autocomplete="off"
                           autocorrect="off"
                           autocapitalize="off"
                           spellcheck="false">
                    <button onclick="sendMessage()" id="sendBtn" class="flex min-h-12 min-w-12 flex-shrink-0 items-center justify-center rounded-xl bg-blue-700 px-3 py-3 text-white shadow-lg shadow-blue-950/30 transition-all hover:bg-blue-600 active:scale-95 disabled:cursor-not-allowed disabled:opacity-50 sm:px-4" title="بنێرە">
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
                        <div class="ml-auto flex max-w-[88%] flex-row-reverse gap-2 sm:max-w-[75%] sm:gap-3">
                            <img src="/storage/logo.png" alt="Bot" class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 object-cover">
                            <div class="rounded-2xl rounded-tl-sm border border-gray-700 bg-gray-800 px-3.5 py-2.5 text-sm leading-7 shadow-sm">
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
                    messageDiv.className = 'ml-auto flex max-w-[88%] flex-row-reverse gap-2 sm:max-w-[75%] sm:gap-3 message-enter';
                    messageDiv.innerHTML = `
                        <img src="/storage/logo.png" alt="Bot" class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 object-cover">
                        <div class="min-w-0 rounded-2xl rounded-tl-sm border border-gray-700 bg-gray-800 px-3.5 py-2.5 text-sm leading-7 shadow-sm break-words">
                            ${htmlContent}
                        </div>
                    `;
                    if (save) {
                        const messages = loadMessages();
                        messages.push({ type: 'ai', text: text });
                        saveMessages(messages);
                    }
                } else if (type === 'user') {
                    messageDiv.className = 'flex max-w-[88%] gap-2 sm:max-w-[75%] sm:gap-3 message-enter';
                    messageDiv.innerHTML = `
                        <div class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-xs sm:text-sm" style="background-color: #020461;">👤</div>
                        <div class="min-w-0 break-words rounded-2xl rounded-tr-sm bg-blue-700 px-3.5 py-2.5 text-sm leading-7 text-white shadow-sm">
                            <p>${escapeHtml(text)}</p>
                        </div>
                    `;
                    if (save) {
                        const messages = loadMessages();
                        messages.push({ type: 'user', text: text });
                        saveMessages(messages);
                    }
                } else if (type === 'error') {
                    messageDiv.className = 'flex gap-2 sm:gap-3 max-w-[100%] sm:max-w-[85%] mx-auto message-enter';
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
                            messageDiv.className = 'ml-auto flex max-w-[88%] flex-row-reverse gap-2 sm:max-w-[75%] sm:gap-3';
                            messageDiv.innerHTML = `
                                <img src="/storage/logo.png" alt="Bot" class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 object-cover">
                                <div class="min-w-0 rounded-2xl rounded-tl-sm border border-gray-700 bg-gray-800 px-3.5 py-2.5 text-sm leading-7 shadow-sm break-words">
                                    ${htmlContent}
                                </div>
                            `;
                        } else if (msg.type === 'user') {
                            messageDiv.className = 'flex max-w-[88%] gap-2 sm:max-w-[75%] sm:gap-3';
                            messageDiv.innerHTML = `
                                <div class="w-8 h-8 sm:w-8 sm:h-8 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-xs sm:text-sm" style="background-color: #020461;">👤</div>
                                <div class="min-w-0 break-words rounded-2xl rounded-tr-sm bg-blue-700 px-3.5 py-2.5 text-sm leading-7 text-white shadow-sm">
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

    </div>
</body>
</html>
