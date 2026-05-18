@extends('layouts.master_admin')

@section('title')
    {{ "Sales Automation Process :: Daily Sales Report" }}
@endsection

@section('content')

    <div class="content-wrapper">
        <section class="content">
            <div class="row">

                {{-- ================= FILTER SECTION ================= --}}
                <section class="col-lg-12 connectedSortable">
                    <div class="box box-warning">

                        <div class="box-header with-border">
                            <h3 class="box-title text-danger">
                                <i class="fa fa-line-chart"></i> Tertiary Sales Report (Sale Out)
                            </h3>
                        </div>

                        <div class="box-body">

                            {{-- Errors --}}
                            @if(count($errors))
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong>
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Success --}}
                            @if(Session::has('success'))
                                <div class="alert alert-success">
                                    <strong>Success!</strong> {{ Session::get('success') }}
                                </div>
                            @endif

                            {{-- FILTER CARD --}}
                            <div class="stats-section">

                                <form class="form-horizontal" method="POST"
                                    action="{{ route('admin.dailySalesReport.store') }}" target="_blank" autocomplete="off">

                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                                    <div class="row">

                                        {{-- Brand --}}
                                        <div class="col-md-12">
                                            <label>Brand (Leave Blank for All)</label>
                                            <input type="text" id="brand_search" class="form-control"
                                                placeholder="Type to Search Brand..." list="brand_list" autocomplete="off">

                                            <datalist id="brand_list"></datalist>

                                            <input type="hidden" name="brand_id" id="brand_id">
                                        </div>

                                        {{-- Serial --}}
                                        <div class="col-md-12" style="margin-top:12px;">
                                            <label>Serial No</label>
                                            <input type="text" name="sno" class="form-control" placeholder="Enter Serial No"
                                                value="{{ @$ssdata['sno'] }}">
                                        </div>

                                        {{-- Dates --}}
                                        <div class="col-md-6" style="margin-top:12px;">
                                            <label>From Date</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                                <input name="fdate" class="form-control" id="datepicker3"
                                                    value="{{ @$ssdata['fdate'] }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6" style="margin-top:12px;">
                                            <label>To Date</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                                <input name="todate" class="form-control" id="datepicker4"
                                                    value="{{ @$ssdata['todate'] }}" required>
                                            </div>
                                        </div>


                                    </div>

                                    {{-- Submit --}}
                                    <div class="admin-action-row" style="margin-top:20px;">
                                        <button type="submit" class="action-btn action-sync">
                                            <span class="btn-icon">
                                                <i class="fa fa-play"></i>
                                            </span>
                                            <span class="btn-text">Go Now</span>
                                            <span class="action-chip">Submit</span>
                                        </button>
                                    </div>

                                </form>
                            </div>

                            {{-- QUICK EXPORTS --}}
                            <div class="stats-section" style="margin-top:24px;">
                                <h4 class="text-info">
                                    <i class="fa fa-download"></i> Quick Exports
                                </h4>

                                <div class="admin-action-row">
                                    <a target="_blank" href="{{ route('currentMonthTerExcel') }}"
                                        class="action-btn action-sync">
                                        <span class="btn-text">Current Month</span>
                                    </a>

                                    <a target="_blank" href="{{ route('lastSixMonthTerExcel') }}"
                                        class="action-btn action-sync">
                                        <span class="btn-text">Last 6 Months</span>
                                    </a>

                                    <a target="_blank" href="{{ route('admin.stock.terexcel') }}"
                                        class="action-btn action-sync">
                                        <span class="btn-text">All Data</span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>



            </div>
        </section>
    </div>



@endsection
