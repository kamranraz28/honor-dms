@extends('layouts.master_admin')

@section('title', 'E-Warranty System :: Jobs')

@section('content')

<div class="content-wrapper jobs-page">
    <section class="content">

        <!-- Page Header -->
        <div class="jobs-header">
            <div class="jobs-header-left">
                <div class="jobs-icon">
                    <span class="jobs-icon-pulse"></span>
                    <i class="fa-solid fa-gear fa-spin"></i>
                </div>
                <div>
                    <h2>Jobs Board</h2>
                    <p>Live job updates and progress tracking</p>
                </div>
            </div>
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="jobs-header-right">
                <div class="chip chip-live">
                    <span class="dot"></span>
                    Live
                </div>
                <div class="jobs-refresh-info">
                    Auto-refreshing <span>every 1s</span>
                </div>
            </div>
        </div>

        <!-- Jobs Table Card -->
        <div class="jobs-card">
            <div class="jobs-card-header">
                <div>
                    <h3>Queued & Running Jobs</h3>
                    <p>Monitor batch processing, uploads, and completions in real time.</p>
                </div>
                <div class="jobs-legend">
                    <span class="legend-item legend-pending">
                        <span class="legend-dot"></span> Pending
                    </span>
                    <span class="legend-item legend-processing">
                        <span class="legend-dot"></span> Processing
                    </span>
                    <span class="legend-item legend-completed">
                        <span class="legend-dot"></span> Completed
                    </span>
                    <span class="legend-item legend-failed">
                        <span class="legend-dot"></span> Failed
                    </span>
                </div>
            </div>

            <!-- Jobs Table -->
            <div class="table-container">
                <table id="jobs-table" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Job ID</th>
                            <th>Job Type</th>
                            
                            <th>Created</th>
                            {{-- <th>Total</th>
                            <th>Uploaded</th> --}}
                            <th>Status</th>
                            <th>Message</th>
                            <th>Started</th>
                            <th>Progress</th>
                            <th>Finished</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="jobs-body">
                        <!-- JS will inject rows here -->
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- FontAwesome for icons -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


<script>
    const jobDetailsBaseUrl = "{{ url('/jobs') }}";
    const retailerStockDownloadUrlTemplate =
    "{{ route('admin.downloadRetailerStockReport', ['jobId' => '__JOB_ID__']) }}";




    function formatDate(dateStr) {
        if (!dateStr) return 'N/A';
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return 'N/A';
        return d.toLocaleString();
    }

    function statusClass(status) {
        switch ((status || '').toLowerCase()) {
            case 'completed': return 'status-completed';
            case 'processing': return 'status-processing';
            case 'failed': return 'status-failed';
            case 'queued':
            case 'pending':
            default: return 'status-pending';
        }
    }

    function fetchJobs() {
        $.get("{{ url('jobs/data') }}", function (jobs) {
            const tbody = $('#jobs-body');
            tbody.empty();

            jobs.forEach((job, index) => {
                const rowStatusClass = statusClass(job.status);
                const progress = job.progress ?? 0;
                const total = job.total ?? 0;
                const inserted = job.inserted ?? 0;

                const row = $(`
                    <tr class="job-row ${rowStatusClass}">
                        <td class="cell-index">
                            <span class="index-badge">${index + 1}</span>
                        </td>
                        <td>
                            <div class="cell-main">
                                <span class="job-id">JB-${job.id}</span>
                                <span class="job-subtext">#${job.id}</span>
                            </div>
                        </td>
                        <td>
                            <div class="cell-main">
                                <span class="job-type">${job.type || 'N/A'}</span>
                                <span class="job-subtext">Batch operation</span>
                            </div>
                        </td>
                        
                        <td>
                            <div class="cell-datetime">
                                <span class="job-datetime">${formatDate(job.created_at)}</span>
                            </div>
                        </td>
                        
                        
                        <td>
                            <span class="status-pill ${rowStatusClass}">
                                <span class="status-dot"></span>
                                ${job.status ? job.status.charAt(0).toUpperCase() + job.status.slice(1) : 'Pending'}
                            </span>
                        </td>
                        <td>
                            <div class="cell-datetime">
                                <span class="job-datetime">${job.message}</span>
                            </div>
                        </td>
                        <td>
                            <div class="cell-datetime">
                                <span class="job-datetime">${job.started_at ? formatDate(job.started_at) : '—'}</span>
                            </div>
                        </td>
                        <td>
                            <div class="progress-wrapper">
                                <div class="progress-bar-bg">
                                     <div class="progress-bar-fill ${rowStatusClass}" style="width: ${progress}%;"></div>
                                </div>
                                <div class="progress-meta">
                                    <span class="progress-text">${progress}%</span>
                                    <span class="progress-sub">${inserted} / ${total}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="cell-datetime">
                                <span class="job-datetime">${job.finished_at ? formatDate(job.finished_at) : '—'}</span>
                            </div>
                        </td>
                        <td>
                            ${
                                ['retailer_stock_report', 'daily_sales_report'].includes(
                                        job.type?.toLowerCase()
                                    )
                                ? (() => {
                                    const downloadUrl =
                                        retailerStockDownloadUrlTemplate.replace('__JOB_ID__', job.id);

                                    return `
                                        <a class="btn-view-advanced" href="${downloadUrl}">
                                            <span class="btn-icon">
                                                <i class="fa-solid fa-download"></i>
                                            </span>
                                            <span class="btn-label">Download</span>
                                        </a>
                                    `;
                                })()
                                : `
                                    <button class="btn-view-advanced"
                                        onclick="window.location.href='${jobDetailsBaseUrl}/${job.id}/details'">
                                        <span class="btn-icon">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </span>
                                        <span class="btn-label">Details</span>
                                    </button>
                                `
                            }
                        </td>


                    </tr>
                `);

                tbody.append(row);

                // requestAnimationFrame(() => {
                //     row.addClass('job-row-enter');
                // });
            });
        });
    }

    setInterval(fetchJobs, 1000);
    fetchJobs();
