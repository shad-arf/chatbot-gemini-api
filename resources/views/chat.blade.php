<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تاڤیار - چاتبۆتی کوردی</title>
    <link rel="icon" href="/storage/logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #252728;
            --bg-panel: #1f2937;
            --text-light: #f3f4f6;
            --text-muted: #9CA3AF;
            --border-color: #374151;
            --user-bubble: #020461;
            --user-bubble-hover: #030578;
            --error-bg: rgba(127, 29, 29, 0.3);
            --error-text: #fca5a5;
            --error-border: rgba(239, 68, 68, 0.5);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-light);
            height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
            padding: 0 1rem;
        }

        /* Header */
        header {
            background-color: var(--bg-panel);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            flex-shrink: 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left, .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status {
            font-size: 0.75rem;
            color: #4ade80;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #4ade80;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .action-btn-icon {
            background: none;
            border: none;
            color: var(--text-light);
            font-size: 1.25rem;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .action-btn-icon:hover { opacity: 0.7; }

        /* Main Chat Area */
        main {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem 1rem;
            padding-bottom: 120px; /* Space for footer */
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Error / No Bot State */
        .empty-state {
            background-color: var(--bg-panel);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            margin: auto;
        }

        .empty-state h2 { margin: 1rem 0 0.5rem; }
        .empty-state a { color: #60a5fa; text-decoration: none; }

        /* Messages */
        .message-row {
            display: flex;
            gap: 12px;
            max-width: 85%;
            animation: slideInUp 0.3s ease-out;
        }

        .ai-message {
            align-self: flex-start;
        }

        .user-message {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 14px;
        }

        .user-avatar { background-color: var(--user-bubble); }

        .chat-bubble {
            padding: 12px 16px;
            border-radius: 12px;
            line-height: 1.6;
            font-size: 0.9rem;
            word-wrap: break-word;
        }

        .ai-bubble {
            background-color: var(--bg-panel);
            border: 1px solid var(--border-color);
            border-top-right-radius: 4px;
        }

        .user-bubble {
            background-color: var(--user-bubble);
            border-top-left-radius: 4px;
        }

        .error-bubble {
            background-color: var(--error-bg);
            border: 1px solid var(--error-border);
            color: var(--error-text);
            text-align: center;
            margin: 0 auto;
        }

        /* Markdown Styles inside bubble */
        .chat-bubble p { margin-bottom: 8px; }
        .chat-bubble p:last-child { margin-bottom: 0; }
        .chat-bubble code {
            background: rgba(0, 0, 0, 0.3);
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }

        /* Footer Input Area */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: var(--bg-main);
            border-top: 1px solid var(--border-color);
            padding: 1rem 0;
            z-index: 10;
        }

        .input-group {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex: 1;
            background-color: var(--bg-panel);
            border: 1px solid var(--border-color);
            color: white;
            border-radius: 8px;
            padding: 12px 16px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus {
            border-color: var(--user-bubble);
        }

        .send-btn {
            background-color: var(--user-bubble);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0 16px;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .send-btn:hover { background-color: var(--user-bubble-hover); }
        .send-btn:disabled { opacity: 0.5; cursor: not-allowed; }

        .copyright {
            text-align: center;
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 8px;
        }

        /* Animations */
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Typing Indicator */
        .typing-dots { display: flex; gap: 4px; padding: 5px; }
        .dot { width: 6px; height: 6px; background: var(--text-muted); border-radius: 50%; animation: bounce 1.4s infinite; }
        .dot:nth-child(2) { animation-delay: 0.2s; }
        .dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes bounce { 0%, 80%, 100% { transform: translateY(0); } 40% { transform: translateY(-6px); } }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 10px; }
    </style>
</head>
<body>

    <header>
        <div class="container header-content">
            <div class="header-left">
                <img src="/storage/logo.png" alt="Logo" class="logo-img">
                <span class="status">
                    <span class="status-dot"></span> ئۆنلاین
                </span>
            </div>
            <div class="header-right">
                <button onclick="location.reload()" class="action-btn-icon" title="نوێکردنەوە">↻</button>
                <button onclick="clearChat()" class="action-btn-icon" title="سڕینەوە">🗑️</button>
            </div>
        </div>
    </header>

    @if (!$bot)
        <main>
            <div class="empty-state container">
                <div style="font-size: 3rem;">🤖</div>
                <h2>{{ $error ?? 'چاتبۆت بەردەست نیە' }}</h2>
                <p style="color: var(--text-muted);">تکایە بۆ <a href="/admin">بەڕێوەبەری سیستەم</a> بڕۆ و چاتبۆتێک دروست بکە.</p>
            </div>
        </main>
    @else
        <main class="container" id="messagesContainer">
            <div class="message-row ai-message">
                <img src="/storage/logo.png" alt="Bot" class="avatar">
                <div class="chat-bubble ai-bubble">
                    <p>بەخێربێیت! چۆن دەتوانم ئەمڕۆ هاوکاریت بکەم؟</p>
                </div>
            </div>
        </main>

        <footer>
            <div class="container">
                <div class="input-group">
                    <input type="text" id="userInput" placeholder="پەیامەکەت لێرە بنووسە..." onkeypress="if(event.key === 'Enter') sendMessage()">
                    <button onclick="sendMessage()" id="sendBtn" class="send-btn" title="بنێرە">
                        <svg style="width: 20px; height: 20px; transform: rotate(-90deg);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                        </svg>
                    </button>
                </div>
                <p class="copyright">هێزگیراوە لەلایەن تاڤیار AI - ٢٠٢٦</p>
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
                        <div class="message-row ai-message">
                            <img src="/storage/logo.png" alt="Bot" class="avatar">
                            <div class="chat-bubble ai-bubble">
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
                    messageDiv.className = 'message-row ai-message';
                    messageDiv.innerHTML = `
                        <img src="/storage/logo.png" alt="Bot" class="avatar">
                        <div class="chat-bubble ai-bubble">
                            ${htmlContent}
                        </div>
                    `;
                    if (save) {
                        const messages = loadMessages();
                        messages.push({ type: 'ai', text: text });
                        saveMessages(messages);
                    }
                } else if (type === 'user') {
                    messageDiv.className = 'message-row user-message';
                    messageDiv.innerHTML = `
                        <div class="avatar user-avatar">👤</div>
                        <div class="chat-bubble user-bubble">
                            <p>${escapeHtml(text)}</p>
                        </div>
                    `;
                    if (save) {
                        const messages = loadMessages();
                        messages.push({ type: 'user', text: text });
                        saveMessages(messages);
                    }
                } else if (type === 'error') {
                    messageDiv.className = 'message-row ai-message';
                    messageDiv.innerHTML = `
                        <div class="chat-bubble error-bubble">
                            <p>${escapeHtml(text)}</p>
                        </div>
                    `;
                } else if (type === 'typing') {
                    messageDiv.className = 'message-row ai-message';
                    messageDiv.id = 'typingIndicator';
                    messageDiv.innerHTML = `
                        <img src="/storage/logo.png" alt="Bot" class="avatar">
                        <div class="typing-dots">
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
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
                            messageDiv.className = 'message-row ai-message';
                            messageDiv.innerHTML = `
                                <img src="/storage/logo.png" alt="Bot" class="avatar">
                                <div class="chat-bubble ai-bubble">
                                    ${htmlContent}
                                </div>
                            `;
                        } else if (msg.type === 'user') {
                            messageDiv.className = 'message-row user-message';
                            messageDiv.innerHTML = `
                                <div class="avatar user-avatar">👤</div>
                                <div class="chat-bubble user-bubble">
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
