<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', '"Project"')</title>

    @if (@$_SESSION['favicon'])
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/app/' . $_SESSION['favicon']) }}">
    @else
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('resources/assets/dms/dist/img/favicon.ico') }}">
    @endif

    <!-- Table with csv,pdf,print -->
    <link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" rel="stylesheet">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet"
        href="{{ asset('resources/assets/dms/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('resources/assets/dms/bower_components/select2/dist/css/select2.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="{{ asset('resources/assets/dms/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('resources/assets/dms/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('resources/assets/dms/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
        href="{{ asset('resources/assets/dms/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('resources/assets/dms/dist/css/AdminLTE.min.css') }}">

    <!-- AdminLTE Skins -->
    <link rel="stylesheet" href="{{ asset('resources/assets/dms/dist/css/skins/_all-skins.min.css') }}">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('resources/assets/dms/plugins/iCheck/square/blue.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "tu2y4gvxwu");
    </script>

    <!-- Add more -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css">
    <script src="http://code.jquery.com/jquery-2.1.3.min.js" data-semver="2.1.3" data-require="jquery@*"></script>
    <script data-require="lodash.js@*" data-semver="3.1.0"
        src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/3.1.0/lodash.min.js"></script>
    <script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/f2c75b7247b/api/fnFakeRowspan.js"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="{{ asset('public/css/customskl.css') }}">

    @stack('customheader')

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<!-- Ajax Search START -->
    <!-- Ajax Search Retailer -->
    <script>
        $(function () {

            $('#retailer_search').on('input', function () {

                var query = this.value;

                if (query.length < 1) {
                    $('#retailer_list').empty();
                    $('#retailer_id').val('');
                    return;
                }

                $.get("{{ route('retailers.search') }}", { q: query }, function (data) {

                    var list = $('#retailer_list');
                    list.empty();

                    for (var i = 0; i < data.length; i++) {
                        list.append(
                            '<option data-id="' + data[i].id + '" value="' + data[i].text + '">'
                        );
                    }
                });

                var selected = $('#retailer_list option[value="' + query + '"]');
                $('#retailer_id').val(selected.length ? selected.data('id') : '');
            });

        });
        </script>

        <!-- Ajax Search Distributor -->

        <script>
$(function () {

    $('#distributor_search').on('input', function () {

        var query = this.value;

        if (query.length < 1) {
            $('#distributor_list').empty();
            $('#distributor_id').val('');
            return;
        }

        $.get("{{ route('distributors.search') }}", { q: query }, function (data) {

            var list = $('#distributor_list');
            list.empty();

            for (var i = 0; i < data.length; i++) {
                list.append(
                    '<option data-id="' + data[i].id + '" value="' + data[i].text + '">'
                );
            }
        });

        var selected = $('#distributor_list option[value="' + query + '"]');
        $('#distributor_id').val(selected.length ? selected.data('id') : '');
    });

});
</script>

<!-- Ajax Search Category -->
        <script>
$(function () {

    $('#category_search').on('input', function () {

        var query = this.value;

        if (query.length < 1) {
            $('#category_list').empty();
            $('#category_id').val('');
            return;
        }

        $.get("{{ route('category.search') }}", { q: query }, function (data) {

            var list = $('#category_list');
            list.empty();

            for (var i = 0; i < data.length; i++) {
                list.append(
                    '<option data-id="' + data[i].id + '" value="' + data[i].text + '">'
                );
            }
        });

        var selected = $('#category_list option[value="' + query + '"]');
        $('#category_id').val(selected.length ? selected.data('id') : '');
    });

});
</script>

<!-- Ajax Search Brand -->
        <script>
$(function () {

    $('#brand_search').on('input', function () {

        var query = this.value;

        if (query.length < 1) {
            $('#brand_list').empty();
            $('#brand_id').val('');
            return;
        }

        $.get("{{ route('brands.search') }}", { q: query }, function (data) {

            var list = $('#brand_list');
            list.empty();

            for (var i = 0; i < data.length; i++) {
                list.append(
                    '<option data-id="' + data[i].id + '" value="' + data[i].text + '">'
                );
            }
        });

        var selected = $('#brand_list option[value="' + query + '"]');
        $('#brand_id').val(selected.length ? selected.data('id') : '');
    });

});
</script>

<!-- Ajax Search Product -->
        <script>
$(function () {

    $('#product_search').on('input', function () {

        var query = this.value;

        if (query.length < 1) {
            $('#product_list').empty();
            $('#product_id').val('');
            return;
        }

        $.get("{{ route('products.search') }}", { q: query }, function (data) {

            var list = $('#product_list');
            list.empty();

            for (var i = 0; i < data.length; i++) {
                list.append(
                    '<option data-id="' + data[i].id + '" value="' + data[i].text + '">'
                );
            }
        });

        var selected = $('#product_list option[value="' + query + '"]');
        $('#product_id').val(selected.length ? selected.data('id') : '');
    });

});
</script>

<script>
$(function () {

    function bindAjaxSearch(inputId, listId, hiddenId, url, extra = {}) {

        $('#' + inputId).on('input', function () {

            let query = this.value;
            let list = $('#' + listId);
            let hidden = $('#' + hiddenId);

            hidden.val('');
            list.empty();

            let params = Object.assign({ q: query }, extra());

            if (!query || (extra()._required && !extra()._required)) {
                return;
            }

            $.get(url, params, function (data) {
                data.forEach(item => {
                    list.append(
                        `<option data-id="${item.id}" value="${item.text}"></option>`
                    );
                });

                let selected = $(`#${listId} option[value="${query}"]`);
                hidden.val(selected.length ? selected.data('id') : '');
            });
        });
    }

    bindAjaxSearch(
        'division_search',
        'division_list',
        'division_id',
        "{{ route('divisions.search') }}",
        () => ({})
    );

    bindAjaxSearch(
        'district_search',
        'district_list',
        'district_id',
        "{{ route('districts.search') }}",
        () => ({
            division_id: $('#division_id').val(),
            _required: $('#division_id').val()
        })
    );

    bindAjaxSearch(
        'upazila_search',
        'upazila_list',
        'upazila_id',
        "{{ route('upazilas.search') }}",
        () => ({
            district_id: $('#district_id').val(),
            _required: $('#district_id').val()
        })
    );

});
</script>



<!-- Ajax Search END -->

    <style>
        #example{
            display:none;
        }
        #example5{
            display:none;
        }

        /* =========================
           SINGLE GLOBAL LOADER
           ========================= */
        #loading {
            position: fixed;
            inset: 0; /* top/right/bottom/left: 0 */
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;      /* vertical center */
            justify-content: center;  /* horizontal center */
            background: rgba(15, 23, 42, 0.85); /* dark translucent */
            z-index: 9999;
            backdrop-filter: blur(3px);
        }

        .loader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
        }

        #loading-image {
            width: 80px;
            filter: drop-shadow(0 8px 16px rgba(0,0,0,0.35));
        }

        .loader-spinner {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 4px solid rgba(148, 163, 184, 0.35);   /* gray ring */
            border-top-color: #3b82f6;                     /* blue accent */
            animation: spin 1s linear infinite, glow 1.8s ease-in-out infinite;
        }

        .loader-text {
            color: #e5e7eb;
            font-size: 15px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-weight: 600;
            text-shadow: 0 2px 10px rgba(0,0,0,0.4);
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes glow {
            0% {
                box-shadow: 0 0 0 0 rgba(59,130,246,0.8);
            }
            70% {
                box-shadow: 0 0 0 14px rgba(59,130,246,0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59,130,246,0);
            }
        }

        /* -------------------------------------------------------
           GLOBAL THEME — ADVANCED MODERN SMART UI
        ----------------------------------------------------------*/

        body {
            background: #f3f4f6 !important;
            font-family: 'Source Sans Pro', sans-serif !important;
        }

        * {
            transition: all 0.25s ease;
        }

        .main-header {
            background: linear-gradient(135deg, #0f172a, #1e293b) !important;
            box-shadow: 0 4px 25px rgba(0,0,0,0.35);
        }

        .main-header .navbar {
            background: transparent !important;
            border: none !important;
        }

        .smart-logo-img,
        .main-header .logo img {
            height: 52px;
            object-fit: contain;
            filter: drop-shadow(0 5px 12px rgba(0,0,0,0.5));
            transition: 0.3s ease;
        }

        .main-header .logo img:hover {
            transform: translateY(-2px) scale(1.04);
        }

        .sidebar-toggle {
            padding: 16px;
        }

        .sidebar-toggle:hover {
            background: rgba(255,255,255,0.06);
        }

        .user-menu > a {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-menu img {
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(59,130,246,0.4);
        }

        .dropdown-menu {
            border-radius: 14px !important;
            border: none !important;
            box-shadow: 0 20px 45px rgba(0,0,0,0.35);
            animation: dropdownFade 0.2s ease-out;
        }

        @keyframes dropdownFade {
            from { opacity:0; transform: translateY(8px); }
            to   { opacity:1; transform: translateY(0); }
        }

        .user-header {
            background: radial-gradient(circle at top left, #1d4ed8, #0f172a);
            color: #fff;
        }

        .main-sidebar {
            background: linear-gradient(180deg, #0f172a, #111827 50%, #1f2937) !important;
            border-right: 1px solid rgba(255,255,255,0.07);
            box-shadow: 4px 0 20px rgba(0,0,0,0.25);
        }

        .sidebar {
            padding-top: 20px;
        }

        .user-panel img {
            box-shadow: 0 0 0 3px rgba(59,130,246,0.55);
        }

        .user-panel > .info p {
            color: #e5e7eb !important;
            font-weight: 600;
        }

        .user-panel .info a {
            color: #94a3b8 !important;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 6px rgba(34,197,94,0.9);
            margin-right: 4px;
            animation: pulseOnline 1.5s infinite;
        }

        @keyframes pulseOnline {
            0%   { transform: scale(1); opacity:1; }
            80%  { transform: scale(1.8); opacity:0; }
            100% { opacity:0; }
        }

        .sidebar-menu>li>a {
            color: #e5e7eb !important;
            padding: 12px 16px;
            margin: 4px 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu>li>a:hover,
        .sidebar-menu>li.active>a {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 8px 20px rgba(37,99,235,0.35);
            color: #fff !important;
            transform: translateY(-1px);
        }

        .sidebar-menu .treeview-menu {
            background: rgba(255,255,255,0.03);
            border-left: 2px solid #3b82f6;
            margin-left: 15px;
            padding-left: 10px;
            border-radius: 0 0 0 12px;
        }

        .treeview-menu>li>a {
            color: #94a3b8 !important;
        }

        .treeview-menu>li>a:hover {
            color: #e5e7eb !important;
            transform: translateX(4px);
        }

        table.dataTable {
            background: #fff !important;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 999px;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
        }

        .dataTables_wrapper .dt-buttons button {
            border-radius: 9999px !important;
            padding: 8px 18px !important;
            background: #2563eb !important;
            border: none !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(37,99,235,0.4);
        }

        .dataTables_wrapper .dt-buttons button:hover {
            background: #1d4ed8 !important;
        }

        .form-control {
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2) !important;
        }

        .btn {
            border-radius: 10px !important;
            padding: 7px 14px;
        }

        .btn-info {
            background: linear-gradient(135deg, #0ea5e9, #0284c7) !important;
            border: none !important;
            box-shadow: 0 6px 18px rgba(2,132,199,0.35);
        }

        .btn-info:hover {
            transform: translateY(-2px);
        }

        .box {
            border-radius: 10px !important;
            border: none !important;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        /* Force LTR layout for forms and Bootstrap grid columns used in forms */
html, body, .content, .content-wrapper, .main-content, .box, form {
  direction: ltr !important;
  text-align: left !important;
}

/* For horizontal Bootstrap forms keep labels left and columns float left */
.form-horizontal .control-label,
.form-group label {
  text-align: left !important;
  float: left !important;
}

/* Ensure Bootstrap grid columns inside form rows behave normally (not reversed) */
.form-horizontal .form-group > [class*="col-"] {
  float: left !important;
  text-align: left !important;
}

/* Clear any accidental right alignment for help text / error messages */
.form-group .help-block,
.form-group .text-danger,
.form-group .form-control {
  text-align: left !important;
}

/* If you used utility classes that might force RTL, override them */
.pull-right, .text-right {
  float: right !important;    /* keep existing behavior where intended */
}
/* but prefer left for form-specific elements */
form .pull-right,
form .text-right {
  float: none !important;
  text-align: left !important;
}

/* If any top-level element accidentally has dir="rtl", this will override */
[dir="rtl"] form,
[dir="rtl"] .form-horizontal {
  direction: ltr !important;
}

/* ================================
   DASHBOARD ACTION BUTTON STYLES
   ================================ */

.admin-action-row {
    display: flex;
    gap: 12px;
    align-items: center;
    margin: 12px 10px;
    flex-wrap: wrap;
}

/* Base button */
.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 13px;
    text-decoration: none;
    color: #e6eef8;
    background: linear-gradient(90deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
    border: 1px solid rgba(255,255,255,0.04);
    box-shadow: 0 10px 30px rgba(2,6,23,0.7);
    transition: transform .22s ease, box-shadow .22s ease, filter .2s ease;
    position: relative;
    overflow: hidden;
}

/* Icon box */
.action-btn .btn-icon {
    display: inline-flex;
    width: 20px;
    height: 20px;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-size: 14px;
    color: #cfeff0;
    background: linear-gradient(180deg,#07121a,#0b1620);
    border: 1px solid rgba(255,255,255,0.03);
    box-shadow: inset 0 -3px 8px rgba(0,0,0,0.6);
}

/* Text */
.action-btn .btn-text {
    display: inline-block;
    min-width: 160px;
    color: black;
}

/* Small chip (Export / Sync / Info) */
.action-chip {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 999px;
    background: rgba(255,255,255,0.02);
    color: black;
    margin-left: auto;
    border: 1px solid rgba(255,255,255,0.02);
}

/* ===== VARIANTS ===== */

/* Blue / Sync style */
.action-sync {
    background: linear-gradient(90deg, rgba(0,40,46,0.34), rgba(1,22,26,0.36));
    color: #dffafa;
    border: 1px solid rgba(0,188,212,0.10);
}

.action-sync .btn-icon {
    background: linear-gradient(180deg,#037a74,#054b4a);
    color: #e6fbf9;
}

/* Yellow / Refresh style */
.action-refresh {
    background: linear-gradient(90deg, rgba(60,40,0,0.22), rgba(30,18,0,0.24));
    color: #fff4d9;
    border: 1px solid rgba(255,170,0,0.06);
}

.action-refresh .btn-icon {
    background: linear-gradient(180deg,#8a5b1a,#623900);
    color: #fff4d9;
}

/* Hover / Active */
.action-btn:hover {
    transform: translateY(-6px);
    filter: brightness(1.06);
    box-shadow: 0 20px 48px rgba(2,6,23,0.6);
}

.action-btn:active {
    transform: translateY(-2px) scale(.995);
}

/* Optional pulse animation */
@keyframes pulseGlow {
    0% {
        box-shadow: 0 8px 28px rgba(0,188,212,0.06);
        transform: translateY(0);
    }
    50% {
        box-shadow: 0 22px 48px rgba(0,188,212,0.12);
        transform: translateY(-3px);
    }
    100% {
        box-shadow: 0 8px 28px rgba(0,188,212,0.06);
        transform: translateY(0);
    }
}

.action-sync.syncing {
    animation: pulseGlow 1.8s ease-in-out infinite;
}




    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- SINGLE LOADER (GIF + spinner + text) -->
    <div id="loading">
        <div class="loader-content">
            <img id="loading-image" src="{{ asset('resources/assets/dms/dist/img/loading.gif') }}" alt="Loading...">
            <div class="loader-spinner"></div>
            <div class="loader-text">Loading, please wait...</div>
        </div>
    </div>

    <div class="wrapper">

        <!-- top nav part================================ -->
        @include('layouts.includes.topnav_admin')
        <!-- top nav part================================ -->

        <!-- content part================================ -->
        @yield('content')
        <!-- content part================================ -->

        <!-- footer part================================ -->
        <footer class="main-footer">
            <!-- Optional footer content -->
        </footer>

        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- Core plugin JavaScript-->

    <!-- jQuery 3 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <!-- DataTables -->
    {{-- <script src="{{asset('resources/assets/dms/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/js/dataTables.bootstrap.min.js"></script>
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- SlimScroll -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="{{asset('resources/assets/dms/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('resources/assets/dms/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('resources/assets/dms/dist/js/demo.js') }}"></script>

    <!-- DataTables JS + buttons -->
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
    <script src="{{asset('resources/assets/dms/dist/print/excellentexport.js') }}"></script>

    <script>
        // Hide loader when window fully loaded
        $(window).on('load', function () {
            $('#loading').fadeOut(200);
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                fixedHeader: true,
                scrollX:true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, ':visible' ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 5 ]
                        }
                    },
                    'colvis'
                ],
                initComplete: function(){
                    $("#example").show();
                    $("#loading").hide(); // use same global loader
                }
            });
        });
    </script>

    <script>
        $(function () {
            $('#example1').DataTable();
            $('#example2').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : false
            });
            $('#example3').DataTable({
                "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]],
            });
        });
    </script>

    <script>
        $(function () {
            $('.select2').select2();
            $('.select3').select2();
            $('.select4').select2();
            $('.select5').select2();

            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#datepicker1').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#datepicker2').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#datepicker3').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#datepicker4').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });
    </script>

    {{-- <script>
    $(function () {

        $('.select2, .select3, .select4, .select5').select2();

        const today = new Date();

        $('#datepicker, #datepicker1, #datepicker2, #datepicker3, #datepicker4')
            .datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            })
            .on('show', function () {

                const $input = $(this);
                const currentVal = $input.val();

                // If input already has a date → let datepicker handle it
                if (currentVal) {
                    return;
                }

                // If empty → highlight today without filling input
                $input.datepicker('setDate', today);
                $input.val('');
            });
    });
</script> --}}


    <script>
        /** add active class and stay opened when selected */
        var url = window.location;

        // for sidebar menu entirely but not cover treeview
        $('ul.sidebar-menu a').filter(function () {
            return this.href == url;
        }).parent().addClass('active');

        // for treeview
        $('ul.treeview-menu a').filter(function () {
            return this.href == url;
        }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
    </script>

    @stack('scripts')
</body>

</html>
