@extends('layouts.master_admin')

@section('title')
  {{"Sales Automation Process :: Upload File"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- bc part================================ -->
      @include('admin.bc.bc')
    <!-- bc part================================ -->



    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">

      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Add Upload</h3>
            </div>


    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('admin.upload1.store') }}" autocomplete="on" enctype="multipart/form-data">
<div class="box-body">
    @if(count($errors))
      <div class="alert alert-danger alert-dismissible">
        <strong>Whoops!</strong> There were some problems with your input.
        <br/>
        <ul>
          @foreach($errors->all() as $error)
          <li>{!! $error !!}</li>
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


      @if (session('csv_errors'))
      <div class="alert alert-danger">
          <ul>
              @foreach (session('csv_errors') as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

</div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
{{-- for for displaying success and errror message --}}

      <div class="box-body">

        <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
            <label for="csv_file" class="col-md-2 control-label">CSV file to import</label>

            <div class="col-md-8">
                <input id="csv_file" type="file" class="form-control" name="csv_file" required>

                @if ($errors->has('csv_file'))
                    <span class="help-block">
                    <strong>{{ $errors->first('csv_file') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
                <div class="form-group1">
                  <label for="type">Select Upload Type :</label>
                  <select name="type" class="form-control" id="type" required="required">
                    <option>Select Type</option>
                    <option value="1">Brand - 01 [CSV- Name]</option>
                    <option value="2">Category - 02 [CSV- Name]</option>
                    <option value="3">Product/Model - 03 [CSV- Name, Model, Code, Color, Brand, Category, Details, Chalan Type, Price]</option>
                    <option value="5">Stock/Product Add - 05 [CSV- Product Name, Product Color, IMEI-1, IMEI-2, Warranty Period]</option>
                    <option value="6">Retailer Upload - 06 [CSV- Retailer Code, Name, Phone, Contact Name, E-mail, District, Upazila, Address, Store Type, Market Name]</option>
                    <option value="61">Distributor Upload - 13 [CSV- Distributor Code, Distributor Name, Contact Name, E-mail, District, Upazila, Address, Category]</option>
                    <option value="7">Tertiary Sales Upload - 07 [CSV- Retailer Code, IMEI-1, IMEI-2, Customer Mobile]</option>
                     <option value="9">Distributor Retail Mapping - 09 [CSV- Distributor Code, Retailer Code]</option>
                     <option value="10">Primary Sales Upload- 10 [CSV- Distributor Code, IMEI-1, Date]</option>

                     <!-- <option value="101">Remaining IMEI Upload in Order Table (Special Case)- 101 (IN CSV 1st Column Distributor ID, 2nd Column IMEI 1, 3rd Column Order Number )</option> -->

                    {{--  <option value="11">I-Retailer Upload - 11</option> --}}
                     <option value="12">Secoondery Sales Upload - 12 [CSV- Retailer Code, IMEI-1, Date]</option>
                     {{-- <option value="13">Delete I-retailer Upload - 13</option> --}}

                     <option value="14">Distributor IMEI Transfer - 14 [CSV- New Distributor Code, IMEI-1]</option>
                    <option value="15">Retailer Delete Permanently - 15 [CSV- Retailer Code]</option>
                     <option value="100">Retailer IMEI Transfer - 100 [CSV- New Retailer Code, IMEI-1]</option>
                     <option value="104">Warranty Period Update - 104 [CSV- IMEI-1, New Warranty Period]</option>
                     <option value="106">Distributor-Retailer Unmapping - 106 [CSV- Distributor Code, Retailer Code]</option>

                      <option value="16">Primary Sales Delete - 16 [CSV- Distributor Code, IMEI-1]</option>
                       <option value="17">Secondary Sales Delete - 17 [CSV- Retailer Code, IMEI-1]</option>
                       <option value="18">Stock Delete - 18 [CSV- IMEI-1]</option>
                       <option value="19">TSO/TSM Upload - 19 [CSV- TSO/TSM Code, TSO/TSM Name, Phone, E-mail, District, Upazila]</option>
                       <option value="21">TSO/TSM-Distributor Mapping - 21 [CSV- Distributor Code, TSO/TSM Code]</option>
                        <option value="22">TSO/TSM-Distributor Un-Mapping - 22 [CSV- Distributor Code, TSO/TSM Code]</option>

                        <option value="23">Tertiarry Delete - 23 [CSV- IMEI-1]</option>
                        <option value="30">Delete Submitted IMEI - 30 [CSV- IMEI-1]</option>
                        <option value="202">Inactive Retail- 202 [CSV- Retailer Code]</option>
                        <option value="204">Replace Request Delete - 204 [CSV- IMEI-1]</option>
                        <option value="205">Stock Update for Specific Date-205 [CSV- IMEI-1, Date]</option>
                        <option value="206">Replaced IMEI Change-206 [CSV- Old IMEI-1, New IMEI-1]</option>
                        <option value="207">Replace IMEI to Receive-207 [CSV- Replace ID]</option>
                        <option value="208">Wrong IMEI Update-208 [CSV- Wrong IMEI-1, Wrong IMEI-2, New IMEI-1, New IMEI-2]</option>

                        <option value="209">Stock Product Change-209 [CSV- IMEI-1, New Product Model]</option>

                       {{--  <option value="42">DATA Correction </option> --}}
                    {{--  <option value="15">Pre Book Retailer Promotion - 15</option> --}}
                  </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
                <button type="submit" class="btn btn-primary">
                    Parse CSV
                </button>
            </div>
        </div>



      </div>

      <!-- <div class="box-footer">
        <button type="submit" class="btn btn-success pull-right">Submit</button>
      </div> -->

  </form>

<!-- ================================== form area==================================== -->



          </div>


        </div>
      </div>



<!-- <div class="row">
    <div class="box box-warning">
    <div class="box-header">
      <h3 class="box-title">Brand List</h3>
    </div>
    <div class="box-body">
      <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
          <th>#</th>
          <th>Brand </th>
          <th>Created Date </th>
          <th>Action</th>
        </tr>
        </thead>
        <tbody>

        </tbody>

      </table>
    </div>
    <div class="clear"></div>
  </div>
</div> -->




    </section>






  </div>
<!-- content part================================ -->


@endsection
