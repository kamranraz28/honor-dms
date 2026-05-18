<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>@yield('title', 'Sales Automation Process')</title>

  <!-- Table with csv,pdf,print -->
  <link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" rel="stylesheet">

  @if (@$_SESSION['favicon'])
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/app/' . $_SESSION['favicon']) }}">
  @else
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('resources/assets/dms/dist/img/favicon.ico') }}">
  @endif

  <!-- Bootstrap / Select2 / FontAwesome / Semantic UI (for datatables) -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/bower_components/Ionicons/css/ionicons.min.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.semanticui.min.css">

  <!-- Datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/dist/css/skins/_all-skins.min.css') }}">

  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/plugins/iCheck/square/blue.css') }}">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('public/css/customskl.css') }}">

  @stack('customheader')

  <!-- Charts loader -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <!-- jQuery (core) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <style>
    /* small helpers */
    #example { display: none; }

    /* =========================
       SINGLE GLOBAL LOADER
       ========================= */
    #loading {
      position: fixed;
      inset: 0;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(15, 23, 42, 0.85);
      z-index: 9999;
      backdrop-filter: blur(3px);
    }

    .loader-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
    }

    #loading-image {
      width: 72px;
      filter: drop-shadow(0 8px 16px rgba(0,0,0,0.35));
    }

    .loader-spinner {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      border: 4px solid rgba(148, 163, 184, 0.35);
      border-top-color: #3b82f6;
      animation: spin 1s linear infinite, glow 1.8s ease-in-out infinite;
    }

    .loader-text {
      color: #e5e7eb;
      font-size: 14px;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      font-weight: 600;
      text-shadow: 0 2px 8px rgba(0,0,0,0.35);
    }

    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes glow {
      0% { box-shadow: 0 0 0 0 rgba(59,130,246,0.8); }
      70% { box-shadow: 0 0 0 12px rgba(59,130,246,0); }
      100% { box-shadow: 0 0 0 0 rgba(59,130,246,0); }
    }

    /* THEME TWEAKS (modern look) */
    body { background: #f3f4f6 !important; font-family: 'Source Sans Pro', sans-serif !important; }
    * { transition: all 0.22s ease; }

    .main-header { background: linear-gradient(135deg,#0f172a,#1e293b) !important; box-shadow: 0 6px 30px rgba(0,0,0,0.25); }
    .main-header .navbar { background: transparent !important; border: none !important; }

    .main-header .logo img { height: 52px; object-fit: contain; filter: drop-shadow(0 5px 12px rgba(0,0,0,0.5)); }
    .main-header .logo img:hover { transform: translateY(-2px) scale(1.04); }

    .main-sidebar { background: linear-gradient(180deg,#0f172a,#111827 50%,#1f2937) !important; border-right: 1px solid rgba(255,255,255,0.06); box-shadow: 4px 0 20px rgba(0,0,0,0.22); }
    .sidebar-menu>li>a { color: #e5e7eb !important; padding: 12px 16px; margin: 6px 12px; border-radius: 10px; display:flex; align-items:center; gap:10px; }

    .sidebar-menu>li>a:hover, .sidebar-menu>li.active>a { background: linear-gradient(135deg,#2563eb,#1d4ed8); box-shadow: 0 8px 20px rgba(37,99,235,0.25); color:#fff !important; transform: translateY(-1px); }

    table.dataTable { background: #fff !important; border-radius: 12px; overflow: hidden; box-shadow: 0 12px 30px rgba(0,0,0,0.06); }
    .dataTables_wrapper .dataTables_filter input { border-radius: 999px; padding: 9px 12px; border: 1px solid #cbd5e1; }

    .dataTables_wrapper .dt-buttons button { border-radius: 999px !important; padding: 8px 16px !important; background: #2563eb !important; border: none !important; color: white !important; box-shadow: 0 4px 12px rgba(37,99,235,0.25); }
    .dataTables_wrapper .dt-buttons button:hover { background: #1d4ed8 !important; }

    .form-control { border-radius: 10px !important; border: 1px solid #cbd5e1 !important; box-shadow: none !important; }
    .form-control:focus { border-color: #3b82f6 !important; box-shadow: 0 0 0 3px rgba(59,130,246,0.12) !important; }

    .btn { border-radius: 10px !important; padding: 7px 14px; }
    .btn-info { background: linear-gradient(135deg,#0ea5e9,#0284c7) !important; border: none !important; box-shadow: 0 6px 18px rgba(2,132,199,0.2); }
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <!-- GLOBAL LOADER -->
  <div id="loading">
    <div class="loader-content">
      <img id="loading-image" src="{{ asset('resources/assets/dms/dist/img/loading.gif') }}" alt="Loading...">
      <div class="loader-spinner"></div>
      <div class="loader-text">Loading, please wait...</div>
    </div>
  </div>

  <div class="wrapper">
    <!-- top nav part -->
    @include('layouts.includes.topnav_service_management')
    <!-- content -->
    @yield('content')

    <!-- password-change modal (kept as-is) -->
    <div class="modal fade" id="{{'userPasswordChangeModal'}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
            <form action="{{ route('admin.user.updatePassword') }}" method="post" autocomplete="on" enctype="multipart/form-data">
              <h3 class="text-info">Do You Want To Update Password ?</h3>
              <br>

              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input name="_method" type="hidden" value="put">
              <input type="hidden" name="id" value="{{ Auth::id() }}">

              <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                  <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" value="{{ old('password') }}" required>
                  <span class="text-danger">{{ $errors->first('password') }}</span>
                </div>
              </div>

              <br><br>

              <div class="form-group {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                <label class="col-sm-2 control-label">Confirm Password</label>
                <div class="col-sm-10">
                  <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Enter Confirm Password" value="{{ old('confirm_password') }}" required>
                  <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <button class="form-control btn btn-warning">Update Password</button>
                </div>
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <!-- footer -->
    <footer class="main-footer"></footer>
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->

  <!-- Core scripts (local first with CDN backups) -->
  <script src="{{ asset('resources/assets/dms/bower_components/jquery/dist/jquery.min.js') }}"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
  <script src="{{ asset('resources/assets/dms/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('resources/assets/dms/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="{{ asset('resources/assets/dms/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
  <script src="{{ asset('resources/assets/dms/bower_components/fastclick/lib/fastclick.js') }}"></script>
  <script src="{{ asset('resources/assets/dms/dist/js/adminlte.min.js') }}"></script>
  <script src="{{ asset('resources/assets/dms/dist/js/demo.js') }}"></script>

  <!-- DataTables buttons (CDN) -->
  <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
  <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
  <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
  <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
  <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
  <script src="{{ asset('resources/assets/dms/dist/print/excellentexport.js') }}"></script>

  <script>
    // Hide loader after window fully loaded
    $(window).on('load', function () {
      $('#loading').fadeOut(180);
    });

    // DataTable - default table #example
    $(document).ready(function() {
      var table = $('#example').DataTable({
        scrollY: '300',
        scrollX: true,
        fixedColumns: true,
        fixedHeader: true,
        "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]],
        dom: 'Bfrtip',
        buttons: [
          { extend: 'copyHtml5', exportOptions: { columns: [0, ':visible'] } },
          { extend: 'excelHtml5', exportOptions: { columns: ':visible' } },
          { extend: 'csvHtml5', exportOptions: { columns: ':visible' } },
          { extend: 'pdfHtml5', exportOptions: { columns: [0,1,2,5] } },
          'colvis'
        ],
        "order": [[0, "asc"]],
        "columnDefs": [{ "visible": true, "targets": -1 }],
        initComplete: function() {
          $("#example").show();
          $("#loading").hide();
        }
      });

      // secondary table example5 (if used)
      if ($('#example5').length) {
        $('#example5').DataTable({
          scrollX: true,
          fixedColumns: true,
          fixedHeader: true,
          "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]],
          dom: 'Bfrtip',
          buttons: [
            { extend: 'copyHtml5', exportOptions: { columns: [0, ':visible'] } },
            { extend: 'excelHtml5', exportOptions: { columns: ':visible' } },
            { extend: 'csvHtml5', exportOptions: { columns: ':visible' } },
            { extend: 'pdfHtml5', exportOptions: { columns: [0,1,2,5] } },
            'colvis'
          ]
        });
      }
    });

    // initialize smaller tables / selects / datepickers
    $(function () {
      $('.select2').select2();
      $('.select3').select2();
      $('.select4').select2();
      $('.select5').select2();

      $('#datepicker, #datepicker1, #datepicker2, #datepicker3, #datepicker4').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
      });
    });

    // sidebar active state
    (function () {
      var url = window.location;
      $('ul.sidebar-menu a').filter(function () { return this.href == url; }).parent().addClass('active');
      $('ul.treeview-menu a').filter(function () { return this.href == url; }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
    })();

    // optional: simple dropdown auto-submit (kept from original)
    var dropdown = document.getElementById("dropdown");
    if(dropdown) {
      dropdown.addEventListener("change", function () {
        var f = document.getElementById("myForm");
        if(f) f.submit();
      });
    }
  </script>

  @stack('scripts')
</body>

</html>
