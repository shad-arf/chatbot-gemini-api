<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tavyar AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-glow: #4f46e5;
            --bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --border: rgba(255, 255, 255, 0.1);
            --txt-white: #f8fafc;
            --txt-muted: #94a3b8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, #1e1b4b, var(--bg));
            color: var(--txt-white);
            min-height: 100vh;
            padding: 2rem;
            overflow-x: hidden;
        }

        .navbar {
            max-width: 1200px;
            margin: 0 auto 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        h1 { font-size: 1.5rem; font-weight: 700; background: linear-gradient(90deg, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .nav-links a {
            color: var(--txt-muted);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .nav-links a:hover {
            color: var(--txt-white);
            background: rgba(99, 102, 241, 0.1);
        }

        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 2rem;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        h2 { font-size: 1.5rem; margin-bottom: 1.5rem; font-weight: 700; background: linear-gradient(90deg, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .input-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: 0.875rem; color: var(--txt-muted); margin-bottom: 0.5rem; font-weight: 500; }
        input[type="text"], textarea {
            width: 100%;
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid var(--border);
            padding: 0.75rem 1rem;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
        }
        input[type="text"]:focus, textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }

        .file-upload-area {
            border: 2px dashed rgba(99, 102, 241, 0.35);
            border-radius: 12px;
            padding: 1.25rem 1rem;
            cursor: pointer;
            transition: all 0.3s;
            background: rgba(99, 102, 241, 0.04);
            text-align: center;
        }
        .file-upload-area:hover { border-color: var(--primary); background: rgba(99, 102, 241, 0.1); }
        .file-upload-area input[type="file"] { display: none; }
        .file-upload-label { font-size: 0.85rem; color: var(--txt-muted); cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 0.4rem; }
        .file-upload-label .upload-icon { font-size: 1.75rem; line-height: 1; }
        .file-upload-label .upload-hint { font-size: 0.72rem; color: rgba(148, 163, 184, 0.6); }
        .file-list { margin-top: 0.5rem; display: flex; flex-direction: column; gap: 0.25rem; }
        .file-tag {
            font-size: 0.75rem;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }
        .file-tag button {
            background: none;
            border: none;
            color: #f87171;
            cursor: pointer;
            padding: 0;
            margin: 0;
            font-size: 0.75rem;
        }
        .file-tag button:hover { color: #ef4444; }

        button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 0.5rem;
            width: 100%;
        }
        button:hover { background: var(--primary-glow); transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3); }
        button:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        .bots-list { display: flex; flex-direction: column; gap: 1rem; }

        .bot-card {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s;
        }

        .bot-card.default {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }

        .bot-card:hover { border-color: var(--primary); }

        .bot-name { font-weight: 600; margin-bottom: 0.5rem; }
        .bot-instruction { font-size: 0.85rem; color: var(--txt-muted); margin-bottom: 0.75rem; white-space: pre-wrap; word-break: break-word; max-height: 60px; overflow-y: auto; }

        .bot-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .bot-actions button {
            flex: 1;
            padding: 0.5rem;
            font-size: 0.8rem;
            margin: 0;
            min-width: 80px;
        }

        .btn-secondary {
            background: rgba(99, 102, 241, 0.15);
            color: var(--txt-white);
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(99, 102, 241, 0.25);
            transform: none;
            box-shadow: none;
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.25);
            transform: none;
            box-shadow: none;
        }

        .btn-success {
            background: rgba(34, 197, 94, 0.15);
            color: #86efac;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .btn-success:hover {
            background: rgba(34, 197, 94, 0.25);
            transform: none;
            box-shadow: none;
        }

        .default-badge {
            display: inline-block;
            background: rgba(34, 197, 94, 0.2);
            color: #86efac;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        @media (max-width: 900px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <img src="/storage/logo.png" alt="Logo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
            <h1>Bot Factory</h1>
        </div>
        <div class="nav-links">
            <a href="/">← Back to Chat</a>
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="padding: 0.5rem 1rem; width: auto; margin: 0;">Logout</button>
            </form>
        </div>
    </div>

    <div class="dashboard">
        <!-- Create/Edit Bot Section -->
        <div class="section">
            <h2>Create Bot</h2>

            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.bots.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="input-group">
                    <label>Bot Name</label>
                    <input type="text" name="name" placeholder="e.g. Kurdish Translator" required>
                </div>

                <div class="input-group">
                    <label>System Instruction</label>
                    <textarea name="instruction" rows="4" placeholder="How should the AI behave?"></textarea>
                </div>

                <div class="input-group">
                    <label>Knowledge Files (PDF)</label>
                    <div class="file-upload-area" onclick="document.getElementById('instructionFiles').click()">
                        <input type="file" id="instructionFiles" name="instruction_files[]" multiple accept=".pdf">
                        <div class="file-upload-label">
                            <span class="upload-icon">📂</span>
                            <span>Click to upload PDF files</span>
                            <span class="upload-hint">Multiple PDFs allowed • Max 20MB each</span>
                        </div>
                    </div>
                    <div class="file-list" id="instructionFileList"></div>
                </div>

                <button type="submit" id="createBotBtn">Create Bot</button>
            </form>
        </div>

        <!-- Bots List Section -->
        <div class="section">
            <h2>Your Bots</h2>

            @if ($bots->count() > 0)
                <div class="bots-list">
                    @foreach ($bots as $bot)
                        <div class="bot-card {{ $bot->is_default ? 'default' : '' }}">
                            <div class="bot-name">
                                {{ $bot->name }}
                                @if ($bot->is_default)
                                    <span class="default-badge">✓ Default</span>
                                @endif
                            </div>

                            @if ($bot->instruction)
                                <div class="bot-instruction">{{ Str::limit($bot->instruction, 100) }}</div>
                            @else
                                <div class="bot-instruction" style="color: #94a3b8; font-style: italic;">No instruction text</div>
                            @endif

                            @if ($bot->instruction_files && count($bot->instruction_files) > 0)
                                <div style="font-size: 0.8rem; color: var(--txt-muted); margin-bottom: 0.75rem;">
                                    📎 {{ count($bot->instruction_files) }} file(s)
                                </div>
                            @endif

                            <div class="bot-actions">
                                <a href="{{ route('admin.bots.edit', $bot) }}" style="flex: 1; display: flex;">
                                    <button type="button" class="btn-secondary" style="width: 100%; margin: 0;">Edit</button>
                                </a>

                                @if (!$bot->is_default)
                                    <form method="POST" action="{{ route('admin.bots.setDefault', $bot) }}" style="flex: 1;">
                                        @csrf
                                        <button type="submit" class="btn-success">Set Default</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.bots.destroy', $bot) }}" style="flex: 1;" onsubmit="return confirm('Delete this bot?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--txt-muted); text-align: center; padding: 2rem 0;">No bots yet. Create one above to get started.</p>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('instructionFiles').addEventListener('change', function() {
            const list = document.getElementById('instructionFileList');
            list.innerHTML = '';
            Array.from(this.files).forEach((file, i) => {
                const tag = document.createElement('div');
                tag.className = 'file-tag';
                tag.innerHTML = `<span>📄 ${file.name}</span><button type="button" onclick="removeFile('instructionFiles', 'instructionFileList', ${i})">✕</button>`;
                list.appendChild(tag);
            });
        });

        function removeFile(inputId, listId, index) {
            const input = document.getElementById(inputId);
            const dt = new DataTransfer();
            Array.from(input.files).forEach((f, i) => { if (i !== index) dt.items.add(f); });
            input.files = dt.files;

            const list = document.getElementById(listId);
            list.innerHTML = '';
            Array.from(input.files).forEach((file, i) => {
                const tag = document.createElement('div');
                tag.className = 'file-tag';
                tag.innerHTML = `<span>📄 ${file.name}</span><button type="button" onclick="removeFile('${inputId}', '${listId}', ${i})">✕</button>`;
                list.appendChild(tag);
            });
        }
    </script>
</body>
</html>
