@extends('layouts.master_admin')

@section('title', 'Sales Automation Process :: VAT Report')

@section('content')

<div class="content-wrapper">

    {{-- Breadcrumb --}}

    <section class="content">
        <section class="col-lg-12 connectedSortable">

            <div class="box box-warning">

                {{-- Header --}}
                <div class="box-header with-border">
                    <h3 class="box-title text-danger">
                        <i class="fa fa-database"></i> VAT Report
                    </h3>
                </div>

                <div class="box-body">

                    {{-- ================= CURRENT MONTH EXPORT ================= --}}
                    <div class="stats-section">

                        <h4 class="text-info" style="margin-bottom: 14px;">
                            <i class="fa fa-calendar"></i> Quick Export
                        </h4>

                        <div class="admin-action-row">
                            <a target="_blank"
                               href="{{ route('admin.currentMonthVatReportDownload') }}"
                               class="action-btn action-sync">

                                <span class="btn-icon">
                                    <i class="fa fa-download"></i>
                                </span>

                                <span class="btn-text">
                                    <?php $currentMonth = date('F\'y'); echo $currentMonth; ?> VAT Report
                                </span>

                                <span class="action-chip">
                                    Excel
                                </span>
                            </a>
                        </div>

                        <div class="admin-action-row" style="margin-top: 20px">
                            <a target="_blank"
                               href="{{ route('admin.vatReportDownload') }}"
                               class="action-btn action-sync">

                                <span class="btn-icon">
                                    <i class="fa fa-download"></i>
                                </span>

                                <span class="btn-text">
                                    Total VAT Report
                                </span>

                                <span class="action-chip">
                                    Excel
                                </span>
                            </a>
                        </div>

                    </div>

                    {{-- ================= DATE RANGE EXPORT ================= --}}
                    <div class="stats-section" style="margin-top: 24px;">

                        <h4 class="text-warning" style="margin-bottom: 14px;">
                            <i class="fa fa-filter"></i> Export by Date Range
                        </h4>

                        <div class="row">
                            <div class="col-lg-6">

                                <form action="{{ route('admin.vatReportStore') }}"
                                      method="POST"
                                      style="max-width: 420px;">

                                    @csrf

                                    {{-- From Date --}}
                                    <div class="form-group">
                                        <label for="fdate" class="control-label">From Date</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="fdate"
                                                   placeholder="YYYY-MM-DD"
                                                   type="text"
                                                   class="form-control pull-right"
                                                   id="datepicker3"
                                                   autocomplete="off">
                                        </div>
                                    </div>

                                    {{-- To Date --}}
                                    <div class="form-group">
                                        <label for="todate" class="control-label">To Date</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="todate"
                                                   placeholder="YYYY-MM-DD"
                                                   type="text"
                                                   class="form-control pull-right"
                                                   id="datepicker4"
                                                   autocomplete="off">
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="admin-action-row">
                                        <button type="submit" class="action-btn action-sync">
                                            <span class="btn-icon">
                                                <i class="fa fa-cloud-download"></i>
                                            </span>
                                            <span class="btn-text">
                                                Download VAT Report
                                            </span>
                                            <span class="action-chip">
                                                Submit
                                            </span>
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>

                    </div>

                </div>
                {{-- /.box-body --}}

            </div>
            {{-- /.box --}}

        </section>
    </section>

</div>

@endsection
