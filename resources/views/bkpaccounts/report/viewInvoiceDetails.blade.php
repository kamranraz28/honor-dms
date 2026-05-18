@extends('layouts.master_accounts')

@section('title')
  {{"DMS :: View Invoice Details"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Invoice Details
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('accounts.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> ACCOUNTS</a></li>
        <li class="active"><a href="{{ route('accounts.viewInvoiceDetails') }}">View Invoice Details</a></li>
      </ol>
    </section>

  

    <!-- Main content -->
    <section class="content-header">
      
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Invoice List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Invoice No.</th>
                  <th>Zone</th>
                  <th>Distributor Name</th>
                  <th>Contact No.</th>
                  <th>Date</th>
                  <th>Total Value</th>
                  <th>View Invoice</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>5897</td>
                  <td>Dhaka </td>
                  <td>Mr. XXXX</td>
                  <td>X123456789</td>
                  <td>15-05-2018</td>
                  <td>80000 Tk.</td>
                  <td><a href="invoice.html"><b>View Invoice</b></a></td>
                  <td><a href="" class=" btn btn-warning btn-md">Edit</a> <a href="" class=" btn btn-danger btn-md">Delete</a></td>
                </tr>
                <tr>
                  <td>5898</td>
                  <td>Dhaka </td>
                  <td>Mr. XXYY</td>
                  <td>X123456789</td>
                  <td>15-05-2018</td>
                  <td>40000 Tk.</td>
                  <td><a href="invoice.html"><b>View Invoice</b></a></td>
                  <td><a href="" class=" btn btn-warning btn-md">Edit</a> <a href="" class=" btn btn-danger btn-md">Delete</a></td>
                </tr>
                <tr>
                  <td>5899</td>
                  <td>Dhaka </td>
                  <td>Mr. YYXX</td>
                  <td>X123456789</td>
                  <td>15-05-2018</td>
                  <td>100000 Tk.</td>
                  <td><a href="invoice.html"><b>View Invoice</b></a></td>
                  <td><a href="" class=" btn btn-warning btn-md">Edit</a> <a href="" class=" btn btn-danger btn-md">Delete</a></td>
                </tr>
                <tr>
                  <td>5900</td>
                  <td>Dhaka </td>
                  <td>Mr. ZZXX</td>
                  <td>X123456789</td>
                  <td>15-05-2018</td>
                  <td>55000 Tk.</td>
                  <td><a href="invoice.html"><b>View Invoice</b></a></td>
                  <td><a href="" class=" btn btn-warning btn-md">Edit</a> <a href="" class=" btn btn-danger btn-md">Delete</a></td>
                </tr>
                <tr>
                  <td>5901</td>
                  <td>Dhaka </td>
                  <td>Mr. XXZZ</td>
                  <td>X123456789</td>
                  <td>15-05-2018</td>
                  <td>75000 Tk.</td>
                  <td><a href="invoice.html"><b>View Invoice</b></a></td>
                  <td><a href="" class=" btn btn-warning btn-md">Edit</a> <a href="" class=" btn btn-danger btn-md">Delete</a></td>
                </tr>
                <tr>
                  <td>5902</td>
                  <td>Dhaka </td>
                  <td>Mr. XYYX</td>
                  <td>X123456789</td>
                  <td>15-05-2018</td>
                  <td>45000 Tk.</td>
                  <td><a href="invoice.html"><b>View Invoice</b></a></td>
                  <td><a href="" class=" btn btn-warning btn-md">Edit</a> <a href="" class=" btn btn-danger btn-md">Delete</a></td>
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