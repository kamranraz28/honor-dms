@extends('layouts.master_admin')

@section('title', 'Sales Automation Process :: Distributor Stock Amount')

@section('content')

<div class="content-wrapper">

    {{-- Breadcrumb --}}
    @include('warehouse.bc.bc')

    <section class="content">
        <section class="col-lg-12 connectedSortable">

            <div class="box box-warning">

                {{-- Header --}}
                <div class="box-header with-border">
                    <h3 class="box-title text-danger">
                        <i class="fa fa-database"></i> Distributor Stock Amount
                    </h3>
                </div>

                <div class="box-body">

                    {{-- ================= DATE RANGE EXPORT ================= --}}
                    <div class="stats-section" style="margin-top: 24px;">

                        <h4 class="text-warning" style="margin-bottom: 14px;">
                            <i class="fa fa-filter"></i> Export by Date Range
                        </h4>

                        <form action="{{ route('dailyStockReport.store') }}"
                              method="POST"
                              style="max-width: 420px;">

                            @csrf

                            {{-- Distributor --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="distributor">
                                            Distributor (Leave Blank for All)
                                        </label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>

                                        <input type="text"
               id="distributor_search"
               class="form-control"
               placeholder="Type to Search Distributor..."
               list="distributor_list"
               autocomplete="off">

        <datalist id="distributor_list"></datalist>

        <input type="hidden" name="distributor_id" id="distributor_id">
                                      </div>
                                    </div>
                                </div>
                            </div>

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
                                                   autocomplete="off">
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
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="admin-action-row" style="margin-top: 20px">
                                <button type="submit" class="action-btn action-sync">
                                    <span class="btn-icon">
                                        <i class="fa fa-cloud-download"></i>
                                    </span>
                                    <span class="btn-text">
                                        Download Stock Information
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
