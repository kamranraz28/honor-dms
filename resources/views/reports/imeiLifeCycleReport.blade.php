@extends('layouts.master_admin')

@section('title')
  {{"Sales Automation Process :: IMEI Life Cycle Report"}}
@endsection

@section('content')

<div class="content-wrapper">
    <section class="content">
        <div class="row">

            <section class="col-lg-12 connectedSortable">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title text-danger">IMEI Life Cycle Report</h3>
                    </div>

                    <div class="box-body">

                        {{-- ================= CSV UPLOAD ================= --}}
                        <form class="form-horizontal" method="POST" action="{{ route('admin.dailyimeivReport.download') }}" enctype="multipart/form-data" autocomplete="off">
                            @csrf

                            <div class="form-group">
                                <label for="csv_file" class="col-md-3 control-label">Check IMEI Life Cycle Report by CSV</label>
                                <div class="col-md-6">
                                    <input id="csv_file" type="file" class="form-control" name="csv_file" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <div class="admin-action-row">
                                        <button type="submit" class="action-btn action-sync">
                                            <span class="btn-icon">
                                                <i class="fa fa-cloud-download"></i>
                                            </span>
                                            <span class="btn-text">Download Report</span>
                                            <span class="action-chip">Excel</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>

                        <hr>

                        {{-- ================= SINGLE IMEI ================= --}}
                        <form class="form-horizontal" method="POST" action="{{ route('singleIMEI') }}">
                            @csrf

                            <div class="form-group">
                                <label for="imei" class="col-md-3 control-label">Check IMEI Life Cycle Report by Single IMEI</label>
                                <div class="col-md-6">
                                    <input id="imei" class="form-control" type="text" name="imei" placeholder="Enter IMEI" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <div class="admin-action-row">
                                        <button type="submit" class="action-btn action-sync">
                                            <span class="btn-icon">
                                                <i class="fa fa-cloud-download"></i>
                                            </span>
                                            <span class="btn-text">Download Report</span>
                                            <span class="action-chip">Excel</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </section>

        </div>
    </section>
</div>

@endsection
