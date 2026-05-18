@extends('layouts.master_admin')

@section('title')
  {{"DMS :: Factory Stock"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        PS Report
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> REPORTS</a></li>
        <li class="active"><a href="{{ route('admin.dateWisePurchaseSaleReport') }}">PS Report</a></li>
      </ol>
    </section>

 
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Search PS Report</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('admin.dateWisePurchaseSaleReport.store') }}" autocomplete="on" enctype="multipart/form-data">
<div class="box-body">
    @if(count($errors))
      <div class="alert alert-danger alert-dismissible">
        <strong>Whoops!</strong> There were some problems with your input.
        <br/>
        <ul>
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(Session::has('success'))
      

      <div class="alert alert-success alert-dismissible fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> {{Session::get('success')}}
      </div>

    @endif
</div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
{{-- for for displaying success and errror message --}}
                <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="fdate" placeholder="DD/MM/YYYY" type="text" class="form-control pull-right" id="datepicker"  required="required" autocomplete="off">
                    </div>
                  </div>

                  <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="todate" placeholder="DD/MM/YYYY" type="text" class="form-control pull-right" id="datepicker1"  required="required" autocomplete="off">
                    </div>
                  </div>

                </div>

                <div class="form-group">
                 

                  <label class="col-sm-2 control-label"></label>
                  <div class="col-sm-5">
                    <div class="input-group">
                      <label class="radio-inline"><input type="radio" value="purchase" name="ps_status">Primary Sale</label>
                      <label class="radio-inline"><input type="radio" name="sales">Secondary Sale</label>
                    </div>
                  </div>



                </div>
                
                </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">Submit</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <!-- <h3 class="box-title">Stock List</h3> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
<!-- //======================= forFirst Tabel ====================== -->

<a style="float:right;" download="Care_PS_REPORT_<?php echo date('Y_m_d');?>.xls" href="#" onclick="return ExcellentExport.excel(this, 'testTable', 'Care_PSI_DAILY_PURCHASE');"><input style="width:120px; float:right; font-size:12px;" type="button" value="Export to Excel"></a>
</p>


<table id="testTable" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
             <th style="text-align:center;" colspan="102"> PS Report (All LD)  </th>
            </tr>
            <tr>
              <th colspan="102">Date : {{$from_date}} - {{$to_date}}  </th>
            </tr>


            <tr>
             <th rowspan="2">Dist name</th>
             <th rowspan="2">DID</th>
              <th rowspan="2"></th>
              <th rowspan="2">Total</th>                
              <th colspan="5">SKU</th>
            </tr>

            <tr>

@foreach ($products as $product)
<td>
  {{$product['product']}}
</td>
@endforeach

            </tr> 

         </thead> 
         <tbody>
<!-- //======================= forFirst Tabel ====================== -->



@foreach ($finaldatas as $finaldata)
<tr>
      <td> {{$finaldata['distributor_name']}}</td>
      <td>{{$finaldata['duid']}} </td>

      <td>{{$finaldata['PSStatus']}}</td>
<td> {{$finaldata['pro_qty_sum']}} </td> 
@foreach ($finaldata['pro_qty'] as $pro_qty)

    
    <td>  
{{$pro_qty}}

    </td>  
  @endforeach  
      
                        
 </tr>               
       

@endforeach

           


</tbody>

</table>

            </div>
            <div class="clear"></div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        </section>
 
 
  </div>
<!-- content part================================ -->
@endsection