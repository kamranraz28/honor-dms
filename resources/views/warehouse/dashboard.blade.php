@extends('layouts.master_warehouse')

@section('title')
    {{ 'SAS :: Dashboard' }}
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper dashboard-wrapper">
        <!-- bc part================================ -->
        @include('warehouse.bc.bc')
        <!-- bc part================================ -->

        <!-- Header -->
        <section class="content-header dashboard-header">
            <h1>
                <span class="dash-title-main">Dashboard</span>
                <small class="dash-title-sub">Control panel</small>
            </h1>
            <ol class="breadcrumb dashboard-breadcrumb">
                <li>
                    <a href="{{ route('warehouse.dashboard') }}">
                        <i class="fa fa-tachometer"></i> Home
                    </a>
                </li>
                <li class="active">
                    <a href="{{ route('warehouse.dashboard') }}">Dashboard</a>
                </li>
            </ol>
        </section>

        <!-- Action buttons -->
        <div class="text-left col-lg-12 dashboard-actions">
            <a class="btn btn-sm dash-btn" style="margin-right:20px;"
               href="{{ route('warehouse.dataSink') }}">
                <i class="fa fa-refresh"></i>
                <span>Data Synchronization</span>
            </a>
            <a class="btn btn-sm dash-btn"
               href="{{ route('warehouse.refreshStock') }}">
                <i class="fa fa-refresh"></i>
                <span>Refresh Stock</span>
            </a>
        </div>

        <!-- Main content -->
        <section class="content">

            <div class="row dashboard-stats-row" style="margin-top: 30px; margin-bottom: 30px;">
                <!-- TOTAL REQUEST -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box dash-card dash-card-total">
                        <span class="info-box-icon">
                            <i class="fa fa-list-alt" aria-hidden="true"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Request</span>
                            <h4 class="info-box-number dash-number">{{ $posting->count() }}</h4>
                        </div>
                    </div>
                </div>

                <!-- WAITING -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box dash-card dash-card-waiting">
                        <span class="info-box-icon">
                            <i class="fa fa-hourglass-half" aria-hidden="true"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Waiting</span>
                            <h4 class="info-box-number dash-number">{{ $posting->where('status', '1')->count() }}</h4>
                        </div>
                    </div>
                </div>

                <!-- PROCESSING -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box dash-card dash-card-processing">
                        <span class="info-box-icon">
                            <i class="fa fa-cogs" aria-hidden="true"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Processing</span>
                            <h4 class="info-box-number dash-number">{{ $posting->where('status', '2')->count() }}</h4>
                        </div>
                    </div>
                </div>

                <!-- COMPLETE -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box dash-card dash-card-complete">
                        <span class="info-box-icon">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Complete</span>
                            <h4 class="info-box-number dash-number">{{ $posting->where('status', '5')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection

@push('customheader')
<style>
    /* ================= DASHBOARD SCOPE ONLY ================= */

    .dashboard-wrapper {
        background: #f9fafb; /* light neutral background */
    }

    .dashboard-header h1 {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .dash-title-main {
        font-weight: 700;
        letter-spacing: 0.03em;
    }

    .dash-title-sub {
        font-size: 13px;
        color: #6b7280;
    }

    .dashboard-breadcrumb > li > a,
    .dashboard-breadcrumb > li.active > a {
        color: #6b7280 !important;
    }

    /* ---------------- TOP ACTION BUTTONS ---------------- */
    .dashboard-actions {
        margin-top: 15px;
        margin-bottom: 10px;
    }

    .dashboard-actions .dash-btn {
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 18px;
        border: none;
        font-weight: 500;
        background-image: linear-gradient(135deg, #22c1c3, #6366f1);
        color: #f9fafb;
        box-shadow: 0 10px 24px rgba(79, 70, 229, 0.35);
    }

    .dashboard-actions .dash-btn i {
        font-size: 14px;
    }

    .dashboard-actions .dash-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 32px rgba(79, 70, 229, 0.45);
        text-decoration: none;
    }

    /* ---------------- RESET + STYLE INFO BOXES ONLY HERE ---------------- */
    /* Force AdminLTE-like layout regardless of master overrides */

    .dashboard-wrapper .dashboard-stats-row .info-box {
        display: block;              /* restore block */
        position: relative;
        min-height: 90px;
        margin-bottom: 20px;
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        overflow: hidden;
        opacity: 0;
        transform: translateY(12px);
        animation: dashCardIn 0.45s ease-out forwards;
    }

    /* subtle stagger */
    .dashboard-wrapper .dashboard-stats-row > div:nth-child(1) .info-box { animation-delay: 0.05s; }
    .dashboard-wrapper .dashboard-stats-row > div:nth-child(2) .info-box { animation-delay: 0.10s; }
    .dashboard-wrapper .dashboard-stats-row > div:nth-child(3) .info-box { animation-delay: 0.15s; }
    .dashboard-wrapper .dashboard-stats-row > div:nth-child(4) .info-box { animation-delay: 0.20s; }

    .dashboard-wrapper .dashboard-stats-row .info-box:hover {
        transform: translateY(0) scale(1.01);
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
    }

    /* ICON AREA: reset and style so it's NEVER flattened */
    .dashboard-wrapper .dashboard-stats-row .info-box-icon {
        display: block;
        float: left;
        height: 90px;
        width: 90px;
        line-height: 90px;
        text-align: center;
        font-size: 36px;
        border-radius: 50%;
        margin: 8px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.25);
    }

    .dashboard-wrapper .dashboard-stats-row .info-box-icon i {
        position: relative;
        z-index: 2;
        animation: iconBreath 3s ease-in-out infinite;
    }

    .dashboard-wrapper .dashboard-stats-row .info-box-icon::before {
        content: "";
        position: absolute;
        inset: -40%;
        background: radial-gradient(circle at top, rgba(255,255,255,0.7), transparent 65%);
        z-index: 1;
        opacity: 0.8;
    }

    /* CONTENT AREA: reset margin + style */
    .dashboard-wrapper .dashboard-stats-row .info-box-content {
        margin-left: 110px; /* 90 icon + margin */
        padding: 14px 18px;
    }

    .dashboard-wrapper .dashboard-stats-row .info-box-text {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #6b7280;
    }

    /* HIGHLIGHTED NUMBERS */
    .dashboard-wrapper .dashboard-stats-row .dash-number {
        font-size: 32px;
        font-weight: 800;
        margin-top: 4px;
        margin-bottom: 4px;
        color: #0f172a;
        position: relative;
    }

    .dashboard-wrapper .dashboard-stats-row .dash-number::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -5px;
        width: 40%;
        max-width: 90px;
        height: 3px;
        border-radius: 999px;
        background: linear-gradient(90deg, rgba(56,189,248,0.8), rgba(129,140,248,0.1));
    }

    /* CARD COLOR THEME (stylish teal / purple) */
    .dashboard-wrapper .dash-card-total .info-box-icon {
        background: linear-gradient(135deg, #06b6d4, #3b82f6);
        color: #ecfeff;
    }

    .dashboard-wrapper .dash-card-waiting .info-box-icon {
        background: linear-gradient(135deg, #f97316, #facc15);
        color: #fff7ed;
    }

    .dashboard-wrapper .dash-card-processing .info-box-icon {
        background: linear-gradient(135deg, #22c55e, #14b8a6);
        color: #ecfdf5;
    }

    .dashboard-wrapper .dash-card-complete .info-box-icon {
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        color: #f5f3ff;
    }

    /* ANIMATIONS */
    @keyframes dashCardIn {
        from {
            opacity: 0;
            transform: translateY(16px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes iconBreath {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.08);
        }
    }

    /* RESPONSIVE TWEAKS */
    @media (max-width: 768px) {
        .dashboard-wrapper .dashboard-stats-row .info-box {
            margin-bottom: 15px;
        }

        .dashboard-wrapper .dashboard-stats-row .info-box-icon {
            height: 80px;
            width: 80px;
            line-height: 80px;
            font-size: 30px;
            margin: 10px;
        }

        .dashboard-wrapper .dashboard-stats-row .info-box-content {
            margin-left: 100px;
        }

        .dashboard-wrapper .dashboard-stats-row .dash-number {
            font-size: 26px;
        }
    }
</style>
@endpush
