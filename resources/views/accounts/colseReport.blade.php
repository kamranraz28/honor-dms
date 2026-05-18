@extends('layouts.master_accounts')

@section('title')
{{ 'E-Warranty Ststem :: Dashboard' }}
@endsection

@section('content')
<!-- content part================================ -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
    @include('accounts.bc.bc')
    <!-- bc part================================ -->

    <!-- Main content -->
    <section class="content">
        <div class="row new-box">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <h4 class="orader">
                                {{ __('Closed Order Report') }}
                            </h4>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif

                    <div class="box-body">
                        <form action="{{route('accounts.closeReportStore')}}" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="fdate" class="control-label">From Date</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="fdate" placeholder="YYYY-MM-DD"
                                                value="{{ @$retVal = (Session::get('fdate')) ? $ssdata['fdate'] : "" }}"
                                                type="text" class="form-control pull-right" id="datepicker3"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="todate" class="control-label">To Date</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="todate" placeholder="YYYY-MM-DD"
                                                value="{{ @$retVal = (Session::get('todate')) ? $ssdata['todate'] : "" }}"
                                                type="text" class="form-control pull-right" id="datepicker4"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" cellspacing="0" width="100%">
                                <thead class="thead">
                                    <tr>
                                        <th>SN</th>

                                        <th>Order Number</th>
                                        <th>Quantity</th>
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
            @foreach ($orderspostings as $ordersposting)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('accounts.searchOrder', ['order_number' => $ordersposting->order_number]) }}">
                        {{ $ordersposting->order_number ?? '-' }}
                    </a>
                </td>
                <td>{{ $ordersposting->total_quantity }}</td>
            </tr>
            @endforeach
        </tbody>

                            </table>
                        </div>
                    </div>
                </div>
               {{ $orderspostings->links() }}
            </div>
        </div>
</div>
</div>
@endsection

@push('scripts')
<script>
    var dropdown = document.getElementById("dropdown");
    dropdown.addEventListener("change", function () {
        document.getElementById("myForm").submit();
    });
</script>
<script>
function con() {
 return  confirm("Do You Want to Restore the data?");
}
</script>

@endpush