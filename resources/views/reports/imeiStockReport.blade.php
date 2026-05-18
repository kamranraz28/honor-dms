@extends('layouts.master_admin')

@section('title')
  {{ "E-Warranty Ststem :: Daily Stock Report" }}
@endsection

@section('content')

<!-- Content Wrapper -->
<div class="content-wrapper">

    {{-- Breadcrumb --}}
   {{--  @include('admin.bc.bc') --}}

    <!-- Main content -->
    <section class="content">

        <div class="row">

            <section class="col-lg-12 connectedSortable">

                {{-- Errors --}}
                @if(count($errors))
                    <div class="alert alert-danger alert-dismissible">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Success --}}
                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Success!</strong> {{ Session::get('success') }}
                    </div>
                @endif

                <!-- Box -->
                <div class="box box-warning">

                    <div class="box-body">

                        <!-- ===== DASHBOARD STYLE ACTION BUTTON ===== -->
                        <div class="admin-action-row">

                            <a target="_blank"
                               href="{{ route('admin.distributorImeiStockReportDownload') }}"
                               class="action-btn action-sync">

                                <span class="btn-icon">
                                    <i class="fa fa-download"></i>
                                </span>

                                <span class="btn-text">
                                    Distributor IMEI Stock (Excel)
                                </span>

                                <span class="action-chip">
                                    Export
                                </span>

                            </a>

                        </div>
                        <!-- ===== END BUTTON ===== -->
                        <div class="admin-action-row" style="margin-top: 20px">

                            <a target="_blank"
                               href="{{ route('admin.retailerImeiStockReportDownload') }}"
                               class="action-btn action-sync">

                                <span class="btn-icon">
                                    <i class="fa fa-download"></i>
                                </span>

                                <span class="btn-text">
                                    Retailer IMEI Stock (Excel)
                                </span>

                                <span class="action-chip">
                                    Export
                                </span>

                            </a>

                        </div>

                    </div>
                </div>
                <!-- /.box -->

            </section>

        </div>

    </section>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->

@endsection
