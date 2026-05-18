@extends('layouts.master_admin')

@section('title')
  {{"DMS :: Create Invoice"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Invoice
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> ASO</a></li>
        <li class="active"><a href="{{ route('admin.createInvoice') }}">Create Invoice</a></li>
      </ol>
    </section>

  
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Create Invoice</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" style="padding:20px">
                <div class="box-body">
                  <div class="form-group">
                  <label for="region" class="col-sm-2 control-label">Region</label>
                  <div class="col-sm-10">
                    <select name="zone" class="form-control select2" style="width: 100%;">
                      <option selected="selected">Select Zone</option>
                      <option value="Dhaka">Dhaka</option>
                      <option value="Chittagong">Chittagong</option>
                      <option value="Shylet">Shylet</option>
                      <option value="Rajshahi">Rajshahi</option>
                      <option value="Khulna">Khulna</option>
                      <option value="Rangpur">Rangpur</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="territory" class="col-sm-2 control-label">Territory</label>
                  <div class="col-sm-10">
                    <select name="zone" class="form-control select2" style="width: 100%;">
                      <option selected="selected">Select Territory</option>
                      <option value="Dhaka">Territory 1</option>
                      <option value="Chittagong">Territory 2</option>
                      <option value="Shylet">Territory 3</option>
                      <option value="Rajshahi">Territory 4</option>
                      <option value="Khulna">Territory 5</option>
                      <option value="Rangpur">Territory 6</option>
                    </select>
                  </div>
                </div>
                  <div class="form-group">
                  <label for="Distributor" class="col-sm-2 control-label">Distributor</label>
                  <div class="col-sm-10">
                    <select class="form-control select2" style="width: 100%;">
                      <option selected="selected">Select Distributor</option>
                      <option>Mr. XXX</option>
                      <option>Mr. YYY</option>
                      <option>Mr. BBB</option>
                      <option>Mr. AAA</option>
                      <option>Mr. EEE</option>
                      <option>Mr. EEE</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-10">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="date" placeholder="DD/MM/YYYY" type="text" class="form-control pull-right" id="datepicker">
                    </div>
                  </div>
                </div>
                <hr>
              <!-- /.form group -->
              <div class="form-group">
                  
                  <div class="container1">

                    <div class="col-sm-12">

                      <div class="col-sm-2">
                        <label  class=" control-label">Product Details</label>
                      </div>
                      <div class="col-sm-10">
                        <div class="row">
                          <div class="col-xs-3">
                            <select name="select_products[]" class="form-control">
                              <option selected="selected">Select Product</option>
                              <option value=" Product 1">Product 1</option>
                              <option value="Product 2">Product 2</option>
                              <option value="Product 3">Product 3</option>
                              <option Value="Product 4">Product 4</option>
                              <option value="Product 5">Product 5</option>
                            </select>
                          </div>
                          <div class="col-xs-3">
                            <input type="text" name="quantity[]" class="form-control"  placeholder="Quantity">
                          </div>
                          <div class="col-xs-3">
                            <input type="text" name="price[]" class="form-control"  placeholder="Price">
                          </div>
                          <button  class="add_form_field btn btn-warning btn-round col-sm-3">Add Field &nbsp; <span style="font-size:16px; font-weight:bold;">+ </span></button>
                        </div>

                          <hr>
                    
                      </div>
                    </div>
                  </div>
                  <br>
                </div>
                <hr>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Total</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control"  placeholder="Total">
                  </div>
                </div>
                
                <hr>
                <div class="form-group">
                  <label  class="col-sm-2 control-label">Deposit Amount</label>

                  <div class="col-sm-10">
                    <input type="text" name="deposit_amount" class="form-control"  placeholder="Invoice No.">
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Upload Bank Slip</label>

                  <div class="col-sm-10">
                  
                  <input class="form-control" name="invoice_upload" type="file" id="exampleInputFile">
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
              <h3 class="box-title">Invoice List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Invoice No.</th>
                  <th>Zone</th>
                  <th>Distributor Name</th>
                  <th>Contact No.</th>
                  <th>Date</th>
                  <th>Total Value</th>
                  <th>Deposite Value</th>
                  <th>View Invoice</th>
                   <th>View Deposit Slip</th>
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
                   <td>80000 Tk.</td>
                  <td><a href="invoice.html"><b>View Invoice</b></a></td>
                   <td><a href="invoice.html"><b>View  Deposit Slip</b></a></td>
                  <td><a href="" class=" btn btn-warning btn-md">Edit</a> <a href="" class=" btn btn-danger btn-md">Delete</a></td>
                </tr>
                <tr>
                  <td>5898</td>
                  <td>Dhaka </td>
                  <td>Mr. XXYY</td>
                  <td>X123456789</td>
                  <td>15-05-2018</td>
                   <td>80000 Tk.</td>
                   <td>80000 Tk.</td>
                  <td><a href="invoice.html"><b>View Invoice</b></a></td>
                   <td><a href="invoice.html"><b>View  Deposit Slip</b></a></td>
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