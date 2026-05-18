@extends('layouts.master_admin')

@section('title', 'E-Warranty System :: Job Details')

@section('content')

@php
    $status = strtolower($job->status ?? 'pending');

    if ($status === 'completed') {
        $statusClass = 'status-completed';
    } elseif ($status === 'processing') {
        $statusClass = 'status-processing';
    } elseif ($status === 'failed') {
        $statusClass = 'status-failed';
    } else {
        $statusClass = 'status-pending';
    }
@endphp

<div class="content-wrapper job-details-page">
    <section class="content">

        <!-- Header -->
        <div class="jd-header">
            <div class="jd-header-left">
                <div class="jd-icon">
                    <span class="jd-icon-pulse"></span>
                    <i class="fa-solid fa-file-waveform"></i>
                </div>
                <div>
                    <h1>Job Details</h1>
                    <p>Inspect the lifecycle, progress, and anomalies of this background job.</p>
                </div>
            </div>

            <div class="jd-header-right">
                <div class="chip chip-id">
                    <span class="chip-label">Job ID</span>
                    <span class="chip-value">#{{ $job->id }}</span>
                </div>
                <div class="chip chip-type">
                    <span class="chip-label">Type</span>
                    <span class="chip-value">{{ $job->type ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="jd-card jd-summary">
            <div class="jd-card-header">
                <div>
                    <h2>Summary</h2>
                    <p>High-level overview of the job execution and record processing.</p>
                </div>
                <span class="status-pill {{ $statusClass }}">
                    <span class="status-dot"></span>
                    {{ ucfirst($job->status ?? 'pending') }}
                </span>
            </div>

            <div class="jd-summary-grid">
                <div class="jd-summary-item">
                    <span class="label">Progress</span>
                    <div class="progress-wrapper">
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill {{ $statusClass }}" style="width: {{ $job->progress ?? 0 }}%;"></div>
                        </div>
                        <div class="progress-meta">
                            <span class="progress-text">{{ $job->progress ?? 0 }}%</span>
                            <span class="progress-sub">
                                {{ $job->inserted ?? 0 }} / {{ $job->total ?? 0 }} records
                            </span>
                        </div>
                    </div>
                </div>

                <div class="jd-summary-item">
                    <span class="label">Inserted</span>
                    <span class="value">{{ $job->inserted ?? 0 }}</span>
                </div>

                <div class="jd-summary-item">
                    <span class="label">Duplicates</span>
                    <span class="value">{{ $job->duplicates ?? 0 }}</span>
                </div>

                <div class="jd-summary-item">
                    <span class="label">Total Records</span>
                    <span class="value">{{ $job->total ?? 0 }}</span>
                </div>

                <div class="jd-summary-item">
                    <span class="label">Started At</span>
                    <span class="value value-muted">{{ $job->started_at ?? '—' }}</span>
                </div>

                <div class="jd-summary-item">
                    <span class="label">Finished At</span>
                    <span class="value value-muted">{{ $job->finished_at ?? '—' }}</span>
                </div>

                <div class="jd-summary-item jd-summary-wide">
                    <span class="label">Message</span>
                    <div class="message-pill">
                        {{ $job->message ?? 'No message provided.' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Bulk upload / warehouse logs ===== --}}
        @if ($job->type == "primary_bulk_upload" || $job->type == "warehouse_upload")
            <div class="jd-card">
                <div class="jd-card-header">
                    <div>
                        <h2>Invalid Distributor IDs</h2>
                        <p>Distributor IDs in the file that do not exist in the system.</p>
                    </div>
                    <span class="badge badge-warn">Distributor Check</span>
                </div>

                @if(empty($noDealerList))
                    <p class="jd-empty">No issue found. All distributors are valid.</p>
                @else
                    <ul class="jd-list">
                        @foreach($noDealerList as $log)
                            <li>{{ $log }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="jd-card">
                <div class="jd-card-header">
                    <div>
                        <h2>Missing IMEIs in Stock</h2>
                        <p>IMEIs referenced in upload but not available in stock records.</p>
                    </div>
                    <span class="badge badge-error">Stock Error</span>
                </div>

                @if(empty($noStockList))
                    <p class="jd-empty">No issue found. All IMEIs exist in stock.</p>
                @else
                    <ul class="jd-list">
                        @foreach($noStockList as $log)
                            <li>{{ $log }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="jd-card">
                <div class="jd-card-header">
                    <div>
                        <h2>IMEIs Already in Secondary Sales</h2>
                        <p>These IMEIs are already recorded in your secondary sales data.</p>
                    </div>
                    <span class="badge badge-info">Secondary</span>
                </div>

                @if(empty($soldList))
                    <p class="jd-empty">No issue found. No duplicates in secondary sales.</p>
                @else
                    <ul class="jd-list">
                        @foreach($soldList as $log)
                            <li>{{ $log }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="jd-card">
                <div class="jd-card-header">
                    <div>
                        <h2>IMEIs Already in Tertiary Sales</h2>
                        <p>These IMEIs are already recorded in tertiary/retail level sales.</p>
                    </div>
                    <span class="badge badge-info badge-soft">Tertiary</span>
                </div>

                @if(empty($tertiarySoldList))
                    <p class="jd-empty">No issue found. No duplicates in tertiary sales.</p>
                @else
                    <ul class="jd-list">
                        @foreach($tertiarySoldList as $log)
                            <li>{{ $log }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        {{-- ===== Stock bulk upload logs ===== --}}
        @if ($job->type == "stock_bulk_upload")
            <div class="jd-card">
                <div class="jd-card-header">
                    <div>
                        <h2>Product Model Mismatches</h2>
                        <p>File models that do not match any configured product model.</p>
                    </div>
                    <span class="badge badge-error">Model Mismatch</span>
                </div>

                @if(empty($modelErrors))
                    <p class="jd-empty">No issue found. All models are valid.</p>
                @else
                    <ul class="jd-list">
                        @foreach($modelErrors as $log)
                            <li>{{ $log }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        {{-- ===== Duplicated IMEIs (generic) ===== --}}
        <div class="jd-card">
            <div class="jd-card-header">
                <div>
                    <h2>Duplicated IMEIs</h2>
                    <p>These IMEIs appear more than once within the uploaded dataset.</p>
                </div>
                <span class="badge badge-warn">Duplicate</span>
            </div>

            @if(empty($logDetails))
                <p class="jd-empty">No issue found. No duplicated IMEIs detected.</p>
            @else
                <ul class="jd-list">
                    @foreach($logDetails as $log)
                        @php
                            $imei = isset($log['sno']) ? $log['sno'] : '';
                        @endphp
                        <li>{{ $imei }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

    </section>
</div>

<!-- FontAwesome (if your master layout doesn’t already load it) -->
<script src="https://kit.fontawesome.com/a2b1c6d1c3.js" crossorigin="anonymous"></script>

<style>
    .job-details-page {
        background: radial-gradient(circle at top left, #1e293b 0, #020617 45%, #020617 100%);
        padding: 16px;
        color: #e5e7eb;
        min-height: calc(100vh - 60px);
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .job-details-page .content {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Header */
    .jd-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .jd-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
        flex: 1 1 auto;
        min-width: 240px;
    }

    .jd-header-left h1 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
        letter-spacing: 0.03em;
        color: #f9fafb;
    }

    .jd-header-left p {
        margin: 2px 0 0;
        font-size: 13px;
        color: #9ca3af;
    }

    .jd-header-right {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: flex-end;
    }

    .jd-icon {
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

    .jd-icon i {
        font-size: 20px;
    }

    .jd-icon-pulse {
        position: absolute;
        width: 110%;
        height: 110%;
        border-radius: inherit;
        border: 1px solid rgba(56, 189, 248, 0.7);
        animation: jdPulse 1.8s infinite ease-out;
    }

    @keyframes jdPulse {
        0% { transform: scale(0.9); opacity: 0.7; }
        70% { transform: scale(1.15); opacity: 0; }
        100% { transform: scale(1.15); opacity: 0; }
    }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 500;
        border: 1px solid rgba(148, 163, 184, 0.4);
        background: rgba(15, 23, 42, 0.9);
        color: #e5e7eb;
    }

    .chip-label {
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #9ca3af;
        font-size: 10px;
    }

    .chip-value {
        font-weight: 600;
        color: #e5e7eb;
    }

    .chip-id {
        border-color: rgba(56, 189, 248, 0.7);
        background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.15), rgba(15,23,42,0.96));
    }

    .chip-type {
        border-color: rgba(129, 140, 248, 0.7);
        background: radial-gradient(circle at top left, rgba(129, 140, 248, 0.15), rgba(15,23,42,0.96));
    }

    /* Cards */
    .jd-card {
        border-radius: 18px;
        background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.08), rgba(15, 23, 42, 0.98));
        box-shadow:
            0 20px 40px rgba(15, 23, 42, 0.9),
            0 0 0 1px rgba(30, 64, 175, 0.25);
        padding: 16px 16px 14px;
        color: #e5e7eb;
        margin-bottom: 16px;
    }

    .jd-summary {
        margin-bottom: 20px;
    }

    .jd-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .jd-card-header h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #e5e7eb;
    }

    .jd-card-header p {
        margin: 4px 0 0;
        font-size: 12px;
        color: #9ca3af;
    }

    /* Status pill (same visual style as jobs page) */
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
        width: 8px;
        height: 8px;
        border-radius: 999px;
        box-shadow: 0 0 0 5px transparent;
    }

    .status-pill.status-pending {
        border-color: rgba(148, 163, 184, 0.6);
        color: #e5e7eb;
    }
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
        animation: jdStatusBlink 1.4s infinite;
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

    @keyframes jdStatusBlink {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.7; }
    }

    /* Summary grid */
    .jd-summary-grid {
        display: grid;
        grid-template-columns: minmax(0, 2.2fr) repeat(3, minmax(0, 1fr));
        gap: 12px 18px;
        margin-top: 6px;
    }

    .jd-summary-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .jd-summary-wide {
        grid-column: 1 / -1;
    }

    .jd-summary-item .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #9ca3af;
        text-align: left !important;
    }

    .jd-summary-item .value {
        font-size: 14px;
        font-weight: 600;
        color: #e5e7eb;
    }

    .jd-summary-item .value-muted {
        font-size: 13px;
        font-weight: 500;
        color: #cbd5f5;
    }

    .message-pill {
        border-radius: 10px;
        border: 1px solid rgba(148, 163, 184, 0.4);
        padding: 8px 10px;
        font-size: 13px;
        color: #e5e7eb;
        background: radial-gradient(circle at top left, rgba(15, 23, 42, 0.96), rgba(15, 23, 42, 1));
    }

    /* Progress bar */
    .progress-wrapper {
        min-width: 220px;
    }

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
    }

    .progress-bar-fill.status-pending {
        background: linear-gradient(90deg, #64748b, #94a3b8);
    }
    .progress-bar-fill.status-processing {
        background: linear-gradient(90deg, #f59e0b, #facc15, #22c55e);
        animation: jdProgressShimmer 1.6s linear infinite;
        background-size: 200% 100%;
    }
    .progress-bar-fill.status-completed {
        background: linear-gradient(90deg, #22c55e, #4ade80);
        box-shadow: 0 0 12px rgba(34, 197, 94, 0.7);
    }
    .progress-bar-fill.status-failed {
        background: linear-gradient(90deg, #ef4444, #f97373);
    }

    @keyframes jdProgressShimmer {
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

    /* Badge */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border: 1px solid transparent;
    }

    .badge-warn {
        border-color: rgba(234, 179, 8, 0.7);
        color: #fef9c3;
        background: radial-gradient(circle at top left, rgba(234, 179, 8, 0.28), rgba(15,23,42,0.98));
    }

    .badge-error {
        border-color: rgba(239, 68, 68, 0.8);
        color: #fecaca;
        background: radial-gradient(circle at top left, rgba(239, 68, 68, 0.25), rgba(15,23,42,0.98));
    }

    .badge-info {
        border-color: rgba(56, 189, 248, 0.7);
        color: #bae6fd;
        background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.25), rgba(15,23,42,0.98));
    }

    .badge-info.badge-soft {
        border-color: rgba(59, 130, 246, 0.7);
    }

    /* Lists & empty state */
    .jd-empty {
        margin: 4px 0 2px;
        font-size: 13px;
        color: #9ca3af;
    }

    .jd-list {
        margin: 4px 0 0;
        padding-left: 20px;
        color: #e5e7eb;
        max-height: 260px;
        overflow-y: auto;
        font-size: 13px;
    }

    .jd-list li {
        margin-bottom: 4px;
    }

    .jd-list::-webkit-scrollbar {
        width: 6px;
    }
    .jd-list::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.9);
    }
    .jd-list::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.6);
        border-radius: 999px;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .jd-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .progress-wrapper {
            min-width: 0;
        }
    }

    @media (max-width: 640px) {
        .job-details-page {
            padding: 12px;
        }
        .jd-header-left h1 {
            font-size: 19px;
        }
        .jd-header-left p {
            font-size: 12px;
        }
        .jd-summary-grid {
            grid-template-columns: minmax(0, 1fr);
        }
        .jd-card {
            padding: 14px 12px 12px;
        }
    }
</style>

@endsection
