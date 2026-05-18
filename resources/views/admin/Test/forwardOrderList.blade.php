@extends('layouts.master_admin')

@section('title')
  {{"DMS :: Forward Order List"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Forward Order List
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> ACCOUNTS</a></li>
        <li class="active"><a href="{{ route('admin.forwardOrderList') }}">Forward Order List</a></li>
      </ol>
    </section>

  


    <!-- Main content -->
    <section class="content-header">
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Order List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Invoice No.</th>
                  <th>Date</th>
                  <th>Dealer Name</th>
                  <th>Order Value</th>
                   <th>Deposit  Value</th>
                  <th>View Invoice</th>
                   <th>View Deposit Slip</th>
                  <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>5586</td>
                  <td>12-05-2018</td>
                  <td>Mr. XXXX</td>
                  <td>45000 Tk.</td>
                   <td>40000 Tk.</td>
                  <td> <a href="#"><b>View Invoice</b></a> </td>
                  <td> <a href="#"><b>View Invoice</b></a> </td>
                  <td> <button  class=" btn btn-warning btn-md">Approved By Sales</button> </td>
                </tr>
                <tr>
                  <td>5587</td>
                  <td>12-05-2018</td>
                  <td>Mr.YYYY</td>
                   <td>45000 Tk.</td>
                   <td>40000 Tk.</td>
                  <td> <a href="#"><b>View Invoice</b></a> </td>
                  <td> <a href="#"><b>View Invoice</b></a> </td>
                  <td> <button  class=" btn btn-success btn-md">Approved By Accounts</button> </td>
                </tr>
               
              
                </tbody>
               
              </table>
            </div>
            <div class="clear"></div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </section>
    






 
  </div>
<!-- content part================================ -->
@endsection