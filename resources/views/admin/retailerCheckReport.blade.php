@extends('layouts.master_admin')

@section('title', 'Sales Automation Process :: Retailer Mapping Report')

@section('content')

<div class="content-wrapper">

    {{-- Breadcrumb --}}

    <section class="content">
        <section class="col-lg-12 connectedSortable">

            <div class="box box-warning">

                {{-- Header --}}
                <div class="box-header with-border">
                    <h3 class="box-title text-danger">
                        <i class="fa fa-database"></i> Retailer Mapping Report
                    </h3>
                </div>

                <div class="box-body">

                    

                    {{-- ================= DATE RANGE EXPORT ================= --}}
                    <div class="stats-section" style="margin-top: 24px;">

                        <h4 class="text-warning" style="margin-bottom: 14px;">
                            <i class="fa fa-filter"></i> Export by Distributor
                        </h4>

                        <div class="row">
                            <div class="col-lg-6">

                                <form action="{{ route('admin.retailerCheckReport.store') }}"
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
