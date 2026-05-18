@extends('layouts.master_admin')

@section('title')
  {{"DMS :: Add Product"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit/Delete Distributor Target
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> SALES</a></li>
        <li class="active"><a href="{{ route('admin.crudDistributorTarget') }}">Edit/Delete Distributor Target</a></li>
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
                      <input name="date" placeholder="DD/MM/YYYY" type="text" class="form-control pull-right" id="monthpicker">
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
              <h3 class="box-title">Invoice List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Region</th>
                  <th>Territory</th>
                  <th>Distributor Name</th>
                  <th>Targeted Time</th>
                  <th>Target Value</th>
                  <th>View Details</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>Dhaka </td>
                  <td>Territory 1</td>
                  <td>Mr. XXXX</td>
                  <td>05-2018</td>
                  <td>80000 Tk.</td>
                  <td><a href="invoice.html"><b>View Details</b></a></td>
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