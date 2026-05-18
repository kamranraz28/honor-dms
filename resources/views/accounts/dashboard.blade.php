@extends('layouts.master_accounts')

@section('title')
    {{ 'SAS :: Dashboard' }}
@endsection

@section('content')

<style>
/* ======= New Premium Color / Dark UI Dashboard ======= */

body, .content-wrapper {
    background: #0f172a !important;
}

.dashboard-container {
    margin-top: 40px;
    margin-bottom: 40px;
}

.modern-card {
    padding: 28px;
    border-radius: 20px;
    background: linear-gradient(145deg, #1e293b, #0f172a);
    border: 1px solid rgba(255,255,255,0.05);

    box-shadow:
        6px 6px 20px rgba(0, 0, 0, 0.6),
        -4px -4px 15px rgba(255, 255, 255, 0.03);

    transition: all 0.3s ease-in-out;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.modern-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow:
        12px 12px 25px rgba(0, 0, 0, 0.7),
        -4px -4px 20px rgba(94, 234, 212, 0.1);
}

.modern-card::after {
    content: "";
    position: absolute;
    top: -60px;
    right: -60px;
    width: 140px;
    height: 140px;
    background: rgba(45, 212, 191, 0.09);
    border-radius: 50%;
    transition: 0.4s ease;
}

.modern-card:hover::after {
    top: -80px;
    right: -80px;
    background: rgba(45, 212, 191, 0.16);
}

/* ICON BOX */
.icon-box {
    width: 70px;
    height: 70px;
    border-radius: 18px;

    background: linear-gradient(135deg, #0d9488, #22d3ee);
    display: flex;
    align-items: center;
    justify-content: center;

    box-shadow:
        0 8px 18px rgba(34, 211, 238, 0.5),
        inset 0 0 30px rgba(13, 148, 136, 0.7);

    animation: floatIcon 3s ease-in-out infinite;
}

.icon-box i {
    font-size: 30px;
    color: #ffffff;
}

/* ICON ANIMATION */
@keyframes floatIcon {
    0% { transform: translateY(0) }
    50% { transform: translateY(-10px) }
    100% { transform: translateY(0) }
}

/* TEXT */
.modern-card h3 {
    margin-top: 18px;
    font-size: 16px;
    font-weight: 600;
    color: #e2e8f0;
    opacity: 0.9;
}

.modern-card h2 {
    font-size: 36px;
    font-weight: 700;
    margin-top: 10px;
    color: #fff;
}


/* MEDIA */
@media screen and (max-width: 768px) {
    .modern-card h2 {
        font-size: 28px;
    }
}
</style>



<div class="content-wrapper">

    @include('accounts.bc.bc')

    <section class="content-header">
        <h1 style="color:#fff;">Dashboard <small style="color:#94a3b8">Control Panel</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('accounts.dashboard') }}" style="color:#38bdf8"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active" style="color:#38bdf8">Dashboard</li>
        </ol>
    </section>


    <!-- Dashboard Section -->
    <section class="content dashboard-container">

        <div class="row">

            <!-- Total Orders -->
            <div class="col-md-3 col-sm-6 col-12">
                <div class="modern-card">
                    <div class="icon-box"><i class="fa fa-shopping-cart"></i></div>
                    <h3>Total Orders</h3>
                    <h2>{{ $posting->count() }}</h2>
                </div>
            </div>

            <!-- Waiting for Review -->
            <div class="col-md-3 col-sm-6 col-12">
                <div class="modern-card">
                    <div class="icon-box"><i class="fa fa-hourglass-half"></i></div>
                    <h3>Waiting for Review</h3>
                    <h2>{{ $posting->where('status', '0')->count() }}</h2>
                </div>
            </div>

            <!-- Processing -->
            <div class="col-md-3 col-sm-6 col-12">
                <div class="modern-card">
                    <div class="icon-box"><i class="fa fa-cogs"></i></div>
                    <h3>Processing</h3>
                    <h2>{{ $posting->where('status', '2')->count() }}</h2>
                </div>
            </div>

            <!-- Completed -->
            <div class="col-md-3 col-sm-6 col-12">
                <div class="modern-card">
                    <div class="icon-box"><i class="fa fa-check-circle"></i></div>
                    <h3>Completed</h3>
                    <h2>{{ $posting->where('status', '5')->count() }}</h2>
                </div>
            </div>

        </div>

    </section>

</div>

@endsection
