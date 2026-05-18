@extends('layouts.master_service_management')

@section('title')
  {{"Sales Automation Process :: Upload File"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->



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
  <form class="form-horizontal" method="POST" action="{{ route('serviceManagement.bulkUpload') }}" autocomplete="on" enctype="multipart/form-data">
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
                    <option value="1">Receive Product-1 (1st Col- Replace Id, 2nd Col- Receive Date, 3rd Col- Remarks)</option>
                    <option value="2">Check Product-2 (1st Col- Replace Id, 2nd Col- Receive Date, 3rd Col- Remarks)</option>
                    <option value="3">Cancel Product-3 (1st Col- Replace Id, 2nd Col- Receive Date, 3rd Col- Void, 4th Col- Remarks)</option>
                    <option value="4">Cancel and Deliver Product-4 (1st Col- Replace Id, 2nd Col- Receive Date, 3rd Col- Void, 4th Col- Remarks, 5th Col- Delivery Date)</option>

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
