@extends('layouts.master_warehouse')

@section('title', 'Sales Automation Process :: Stock Receive Report')

@section('content')

<div class="content-wrapper">


    <section class="content">
        <section class="col-lg-12 connectedSortable">

            <div class="box box-warning">

                {{-- Header --}}
                <div class="box-header with-border">
                    <h3 class="box-title text-danger">
                        <i class="fa fa-database"></i> Stock Receive Report
                    </h3>
                </div>

                <div class="box-body">

                    {{-- ================= DATE RANGE EXPORT ================= --}}
                    <div class="stats-section" style="margin-top: 24px;">

                        <h4 class="text-warning" style="margin-bottom: 14px;">
                            <i class="fa fa-filter"></i> Export by Date Range
                        </h4>

                        <form action="{{ route('stockReceiveReportStore') }}"
                              method="POST"
                              style="max-width: 420px;">

                            @csrf



                            {{-- From Date --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">
                                            From Date
                                        </label>

                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                            <input name="fdate"
                                                   placeholder="YYYY-MM-DD"
                                                   value="{{ Session::get('fdate') ? $ssdata['fdate'] ?? '' : '' }}"
                                                   type="text"
                                                   class="form-control pull-right"
                                                   id="datepicker3"
                                                   autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- To Date --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">
                                            To Date
                                        </label>

                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                            <input name="todate"
                                                   placeholder="YYYY-MM-DD"
                                                   value="{{ Session::get('todate') ? $ssdata['todate'] ?? '' : '' }}"
                                                   type="text"
                                                   class="form-control pull-right"
                                                   id="datepicker4"
                                                   autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="admin-action-row" style="margin-top: 10px">
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
                {{-- /.box-body --}}

            </div>
            {{-- /.box --}}

        </section>
    </section>

</div>



@endsection