</script>

<style>
    /* PAGE & LAYOUT */
    .jobs-page {
        background: radial-gradient(circle at top left, #1e293b 0, #020617 45%, #020617 100%);
        padding: 16px;
        color: #e5e7eb;
        min-height: calc(100vh - 60px);
    }

    .jobs-page .content {
        max-width: 1300px;
        margin: 0 auto;
    }

    .jobs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .jobs-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
        flex: 1 1 auto;
        min-width: 220px;
    }

    .jobs-header-right {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        color: #9ca3af;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    /* ICON & HEADER TEXT (unchanged from previous answer) */
    .jobs-icon {
        position: relative;
        width: 46px;
        height: 46px;
        border-radius: 999px;
        background: radial-gradient(circle at 30% 30%, #38bdf8 0, #0f172a 60%);
        display: flex;
        justify-content: center;
        align-items: center;
        color: #e0f2fe;
        box-shadow: 0 12px 30px rgba(56, 189, 248, 0.35);
        overflow: hidden;
    }
    .jobs-icon i { font-size: 20px; }
    .jobs-icon-pulse {
        position: absolute;
        width: 110%;
        height: 110%;
        border-radius: inherit;
        border: 1px solid rgba(56, 189, 248, 0.7);
        animation: jobsPulse 1.8s infinite ease-out;
    }
    @keyframes jobsPulse {
        0% { transform: scale(0.9); opacity: 0.7; }
        70% { transform: scale(1.15); opacity: 0; }
        100% { transform: scale(1.15); opacity: 0; }
    }
    .jobs-header-left h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
        letter-spacing: 0.03em;
        color: #f9fafb;
    }
    .jobs-header-left p {
        margin: 2px 0 0;
        font-size: 13px;
        color: #9ca3af;
    }
    .chip { /* same as before */ 
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid rgba(148, 163, 184, 0.4);
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
    }
    .chip-live {
        border-color: rgba(248, 113, 113, 0.5);
        background: radial-gradient(circle at top left, rgba(248, 113, 113, 0.25), rgba(15,23,42,0.8));
        color: #fecaca;
    }
    .chip .dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #f97373;
        box-shadow: 0 0 0 5px rgba(248, 113, 113, 0.25);
        animation: dotPulse 1.4s infinite;
    }
    @keyframes dotPulse {
        0% { transform: scale(0.85); opacity: 0.9; }
        70% { transform: scale(1.1); opacity: 0.4; }
        100% { transform: scale(0.85); opacity: 0.9; }
    }
    .jobs-refresh-info span {
        color: #e5e7eb;
        font-weight: 500;
    }

    /* CARD */
    .jobs-card {
        border-radius: 18px;
        background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.09), rgba(15, 23, 42, 0.98));
        box-shadow:
            0 22px 40px rgba(15, 23, 42, 0.9),
            0 0 0 1px rgba(148, 163, 184, 0.15);
        padding: 18px 14px 14px;
        color: #e5e7eb;
    }

    .jobs-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .jobs-card-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #e5e7eb;
    }

    .jobs-card-header p {
        margin: 4px 0 0;
        font-size: 12px;
        color: #9ca3af;
    }

    .jobs-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: flex-end;
    }

    .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.85);
        border: 1px solid rgba(148, 163, 184, 0.35);
    }

    .legend-dot { width: 8px; height: 8px; border-radius: 999px; }
    .legend-pending .legend-dot { background: #e5e7eb; }
    .legend-processing .legend-dot { background: #facc15; }
    .legend-completed .legend-dot { background: #22c55e; }
    .legend-failed .legend-dot { background: #ef4444; }

    /* TABLE CONTAINER: RESPONSIVE FIX */
    .table-container {
        margin-top: 4px;
        border-radius: 14px;
        background: rgba(15, 23, 42, 0.95);
        border: 1px solid rgba(51, 65, 85, 0.9);
        box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.85);
        overflow-x: auto;          /* 👈 allow horizontal scroll */
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch; /* smooth on mobile */
    }

    /* TABLE */
    #jobs-table {
        width: 100%;
        min-width: 900px; /* 👈 prevents columns from squishing too much */
        border-collapse: collapse;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        font-size: 13px;
        color: #e5e7eb;
    }

    #jobs-table thead {
        background: linear-gradient(90deg, #0f172a, #020617);
    }

    #jobs-table thead th {
        padding: 10px 10px;
        text-align: center;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 11px;
        letter-spacing: 0.07em;
        color: #9ca3af;
        border-bottom: 1px solid rgba(31, 41, 55, 0.9);
        white-space: nowrap;
    }

    #jobs-table tbody td {
        padding: 9px 10px;
        text-align: center;
        border-bottom: 1px solid rgba(31, 41, 55, 0.7);
        vertical-align: middle;
        white-space: nowrap; /* helps keep single-line cells on wide screens */
    }

    #jobs-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ROW ANIMATION / STATUS / PROGRESS / BUTTONS – same as before */
    .job-row {
        transition: background 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
    }
    .job-row-enter {
        animation: rowFadeInUp 0.3s ease-out forwards;
    }
    @keyframes rowFadeInUp {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .job-row:hover {
        background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.10), rgba(15, 23, 42, 1));
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.9);
    }
    .job-row.status-pending { box-shadow: inset 2px 0 0 rgba(148, 163, 184, 0.8); }
    .job-row.status-processing { box-shadow: inset 2px 0 0 rgba(250, 204, 21, 0.9); }
    .job-row.status-completed { box-shadow: inset 2px 0 0 rgba(34, 197, 94, 0.9); }
    .job-row.status-failed {
        box-shadow: inset 2px 0 0 rgba(239, 68, 68, 0.9);
        background: radial-gradient(circle at left, rgba(239, 68, 68, 0.14), rgba(15, 23, 42, 1));
    }

    .cell-index { width: 40px; }
    .index-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.25), rgba(15, 23, 42, 1));
        border: 1px solid rgba(56, 189, 248, 0.7);
        color: #e5f3ff;
    }
    .cell-main {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 2px;
    }
    .job-id { font-size: 13px; font-weight: 600; letter-spacing: 0.04em; color: #e5e7eb; }
    .job-type, .job-order { font-weight: 500; color: #e5e7eb; }
    .job-subtext { font-size: 11px; color: #6b7280; }
    .cell-datetime .job-datetime { font-size: 12px; color: #d1d5db; white-space: nowrap; }
    .metric { display: flex; flex-direction: column; gap: 2px; align-items: center; }
    .metric-value { font-weight: 600; color: #e5e7eb; font-size: 13px; }
    .metric-label { font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.06em; }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 500;
        text-transform: capitalize;
        border: 1px solid transparent;
        background: rgba(15, 23, 42, 0.95);
    }
    .status-dot {
        width: 8px; height: 8px; border-radius: 999px; box-shadow: 0 0 0 5px transparent;
    }
    .status-pill.status-pending { border-color: rgba(148, 163, 184, 0.6); color: #e5e7eb; }
    .status-pill.status-pending .status-dot {
        background: #9ca3af;
        box-shadow: 0 0 0 4px rgba(148, 163, 184, 0.35);
    }
    .status-pill.status-processing {
        border-color: rgba(250, 204, 21, 0.7);
        background: radial-gradient(circle at top left, rgba(250, 204, 21, 0.18), rgba(15,23,42,0.95));
        color: #facc15;
    }
    .status-pill.status-processing .status-dot {
        background: #facc15;
        box-shadow: 0 0 0 5px rgba(250, 204, 21, 0.25);
        animation: statusBlink 1.4s infinite;
    }
    .status-pill.status-completed {
        border-color: rgba(34, 197, 94, 0.7);
        background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.18), rgba(15,23,42,0.95));
        color: #bbf7d0;
    }
    .status-pill.status-completed .status-dot {
        background: #22c55e;
        box-shadow: 0 0 0 5px rgba(22, 163, 74, 0.35);
    }
    .status-pill.status-failed {
        border-color: rgba(248, 113, 113, 0.8);
        background: radial-gradient(circle at top left, rgba(248, 113, 113, 0.22), rgba(15,23,42,0.95));
        color: #fecaca;
    }
    .status-pill.status-failed .status-dot {
        background: #ef4444;
        box-shadow: 0 0 0 5px rgba(248, 113, 113, 0.25);
    }
    @keyframes statusBlink {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.7; }
    }

    .progress-wrapper { min-width: 180px; }
    .progress-bar-bg {
        width: 100%;
        height: 14px;
        border-radius: 999px;
        background: radial-gradient(circle at top, #020617, #020617);
        box-shadow:
            inset 0 0 0 1px rgba(30, 64, 175, 0.7),
            0 0 18px rgba(56, 189, 248, 0.05);
        overflow: hidden;
        position: relative;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: inherit;
        transition: width 0.8s ease, box-shadow 0.3s ease, background 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .progress-bar-fill.status-pending {
        background: linear-gradient(90deg, #64748b, #94a3b8);
    }
    .progress-bar-fill.status-processing {
        background: linear-gradient(90deg, #f59e0b, #facc15, #22c55e);
        animation: progressShimmer 1.6s linear infinite;
        background-size: 200% 100%;
    }
    .progress-bar-fill.status-completed {
        background: linear-gradient(90deg, #22c55e, #4ade80);
        box-shadow: 0 0 12px rgba(34, 197, 94, 0.7);
    }
    .progress-bar-fill.status-failed {
        background: linear-gradient(90deg, #ef4444, #f97373);
    }
    @keyframes progressShimmer {
        0% { background-position: 0% 50%; }
        100% { background-position: 200% 50%; }
    }
    .progress-meta {
        display: flex;
        justify-content: space-between;
        margin-top: 4px;
        font-size: 11px;
        color: #9ca3af;
    }
    .progress-text {
        font-weight: 600;
        color: #e5e7eb;
    }
    .progress-sub {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        color: #6b7280;
    }

    .btn-view-advanced {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.65);
        background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.12), rgba(15,23,42,0.95));
        color: #e5e7eb;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, background 0.2s ease;
        outline: none;
        white-space: nowrap;
    }
    .btn-view-advanced .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.9);
    }
    .btn-view-advanced i { font-size: 11px; }
    .btn-view-advanced:hover {
        transform: translateY(-1px) translateX(1px);
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.9);
        border-color: rgba(56, 189, 248, 0.9);
        background: radial-gradient(circle at top left, rgba(56,189,248,0.2), rgba(15,23,42,1));
    }
    .btn-view-advanced:active {
        transform: translateY(0) translateX(0);
        box-shadow: 0 4px 8px rgba(15, 23, 42, 0.9);
    }

    /* RESPONSIVE TWEAKS */
    @media (max-width: 992px) {
        .jobs-page {
            padding: 12px;
        }
        .jobs-card {
            padding: 14px 10px 10px;
        }
        #jobs-table {
            min-width: 800px; /* a bit smaller on tablets */
        }
        #jobs-table thead th,
        #jobs-table tbody td {
            padding: 7px 6px;
            font-size: 12px;
        }
        .progress-wrapper {
            min-width: 150px;
        }
    }

    @media (max-width: 640px) {
        .jobs-header-left h2 {
            font-size: 18px;
        }
        .jobs-header-left p {
            font-size: 12px;
        }
        .jobs-legend {
            width: 100%;
            justify-content: flex-start;
        }
        #jobs-table {
            min-width: 700px; /* still scrollable horizontally */
        }
        .job-subtext {
            display: none; /* hide secondary texts on very small screens */
        }
        .metric-label {
            display: none;
        }
    }
</style>

@endsection
