@extends('layouts.master_admin')

@section('title', 'Sales Automation Process :: Daily Return Report')

@section('content')

<div class="content-wrapper">

    {{-- Breadcrumb --}}

    <section class="content">
        <section class="col-lg-12 connectedSortable">

            <div class="box box-warning">

                {{-- Header --}}
                <div class="box-header with-border">
                    <h3 class="box-title text-danger">
                        <i class="fa fa-database"></i> Daily Return Report
                    </h3>
                </div>

                <div class="box-body">

                    

                    {{-- ================= DATE RANGE EXPORT ================= --}}
                    <div class="stats-section" style="margin-top: 24px;">

                        <h4 class="text-warning" style="margin-bottom: 14px;">
                            <i class="fa fa-filter"></i> Export by Date Range
                        </h4>

                        <div class="row">
                            <div class="col-lg-6">

                                <form action="{{ route('admin.dailyReturnReport.store') }}"
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
                                                Download Report
                                            </span>
                                            <span class="action-chip">
                                                Excel
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
