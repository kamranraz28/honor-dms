@extends('layouts.master_admin')

@section('title')
  {{"DMS :: Distributor Invoice History"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Distributor Invoice History
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> REPORTS</a></li>
        <li class="active"><a href="{{ route('admin.distributorInvoiceHistory') }}">Distributor Invoice History</a></li>
      </ol>
    </section>


    <!-- Main content -->
    <section class="content-header">
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Distributor Details </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Invoice No.</th>
                    <th>Total Order Value</th>
                   <th>Total Deposit Value</th>
                   <th>Final Order Value</th>
                  <th>Balance</th>
                  <th>View Invoice</th>
                  <th>View Factory Invoice</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>5780</td>
                 <td>250000</td>
                  <td>250000</td>
                  <td>220000</td>
                  <td>30000</td>
                  <td><a href="invoice.html" class=" btn btn-success btn-md">View Invoice</a></td>
                  <td><a href="invoice.html" class=" btn btn-success btn-md">Factory Invoice</a></td>
                </tr>
                <tr>
                  <td>5780</td>
                 <td>250000</td>
                  <td>250000</td>
                  <td>220000</td>
                  <td>30000</td>
                  <td><a href="invoice.html" class=" btn btn-success btn-md">View Invoice</a></td>
                  <td><a href="invoice.html" class=" btn btn-success btn-md">Factory Invoice</a></td>
                </tr>
                <tr>
                  <td>5780</td>
                <td>250000</td>
                  <td>250000</td>
                  <td>220000</td>
                  <td>30000</td>
                  <td><a href="invoice.html" class=" btn btn-success btn-md">View Invoice</a></td>
                  <td><a href="invoice.html" class=" btn btn-success btn-md">Factory Invoice</a></td>
                </tr>
                <tr>
                  <td>5780</td>
                 <td>250000</td>
                  <td>250000</td>
                  <td>220000</td>
                  <td>30000</td>
                  <td><a href="invoice.html" class=" btn btn-success btn-md">View Invoice</a></td>
                  <td><a href="invoice.html" class=" btn btn-success btn-md">Factory Invoice</a></td>
                </tr>
                
              
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