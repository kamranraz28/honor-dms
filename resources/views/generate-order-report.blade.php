<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mira - Report Generator</title>
    <script>
        // Apply dark mode immediately if saved in localStorage
        if (localStorage.getItem('miraDarkMode') === 'true') {
            document.documentElement.classList.add('dark-mode'); // Add the dark mode class to <html>
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background-color: #f7fafc;
        }

        /* Loader Style */
        #loadingOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 18px;
            z-index: 9999;
        }

        #loadingOverlay .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Sidebar Styles */
        .sidebar {
            width: 20%;
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            padding: 16px;
        }

        .sidebar .title {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 16px;
        }

        .sidebar .chatbox {
            flex: 1;
            overflow-y: auto;
        }

        .sidebar .prompt-box {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            cursor: pointer;
            font-size: 14px;
            color: #4a5568;
            transition: background-color 0.2s;
        }

        .sidebar .prompt-box:hover {
            background-color: #f1f1f1;
        }

        .sidebar .footer {
            font-size: 12px;
            color: #a0aec0;
            text-align: center;
            margin-top: 16px;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            background-color: #ffffff;
            padding: 24px;
            overflow-y: auto;
        }

        .mira-box {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 24px;
        }

        .mira-box h2 {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .mira-box p {
            font-size: 14px;
            color: #718096;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            resize: none;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #3182ce;
        }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-success {
            background-color: #48bb78;
            color: #ffffff;
        }

        .btn-success:hover {
            background-color: #38a169;
        }

        .btn-secondary {
            background-color: #718096;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background-color: #4a5568;
        }

        /* Table Styles */
        .table-responsive {
            margin-top: 24px;
            overflow-x: auto;
        }

        #report-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        #report-table thead {
            background-color: #2d3748;
            color: #ffffff;
        }

        #report-table thead th {
            padding: 12px;
            font-size: 14px;
            font-weight: bold;
            text-align: left;
        }

        #report-table tbody tr {
            transition: background-color 0.2s;
        }

        #report-table tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }

        #report-table tbody tr:hover {
            background-color: #edf2f7;
        }

        #report-table tbody td {
            padding: 12px;
            font-size: 14px;
            color: #4a5568;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Dark Mode Styles */
        .dark-mode {
            background-color: #1a202c;
            color: #f7fafc;
        }

        .dark-mode .sidebar {
            background-color: #2d3748;
            color: #f7fafc;
        }

        .dark-mode .prompt-box {
            background-color: #2d3748;
            color: #f7fafc;
        }

        .dark-mode .prompt-box:hover {
            background-color: #4a5568;
        }

        .dark-mode .main-content {
            background-color: #2d3748;
            color: #f7fafc;
        }

        .dark-mode .mira-box {
            background-color: #2d3748;
            color: #f7fafc;
        }

        .dark-mode textarea {
            background-color: #2d3748;
            color: #f7fafc;
            border: 1px solid #4a5568;
        }

        /* Dark Mode Styles for DataTable */
        .dark-mode #report-table {
            background-color: #2d3748;
            /* Dark background for the table */
            color: #f7fafc;
            /* Light text for the table */
        }

        .dark-mode #report-table thead {
            background-color: #4a5568;
            /* Darker background for the table header */
            color: #f7fafc;
            /* Light text for the table header */
        }

        .dark-mode #report-table tbody tr {
            background-color: #2d3748;
            /* Dark background for table rows */
            color: #f7fafc;
            /* Light text for table rows */
        }

        .dark-mode #report-table tbody tr:nth-child(even) {
            background-color: #3b4252;
            /* Slightly lighter background for even rows */
            color: #f7fafc;
            /* Ensure text is white for even rows */
        }

        .dark-mode #report-table tbody tr:hover {
            background-color: #4a5568;
            /* Highlighted background on hover */
            color: #f7fafc;
            /* Ensure text remains white on hover */
        }

        .dark-mode #report-table tbody td {
            border-color: #4a5568;
            /* Border color for table cells */
            color: #f7fafc;
            /* Ensure text is white for all cells */
        }

        /* Dark Mode Styles for DataTable Buttons */
        .dark-mode .dt-buttons button {
            background-color: #4a5568;
            /* Dark background for buttons */
            color: #f7fafc;
            /* White text for buttons */
            border: none;
            border-radius: 4px;
            padding: 6px 12px;
            margin: 2px;
            transition: background-color 0.2s;
        }

        .dark-mode .dt-buttons button:hover {
            background-color: #2d3748;
            /* Slightly darker background on hover */
            color: #f7fafc;
            /* Ensure text remains white */
        }

        .dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #4a5568;
            /* Dark background for pagination buttons */
            color: #f7fafc;
            /* White text for pagination buttons */
            border: none;
            border-radius: 4px;
            padding: 6px 12px;
            margin: 2px;
            transition: background-color 0.2s;
        }

        .dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #2d3748;
            /* Slightly darker background on hover */
            color: #f7fafc;
            /* Ensure text remains white */
        }

        .dark-mode .dataTables_wrapper .dataTables_info,
        .dark-mode .dataTables_wrapper .dataTables_length label,
        .dark-mode .dataTables_wrapper .dataTables_filter label {
            color: #f7fafc;
            /* White text for table info, length, and filter labels */
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="title">🕘 Prompt History</div>
        <div class="chatbox">
            
@if (isset($prompts) && is_array($prompts))
    @foreach($prompts as $prompt)
        <div class="prompt-box" onclick="setPrompt('{{ addslashes($prompt->prompt) }}')">
            {{ \Illuminate\Support\Str::limit($prompt->prompt, 25, '...') }}
        </div>
    @endforeach
@endif
        </div>
        <div class="footer">Mira v1.0 - AI-powered Report Assistant</div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="mira-box">
            <button id="darkModeToggle" class="btn btn-secondary" style="position: fixed; top: 20px; right: 20px;">
                🌓 Switch Dark Mode
            </button>
            <h2>Hello, I'm <strong>Mira</strong> 👋</h2>
            <p>Your AI-powered report assistant. Just give me a prompt, and I'll craft a report for you.</p>

            <form action="{{ route('admin.miraPost') }}" method="POST" id="reportForm">
                @csrf
                <div class="form-group">
                    <label for="prompt">Enter your report prompt:</label>
                    <textarea id="prompt" name="prompt" rows="4" placeholder="Enter your prompt here..."
                        required>{{ old('prompt') }}</textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-success">🚀 Generate Report</button>
                    <button type="button" class="btn btn-secondary" id="voiceBtn">🎤 Use Voice</button>
                </div>
            </form>

            @php
                $allData = $report ?? $users ?? $stocks ?? $sales ?? $purchases ?? $combined ?? $smsdetails ?? $prediction ?? null;
            @endphp

            @if ($allData && (is_array($allData) || $allData instanceof Countable) && count($allData) > 0)
                @if(request()->has('prompt') || session()->has('prompt'))
                    <div class="prompt-box mb-3">
                        <strong>Prompt:</strong> {{ request('prompt') ?? session('prompt') }}
                    </div>
                @endif

                <h4 class="mb-3">📊 Generated Report:</h4>
                <div class="table-responsive mb-6">
                    <table class="min-w-full table-auto border-collapse bg-white shadow-lg rounded-lg overflow-hidden"
                        id="report-table">
                        @php
                            $rowArray = is_object($allData[0]) && method_exists($allData[0], 'toArray')
                                ? $allData[0]->toArray()
                                : (array) $allData[0];

                            $ignored = ['pivot', 'updated_at', 'deleted_at'];
                            $headers = array_diff(array_keys($rowArray), $ignored);
                        @endphp

                        <thead class="bg-gray-800 text-white">

                            <tr>
                                @foreach($headers as $key)
                                    <th class="py-3 px-4 text-left text-sm font-semibold tracking-wider">
                                        {{ ucwords(str_replace('_', ' ', $key)) }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach($allData as $row)
                                @php
                                    $rowData = is_object($row) && method_exists($row, 'toArray')
                                        ? $row->toArray()
                                        : (array) $row;
                                @endphp
                                <tr class="hover:bg-gray-100">
                                    @foreach($headers as $key)
                                                    <td class="py-3 px-4 text-sm border-t">{{
                                        isset($rowData[$key]) ?
                                        (is_string($rowData[$key]) && \Carbon\Carbon::hasFormat($rowData[$key], 'Y-m-d H:i:s') ? \Carbon\Carbon::parse($rowData[$key])->format('Y-m-d H:i:s') : $rowData[$key])
                                        : ''
                                                                                                                                                                                                                                }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @elseif(request()->isMethod('post'))
                <div class="alert alert-warning mt-4">No data found for your prompt. Please try a different query.</div>
            @endif
        </div>
    </div>

    <div id="loadingOverlay">
        <div class="loader"></div>
        <span>Wait Boss, Mira is generating report for you!</span>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

    <script>
        function showLoader() {
            $('#loadingOverlay').fadeIn(300).css('display', 'flex');
        }

        function hideLoader() {
            $('#loadingOverlay').fadeOut(300);
        }

        $(document).ready(function () {
            // Initialize DataTable
            $('#report-table').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                pageLength: 25,
                responsive: true
            });

            const voiceBtn = document.getElementById('voiceBtn');
            const promptInput = document.getElementById('prompt');

            if ('webkitSpeechRecognition' in window) {
                const recognition = new webkitSpeechRecognition();
                recognition.continuous = false;
                recognition.interimResults = false;
                recognition.lang = 'en-US';

                voiceBtn.addEventListener('click', function () {
                    recognition.start();
                    console.log('Voice recognition started...');
                });

                recognition.onresult = function (event) {
                    const transcript = event.results[0][0].transcript;
                    console.log('Voice recognized:', transcript);
                    promptInput.value = transcript;
                };

                recognition.onerror = function (event) {
                    console.error('Voice recognition error:', event.error);
                    alert('Voice recognition failed. Error: ' + event.error);
                };
            } else {
                voiceBtn.style.display = 'none';
                alert('Voice recognition not supported in your browser.');
            }

            // Show loading overlay on form submit
            $('#reportForm').on('submit', function (e) {
                showLoader();
            });

        });
    </script>

<script>
    // Dark Mode Toggle Script
    document.getElementById('darkModeToggle').addEventListener('click', function () {
        // Toggle dark mode class on the <html> element
        document.documentElement.classList.toggle('dark-mode');

        // Store the current dark mode state in localStorage
        localStorage.setItem('miraDarkMode', document.documentElement.classList.contains('dark-mode'));
    });

    // On load, check localStorage and apply dark mode if saved
    window.addEventListener('DOMContentLoaded', () => {
        // Check if the dark mode state is saved in localStorage
        if (localStorage.getItem('miraDarkMode') === 'true') {
            document.documentElement.classList.add('dark-mode'); // Add the dark mode class
        }
    });
</script>

    <script>
        function setPrompt(prompt) {
            document.getElementById('prompt').value = prompt;
        }
    </script>
</body>

</html>
