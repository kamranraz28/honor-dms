@extends('layouts.master_admin')

@section('title')
  {{"Sales Automation Process :: Dashboard"}}
@endsection

@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      <!-- @include('admin.bc.bc') -->
    <!-- bc part================================ -->

<style>
    .DTFC_LeftBodyLiner{overflow-y:unset !important}
    .DTFC_RightBodyLiner{overflow-y:unset !important}

    /* overall page darker tone for stats area to match chart cards */
    .stats-section {
      background: linear-gradient(180deg,#0b1620,#0f1724);
      padding: 18px;
      border-radius: 14px;
      border: 1px solid rgba(255,255,255,0.03);
      margin: 12px;
      box-shadow: 0 12px 40px rgba(2,6,23,0.45);
    }

    /* ---- Modern stat cards ---- */
    .stats-row { display:flex; flex-wrap:wrap; gap:18px; margin:6px 0; }
    .stat-card {
      flex:1 1 calc(33.333% - 12px);
      min-width:220px;
      background: linear-gradient(180deg,#07121a,#0b1620);
      border: 1px solid rgba(255,255,255,0.04);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      border-radius:14px;
      padding:18px;
      display:flex;
      align-items:center;
      gap:14px;
      box-shadow: 0 6px 30px rgba(2,6,23,0.6);
      transition: transform .22s ease, box-shadow .22s ease, background .3s ease;
      position: relative;
      overflow: hidden;
    }
    .stat-card::after{
      content:'';
      position:absolute;
      left:-40%;
      top:-60%;
      width:220%;
      height:220%;
      background: linear-gradient(120deg, rgba(255,255,255,0.01), rgba(255,255,255,0.00) 35%, rgba(255,255,255,0.03) 50%, rgba(255,255,255,0.00) 65%);
      transform: rotate(20deg) translateX(-20%);
      transition: transform .9s ease;
      pointer-events:none;
      opacity:0.45;
      mix-blend-mode: overlay;
    }
    .stat-card:hover { transform: translateY(-8px); box-shadow: 0 20px 54px rgba(2,6,23,0.7); }
    .stat-card:hover::after{ transform: rotate(20deg) translateX(30%); }

    .stat-icon {
      width:68px; height:68px; border-radius:12px; display:flex; align-items:center; justify-content:center;
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
      font-size:28px;
      color: #fff;
      box-shadow: inset 0 -6px 18px rgba(0,0,0,0.5);
      flex-shrink:0;
      position: relative;
      overflow: hidden;
    }
    .stat-icon::before{
      content:'';
      position:absolute;
      inset:0;
      background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.02), transparent 20%);
      mix-blend-mode: overlay;
      pointer-events:none;
    }

    .stat-content { flex:1; position:relative; z-index:2; }
    .stat-label { font-size:12px; color: #98b2c8; margin-bottom:6px; letter-spacing:0.6px; text-transform:uppercase; font-weight:700; opacity:0.95; }
    .stat-number { font-size:28px; font-weight:900; color:#e6eef8; letter-spacing:0.2px; display:block; line-height:1; position:relative; }

    /* animated number wrapper (position:relative so shimmer can work) */
    .animated-count {
      display:inline-block;
      transform-origin: 50% 50%;
      transition: transform .48s cubic-bezier(.2,.9,.2,1), text-shadow .48s cubic-bezier(.2,.9,.2,1), color .3s ease;
      will-change: transform, text-shadow, color;
      position: relative;
    }

    /* stronger pop + glow when animation completes */
    .animated-count.count-animate {
      transform: translateY(-8px) scale(1.14);
      text-shadow: 0 10px 30px rgba(0,160,255,0.18), 0 4px 12px rgba(0,0,0,0.6);
      color: #ffffff;
    }

    /* shimmer pseudo-element (only visible while .count-animate exists) */
    .animated-count.count-animate::after{
      content:'';
      position:absolute;
      width:60px;
      height:28px;
      left:-10px;
      top:0;
      transform: translateX(-120%) skewX(-12deg);
      background: linear-gradient(90deg, rgba(255,255,255,0.06), rgba(255,255,255,0.22), rgba(255,255,255,0.06));
      animation: shimmer 900ms linear;
      pointer-events: none;
      border-radius:6px;
      opacity:0.95;
    }
    @keyframes shimmer {
      from { transform: translateX(-120%) skewX(-12deg); opacity:0; }
      40% { opacity:1; }
      to { transform: translateX(220%) skewX(-12deg); opacity:0; }
    }

    /* color variants */
    .stat-aqua .stat-icon { background: linear-gradient(180deg,#053a41,#0b5a61); color:#e6fbf9; }
    .stat-yellow .stat-icon { background: linear-gradient(180deg,#4a2b00,#7a4900); color:#fff7ea; }
    .stat-green .stat-icon { background: linear-gradient(180deg,#063a2c,#126e4b); color:#eafff2; }

    /* floating entrance animation */
    @keyframes floatIn {
      0% { opacity:0; transform: translateY(18px) scale(.98); }
      60% { opacity:1; transform: translateY(-6px) scale(1.01); }
      100% { transform: translateY(0) scale(1); }
    }
    .stat-card { animation: floatIn .7s cubic-bezier(.2,.8,.2,1) both; }

    /* staggered delays */
    .stat-card:nth-child(1){ animation-delay: 0.06s; }
    .stat-card:nth-child(2){ animation-delay: 0.12s; }
    .stat-card:nth-child(3){ animation-delay: 0.18s; }
    .stat-card:nth-child(4){ animation-delay: 0.24s; }
    .stat-card:nth-child(5){ animation-delay: 0.30s; }
    .stat-card:nth-child(6){ animation-delay: 0.36s; }
    .stat-card:nth-child(7){ animation-delay: 0.42s; }
    .stat-card:nth-child(8){ animation-delay: 0.48s; }
    .stat-card:nth-child(9){ animation-delay: 0.54s; }

    /* Chart card */
    .chart-card { background: #0f1724; padding:14px; border-radius:12px; border:1px solid rgba(255,255,255,0.03); margin-bottom:18px; }
    .chart-title { text-align:center; color:#9fb3cc; margin:8px 0 0 0; font-size:13px; }

    /* Responsive tweaks */
    @media (max-width: 992px) {
      .stat-card { flex:1 1 calc(50% - 12px); }
    }
    @media (max-width: 600px) {
      .stat-card { flex:1 1 100%; }
    }

    /* ensure canvases have sensible height */
    .chart-card canvas { width:100% !important; height: 300px !important; display:block; }

    /* ---------------- New darker styles for the two buttons ---------------- */
    .admin-action-row { display:flex; gap:12px; align-items:center; margin:12px 10px; flex-wrap:wrap; }

    /* upgraded action button base - dark theme */
    .action-btn {
      display:inline-flex;
      align-items:center;
      gap:10px;
      padding:10px 16px;
      border-radius:12px;
      font-weight:700;
      font-size:13px;
      text-decoration:none;
      color:#e6eef8;
      background: linear-gradient(90deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
      border: 1px solid rgba(255,255,255,0.04);
      box-shadow: 0 10px 30px rgba(2,6,23,0.7);
      transition: transform .22s ease, box-shadow .22s ease, filter .2s ease;
      position: relative;
      overflow: hidden;
    }
    .action-btn .btn-icon {
  display:inline-flex;
  width:20px;
  height:20px;
  align-items:center;
  justify-content:center;
  border-radius:6px;
  font-size:14px;
  color:#cfeff0;                        /* lighter text for contrast */
  background: linear-gradient(180deg,#07121a,#0b1620); /* dark gradient to match charts */
  border: 1px solid rgba(255,255,255,0.03);
  box-shadow: inset 0 -3px 8px rgba(0,0,0,0.6);
}

    .action-btn .btn-text { display:inline-block; min-width:120px; text-align:left; color:#e6eef8; }

    /* Data Sync button specific - dark */
    .action-sync {
      background: linear-gradient(90deg, rgba(0,40,46,0.34), rgba(1,22,26,0.36));
      color: #dffafa;
      border: 1px solid rgba(0,188,212,0.10);
      box-shadow: 0 10px 30px rgba(0,188,212,0.04);
    }
    .action-sync .btn-icon { background: linear-gradient(180deg,#037a74,#054b4a); color:#e6fbf9; box-shadow: 0 4px 14px rgba(0,188,212,0.04); }

    /* Refresh button specific - dark */
    .action-refresh {
      background: linear-gradient(90deg, rgba(60,40,0,0.22), rgba(30,18,0,0.24));
      color: #fff4d9;
      border: 1px solid rgba(255,170,0,0.06);
      box-shadow: 0 10px 30px rgba(255,170,0,0.02);
    }
    .action-refresh .btn-icon { background: linear-gradient(180deg,#8a5b1a,#623900); color:#fff4d9; box-shadow: 0 4px 14px rgba(255,170,0,0.04); }

    /* micro hover effects */
    .action-btn:hover { transform: translateY(-6px); filter:brightness(1.06); box-shadow: 0 20px 48px rgba(2,6,23,0.6); }
    .action-btn:active { transform: translateY(-2px) scale(.995); }

    /* syncing pulse */
    @keyframes pulseGlow {
      0% { box-shadow: 0 8px 28px rgba(0,188,212,0.06); transform: translateY(0); }
      50% { box-shadow: 0 22px 48px rgba(0,188,212,0.12); transform: translateY(-3px); }
      100% { box-shadow: 0 8px 28px rgba(0,188,212,0.06); transform: translateY(0); }
    }
    .action-sync.syncing { animation: pulseGlow 1.8s ease-in-out infinite; }

    /* small label chip inside the action button (for last sync time or hint) */
    .action-chip {
      font-size:11px;
      padding:4px 8px;
      border-radius:999px;
      background: rgba(255,255,255,0.02);
      color: #cfeff0;
      margin-left:auto;
      display:inline-block;
      border: 1px solid rgba(255,255,255,0.02);
    }

    /* Improve Mira container alignment (we didn't change button) */
    .mira-wrap{ display:flex; justify-content:center; align-items:center; height:20vh; margin:0; background-color:#f0f0f0; }

</style>

    <!-- Main content -->
    <section class="content">

      <!-- Small boxes (Stat box) -->
      <div class="row">

<div class="box-body">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<div class="mira-wrap">
    <a href="{{ route('admin.mira') }}" style="width: 100%; text-decoration: none, display: inline-block;">
        <button
            class="ask-siri-button"
            style="width: 100%; padding: 15px 30px; font-size: 18px; color: white; background-color: rgba(0, 0, 0, 0.7); border: none; border-radius: 50px; box-shadow: 0 0 10px rgba(128, 0, 128, 0.6), 0 0 20px rgba(128, 0, 128, 0.6); cursor: pointer; outline: none; transition: all 0.3s ease; text-align: center;"
            onmouseover="this.style.boxShadow = '0 0 25px rgba(0, 255, 0, 0.8), 0 0 40px rgba(0, 255, 0, 0.8)'"
            onmouseout="this.style.boxShadow = '0 0 10px rgba(128, 0, 128, 0.6), 0 0 20px rgba(128, 0, 128, 0.6)'"
            onmousedown="this.style.boxShadow = '0 0 15px rgba(128, 0, 128, 0.8), 0 0 30px rgba(128, 0, 128, 0.8)'"
            onmouseup="this.style.boxShadow = '0 0 25px rgba(0, 255, 0, 0.8), 0 0 40px rgba(0, 255, 0, 0.8)'"
        >
            Mira, Your AI Assistant
        </button>
    </a>
</div>

<div class="admin-action-row" style="margin-top:8px; margin-left:10px;">
    <!-- Data Sync (styled) -->
    <a class="action-btn action-sync" href="{{ route('admin.dataSink') }}" id="btnDataSync">
        <span class="btn-icon"><i class="bi bi-arrow-repeat" style="font-size:16px;"></i></span>
        <span class="btn-text">Data Sync</span>
        <span class="action-chip">Sync Now</span>
    </a>

    <!-- Refresh Dashboard Data (styled) -->
    <a class="action-btn action-refresh" href="{{ route('admin.dashboard.cache') }}" id="btnRefreshDashboard">
        <span class="btn-icon"><i class="bi bi-arrow-clockwise" style="font-size:16px;"></i></span>
        <span class="btn-text">Refresh Dashboard Data</span>
        <span class="action-chip">Live</span>
    </a>
</div>

    @if(count($errors))
      <div class="alert alert-danger alert-dismissible">
        <strong>Whoops!</strong> There were some problems with your input.
        <br/>
        <ul>
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(Session::has('success'))
      <div class="alert alert-success alert-dismissible fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> {{Session::get('success')}}
      </div>
    @endif
</div>

<!-- Darker stats container -->
<div class="stats-section">
  <div class="stats-row" style="margin-left:4px; margin-right:4px;">
    <div class="stat-card stat-aqua">
      <div class="stat-icon"><i class="ion ion-pie-graph"></i></div>
      <div class="stat-content">
        <div class="stat-label">Total Primary Sale</div>
        <div class="stat-number">
          <span class="animated-count">
            <span class="countup" data-target="{{ $data['totalPrimarySale'] }}">{{ $data['totalPrimarySale'] }}</span>
          </span>
        </div>
      </div>
    </div>

    <div class="stat-card stat-yellow">
      <div class="stat-icon"><i class="ion ion-pie-graph"></i></div>
      <div class="stat-content">
        <div class="stat-label">Monthly Primary Sale</div>
        <div class="stat-number">
          <span class="animated-count">
            <span class="countup" data-target="{{ $data['monthlyPrimarySale'] }}">{{ $data['monthlyPrimarySale'] }}</span>
          </span>
        </div>
      </div>
    </div>

    <div class="stat-card stat-green">
      <div class="stat-icon"><i class="ion ion-pie-graph"></i></div>
      <div class="stat-content">
        <div class="stat-label">Today Primary Sale</div>
        <div class="stat-number">
          <span class="animated-count">
            <span class="countup" data-target="{{ $data['todayPrimarySale'] }}">{{ $data['todayPrimarySale'] }}</span>
          </span>
        </div>
      </div>
    </div>

    <div class="stat-card stat-aqua">
      <div class="stat-icon"><i class="ion ion-pie-graph"></i></div>
      <div class="stat-content">
        <div class="stat-label">Total Secondary Sale</div>
        <div class="stat-number">
          <span class="animated-count">
            <span class="countup" data-target="{{ $data['totalSecondarySale'] }}">{{ $data['totalSecondarySale'] }}</span>
          </span>
        </div>
      </div>
    </div>

    <div class="stat-card stat-yellow">
      <div class="stat-icon"><i class="ion ion-pie-graph"></i></div>
      <div class="stat-content">
        <div class="stat-label">Monthly Secondary Sale</div>
        <div class="stat-number">
          <span class="animated-count">
            <span class="countup" data-target="{{ $data['monthlySecondarySale'] }}">{{ $data['monthlySecondarySale'] }}</span>
          </span>
        </div>
      </div>
    </div>

    <div class="stat-card stat-green">
      <div class="stat-icon"><i class="ion ion-pie-graph"></i></div>
      <div class="stat-content">
        <div class="stat-label">Today Secondary Sale</div>
        <div class="stat-number">
          <span class="animated-count">
            <span class="countup" data-target="{{ $data['todaySecondarySale'] }}">{{ $data['todaySecondarySale'] }}</span>
          </span>
        </div>
      </div>
    </div>

    <div class="stat-card stat-aqua">
      <div class="stat-icon"><i class="ion ion-pie-graph"></i></div>
      <div class="stat-content">
        <div class="stat-label">Total Tertiary Sale</div>
        <div class="stat-number">
          <span class="animated-count">
            <span class="countup" data-target="{{ $data['totalTertiarySale'] }}">{{ $data['totalTertiarySale'] }}</span>
          </span>
        </div>
      </div>
    </div>

    <div class="stat-card stat-yellow">
      <div class="stat-icon"><i class="ion ion-pie-graph"></i></div>
      <div class="stat-content">
        <div class="stat-label">Monthly Tertiary Sale</div>
        <div class="stat-number">
          <span class="animated-count">
            <span class="countup" data-target="{{ $data['monthlyTertiarySale'] }}">{{ $data['monthlyTertiarySale'] }}</span>
          </span>
        </div>
      </div>
    </div>

    <div class="stat-card stat-green">
      <div class="stat-icon"><i class="ion ion-pie-graph"></i></div>
      <div class="stat-content">
        <div class="stat-label">Today Tertiary Sale</div>
        <div class="stat-number">
          <span class="animated-count">
            <span class="countup" data-target="{{ $data['todayTertiarySale'] }}">{{ $data['todayTertiarySale'] }}</span>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.stats-section -->

<!-- /.row -->

      <div class="row" style="padding:12px;">

        <div class="col-md-6">
          <div class="chart-card">
            <canvas id="dayinmonthchartdata"></canvas>
            <p class="chart-title">Current Month Daily Sales Chart</p>
          </div>
        </div>

        <div class="col-md-6">
          <div class="chart-card">
            <canvas id="monthinyearchartdata"></canvas>
            <p class="chart-title">Current Year Monthly Sales Chart</p>
          </div>
        </div>

    <div class="col-md-6">
          <div class="chart-card">
            <canvas id="todaysalebrandwise"></canvas>
            <p class="chart-title">Brand Wise Todays Tertiary Sales Chart</p>
          </div>
        </div>

        <div class="col-md-6">
          <div class="chart-card">
            <canvas id="monthlysalebrandwise"></canvas>
            <p class="chart-title">Brand Wise Current Month Tertiary Sales Chart</p>
          </div>
        </div>

        <div class="col-md-6">
          <div class="chart-card">
            <canvas id="topproduct"></canvas>
            <p class="chart-title">Current Month Top Product Chart</p>
          </div>
        </div>

        <div class="col-md-6">
          <div class="chart-card">
            <canvas id="topretailer"></canvas>
            <p class="chart-title">Current Month Top Retailer Chart</p>
          </div>
        </div>

        <div class="col-md-6">
          <div class="chart-card">
            <canvas id="topseconderysale"></canvas>
            <p class="chart-title">Current Month Top Secondary Sale Chart</p>
          </div>
        </div>

        <div class="col-md-6">
          <div class="chart-card">
            <canvas id="topdistributor"></canvas>
            <p class="chart-title">Current Month Top Distributor Chart</p>
          </div>
        </div>

      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->

  </div>
<!-- /.content-wrapper -->

<!-- Chart & helper scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.2.0/countUp.min.js"></script>

<script>
  // ---------- small helpers ----------
  function hexToRgba(hex, alpha){
    // support #RRGGBB or #RGB
    let h = hex.replace('#','');
    if(h.length === 3) h = h.split('').map(c => c+c).join('');
    const bigint = parseInt(h, 16);
    const r = (bigint >> 16) & 255;
    const g = (bigint >> 8) & 255;
    const b = bigint & 255;
    return 'rgba('+r+','+g+','+b+','+alpha+')';
  }

  // default palette
  const palette = [
    '#3F51B5','#00BCD4','#4CAF50','#FF9800','#FF5252','#9C27B0','#00ACC1','#8BC34A','#FFC107','#E91E63',
    '#2196F3','#7C4DFF','#00BFA5','#FF7043','#66BB6A'
  ];
  function pickColor(i){ return palette[i % palette.length]; }

  // set global text color for charts (light on dark)
  Chart.defaults.font.family = "'Helvetica Neue', Helvetica, Arial, sans-serif";
  Chart.defaults.color = '#cbd5e1';

  // ---------- Count up numbers (robust & animated) ----------
  document.addEventListener('DOMContentLoaded', function() {
    // find all countup elements and ensure they are wrapped properly
    var countEls = Array.from(document.querySelectorAll('.countup'));

    countEls.forEach(function(el, idx) {
      // ensure parent wrapper .animated-count exists (we used that in markup)
      var wrapper = el.closest('.animated-count');
      if(!wrapper){
        // create wrapper if missing
        wrapper = document.createElement('span');
        wrapper.className = 'animated-count';
        el.parentNode.insertBefore(wrapper, el);
        wrapper.appendChild(el);
      }

      var endVal = Number(el.getAttribute('data-target')) || 0;
      var startVal = Number((el.textContent + '').replace(/[^0-9.-]+/g,"")) || 0;

      // create CountUp instance on the element (this will replace inner text)
      var countUp = new CountUp(el, endVal, {
        startVal: startVal,
        duration: 1.6 + (idx * 0.04), // slight stagger by index
        useEasing: true,
        separator: ',',
        decimal: '.'
      });

      if (!countUp.error) {
        // stagger starts a bit so they cascade
        setTimeout(function(){
          countUp.start(function() {
            // add class to wrapper to trigger pop & shimmer
            try {
              wrapper.classList.add('count-animate');
              // remove after animation completes so it can replay later if needed
              setTimeout(function(){ wrapper.classList.remove('count-animate'); }, 1200);
            } catch(e){}
          });
        }, 90 * idx);
      } else {
        // fallback: just set text
        el.textContent = endVal;
      }
    });
  });

  // ---------- Build datasets from PHP arrays ----------
  const dayInMonthLabels = [
    @foreach ($dayinmonthchartdata as $key => $value)
      "{{ sprintf('%02d-%02d', $value['day'], $value['month']) }}",
    @endforeach
  ];
  const dayInMonthValues = [
    @foreach ($dayinmonthchartdata as $key => $value)
      {{ $value['sale'] }},
    @endforeach
  ];

  const monthInYearLabels = [
    @foreach ($monthinyearchartdata as $key => $value)
      "{{ date('M', mktime(0,0,0,$value['month'],1,$value['year'])) }}",
    @endforeach
  ];
  const monthInYearValues = [
    @foreach ($monthinyearchartdata as $key => $value)
      {{ $value['sale'] }},
    @endforeach
  ];

  const todayBrandLabels = [
    @foreach ($todaybrandwisesalechart as $key => $value)
      "{{ addslashes($value['name']) }}",
    @endforeach
  ];
  const todayBrandValues = [
    @foreach ($todaybrandwisesalechart as $key => $value)
      {{ $value['sale'] }},
    @endforeach
  ];

  const monthlyBrandLabels = [
    @foreach ($monthlybrandwisesalechart as $key => $value)
      "{{ addslashes($value['name']) }}",
    @endforeach
  ];
  const monthlyBrandValues = [
    @foreach ($monthlybrandwisesalechart as $key => $value)
      {{ $value['sale'] }},
    @endforeach
  ];

  const topProductLabels = [
    @foreach ($monthlytopproductchart as $key => $value)
      "{{ addslashes($value->product) }}",
    @endforeach
  ];
  const topProductValues = [
    @foreach ($monthlytopproductchart as $key => $value)
      {{ $value->sale }},
    @endforeach
  ];

  const topRetailerLabels = [
    @foreach ($monthlytopretailerchart as $key => $value)
      "{{ addslashes($value->user) }}",
    @endforeach
  ];
  const topRetailerValues = [
    @foreach ($monthlytopretailerchart as $key => $value)
      {{ $value->sale }},
    @endforeach
  ];

  const topSecondaryLabels = [
    @foreach ($monthlytopproductsalechart as $key => $value)
      "{{ addslashes($value->product) }}",
    @endforeach
  ];
  const topSecondaryValues = [
    @foreach ($monthlytopproductsalechart as $key => $value)
      {{ $value->sale }},
    @endforeach
  ];

  const topDistributorLabels = [
    @foreach ($monthlytopdistributorchart as $key => $value)
      "{{ addslashes($value->user) }}",
    @endforeach
  ];
  const topDistributorValues = [
    @foreach ($monthlytopdistributorchart as $key => $value)
      {{ $value->sale }},
    @endforeach
  ];

  // ---------- Create charts (unchanged) ----------
  function createLineChart(canvasId, labels, values, colorIndex = 0) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    return new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Sales',
          data: values,
          borderWidth: 2,
          pointRadius: 3,
          pointHoverRadius: 5,
          borderColor: pickColor(colorIndex),
          backgroundColor: hexToRgba(pickColor(colorIndex), 0.06),
          tension: 0.25,
          fill: true,
          pointBackgroundColor: pickColor(colorIndex)
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false, labels: { color: '#cbd5e1' } },
          tooltip: { titleColor: '#ffffff', bodyColor: '#e6eef8' }
        },
        scales: {
          x: {
            ticks: { color: '#cbd5e1', maxRotation: 0, autoSkip: true, maxTicksLimit: 10 },
            grid: { color: 'rgba(255,255,255,0.03)' }
          },
          y: {
            ticks: { color: '#cbd5e1', beginAtZero: true },
            grid: { color: 'rgba(255,255,255,0.03)' }
          }
        },
        animation: {
          duration: 700,
          easing: 'easeOutQuart'
        }
      }
    });
  }

  function createBarChart(canvasId, labels, values, colorIndexStart = 0, horizontal=false) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    const bg = labels.map((l,i) => hexToRgba(pickColor(colorIndexStart + i), 0.72));
    const brd = labels.map((l,i) => pickColor(colorIndexStart + i));
    return new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total',
          data: values,
          backgroundColor: bg,
          borderColor: brd,
          borderWidth: 1,
          borderRadius: 6,
          barThickness: horizontal ? 18 : undefined
        }]
      },
      options: {
        indexAxis: horizontal ? 'y' : 'x',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: { titleColor: '#ffffff', bodyColor: '#e6eef8' }
        },
        scales: {
          x: {
            ticks: { color: '#cbd5e1', maxRotation: 0, autoSkip: true, maxTicksLimit: 8 },
            grid: { color: 'rgba(255,255,255,0.03)' }
          },
          y: {
            ticks: { color: '#cbd5e1' },
            grid: { color: 'rgba(255,255,255,0.03)' }
          }
        },
        animation: {
          duration: 700,
          easing: 'easeOutCubic'
        }
      }
    });
  }

  // instantiate line charts (no wave loop)
  createLineChart('dayinmonthchartdata', dayInMonthLabels, dayInMonthValues, 0);
  createLineChart('monthinyearchartdata', monthInYearLabels, monthInYearValues, 1);

  // instantiate bar charts (reasonable ticks / horizontal where appropriate)
  createBarChart('todaysalebrandwise', todayBrandLabels, todayBrandValues, 2, false);
  createBarChart('monthlysalebrandwise', monthlyBrandLabels, monthlyBrandValues, 5, false);
  createBarChart('topproduct', topProductLabels, topProductValues, 8, true);
  createBarChart('topretailer', topRetailerLabels, topRetailerValues, 11, true);
  createBarChart('topseconderysale', topSecondaryLabels, topSecondaryValues, 14, true);
  createBarChart('topdistributor', topDistributorLabels, topDistributorValues, 17, true);

  // ensure canvases have sensible heights on load/resize
  function adjustCanvasHeights(){
    document.querySelectorAll('.chart-card canvas').forEach(c => {
      // enforce a standard height
      c.style.height = '300px';
    });
  }
  window.addEventListener('load', adjustCanvasHeights);
  window.addEventListener('resize', adjustCanvasHeights);

  // Optional UX: show syncing animation on Data Sync button while clicked (cleared automatically)
  (function(){
    var syncBtn = document.getElementById('btnDataSync');
    if(syncBtn){
      syncBtn.addEventListener('click', function(){
        syncBtn.classList.add('syncing');
        setTimeout(function(){ syncBtn.classList.remove('syncing'); }, 2500);
      });
    }
  })();
</script>

@endsection
