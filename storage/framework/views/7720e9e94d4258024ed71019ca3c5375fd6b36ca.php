<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AI Chat</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <style>
        :root {
            --bg: #020617;
            --panel: #0b1220;
            --card: #111827;
            --border: #1f2937;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --accent: #10a37f;
            --danger: #ef4444;
        }

        .light {
            --bg: #f8fafc;
            --panel: #ffffff;
            --card: #f1f5f9;
            --border: #e5e7eb;
            --text: #020617;
            --muted: #475569;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            height: 100vh;
            background: var(--bg);
            font-family: Inter, system-ui, sans-serif;
            color: var(--text);
        }

        .app {
            display: flex;
            height: 100vh;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 280px;
            background: var(--panel);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            padding: 18px 20px;
            margin: 0;
            font-size: 15px;
            border-bottom: 1px solid var(--border);
        }

        .history {
            flex: 1;
            overflow-y: auto;
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .history-item {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .history-item small {
            color: var(--muted);
            line-height: 1.3;
        }

        .history-item button {
            background: var(--danger);
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            cursor: pointer;
        }

        /* ================= CHAT ================= */
        .chat-app {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            background: var(--panel);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            font-weight: 600;
        }

        /* ================= THEME SWITCH ================= */
        .theme-switch {
            display: flex;
            gap: 8px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 4px;
            cursor: pointer;
        }

        .theme-switch span {
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 999px;
            color: var(--muted);
        }

        .theme-switch .active {
            background: var(--accent);
            color: #fff;
        }

        .chat-window {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .message {
            max-width: 70%;
            padding: 14px 18px;
            border-radius: 18px;
            font-size: 15px;
        }

        .user {
            align-self: flex-end;
            background: linear-gradient(135deg, var(--accent), #22c55e);
            color: #fff;
        }

        .ai {
            align-self: flex-start;
            background: var(--panel);
            border: 1px solid var(--border);
        }

        .chat-input {
            padding: 16px;
            display: flex;
            gap: 12px;
            border-top: 1px solid var(--border);
            background: var(--panel);
        }

        textarea {
            flex: 1;
            resize: none;
            min-height: 48px;
            padding: 14px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--text);
        }

        button.send-btn {
            padding: 12px 26px;
            border: none;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent), #22c55e);
            color: #fff;
            cursor: pointer;
        }

        /* ================= MODAL ================= */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            display: none;
            align-items: center;
            justify-content: center;
        }

        .modal {
            background: var(--panel);
            border-radius: 18px;
            width: 100%;
            max-width: 400px;
            border: 1px solid var(--border);
        }

        .modal-header,
        .modal-actions {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .modal-actions {
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .modal-body {
            padding: 20px;
            color: var(--muted);
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            padding: 8px 14px;
            border-radius: 999px;
            cursor: pointer;
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 999px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="app">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h2>Today History</h2>
            <div class="history" id="history"></div>
        </aside>

        <!-- CHAT -->
        <div class="chat-app">
            <header>
                AI Chat
                <div class="theme-switch" onclick="toggleTheme()">
                    <span id="lightLabel">Light</span>
                    <span id="darkLabel">Dark</span>
                </div>
            </header>

            <div class="chat-window" id="chat"></div>

            <div class="chat-input">
                <textarea id="prompt" placeholder="Type a message..."></textarea>
                <button class="send-btn" onclick="send()">Send</button>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal-backdrop" id="modal">
        <div class="modal">
            <div class="modal-header">Confirm Undo</div>
            <div class="modal-body" id="modalMessage"></div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button class="btn-danger" id="modalConfirm">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        /* ================= THEME ================= */
        const lightLabel = document.getElementById('lightLabel');
        const darkLabel = document.getElementById('darkLabel');

        function applyTheme(theme) {
            document.body.classList.toggle('light', theme === 'light');
            lightLabel.classList.toggle('active', theme === 'light');
            darkLabel.classList.toggle('active', theme === 'dark');
            localStorage.setItem('theme', theme);
        }

        function toggleTheme() {
            applyTheme(document.body.classList.contains('light') ? 'dark' : 'light');
        }
        applyTheme(localStorage.getItem('theme') || 'dark');

        /* ================= CHAT ================= */
        const chat = document.getElementById('chat');
        const promptInput = document.getElementById('prompt');
        const historyBox = document.getElementById('history');

        function addMessage(text, type) {
            const div = document.createElement('div');
            div.className = `message ${type}`;
            div.innerText = text;
            chat.appendChild(div);
            chat.scrollTop = chat.scrollHeight;
        }

        /* ================= HISTORY + UNDO ================= */
        let undoId = null;
        let undoSerial = null;

        function loadHistory() {
            fetch("<?php echo e(url('/admin-panel/ai/history/today')); ?>")
                .then(r => r.json())
                .then(logs => {
                    historyBox.innerHTML = '';
                    logs.forEach((l, i) => {
                        const div = document.createElement('div');
                        div.className = 'history-item';
                        div.innerHTML = `
                <small>#${i + 1} — ${l.action} ${l.resource_id ?? ''}</small>
                <button onclick="confirmUndo(${l.id}, ${i + 1})">Undo</button>
            `;
                        historyBox.appendChild(div);
                    });
                });
        }

        function confirmUndo(id, serial) {
            undoId = id;
            undoSerial = serial;
            addMessage(`Please undo the history number ${serial}`, 'user');
            document.getElementById('modalMessage').innerText =
                `Are you sure you want to undo history #${serial}?`;
            document.getElementById('modalConfirm').onclick = executeUndo;
            document.getElementById('modal').style.display = 'flex';
        }

        function executeUndo() {
            fetch(`<?php echo e(url('/admin-panel/ai/undo')); ?>/${undoId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            })
                .then(() => {
                    addMessage(`Undone request successful for history ${undoSerial}`, 'ai');
                    closeModal();
                    loadHistory();
                });
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
            undoId = null;
            undoSerial = null;
        }

        /* ================= SEND CHAT ================= */
        function send() {
            const text = promptInput.value.trim();
            if (!text) return;
            addMessage(text, 'user');
            promptInput.value = '';

            fetch("<?php echo e(url('/admin-panel/ai/execute')); ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ prompt: text })
            })
                .then(res => res.json())
                .then(data => {
                    addMessage(data.message, 'ai');
                    loadHistory(); // 👈 refresh history immediately
                });

        }

        promptInput.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                send();
            }
        });

        document.addEventListener('DOMContentLoaded', loadHistory);
    </script>

</body>

</html>
