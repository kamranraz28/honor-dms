@extends('layouts.master_admin')

@section('title')
  {{"Sales Automation Process :: Setting"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Setting
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> Settings</a></li>
        <li class="active"><a href="{{ route('admin.setting') }}">Setting</a></li>
      </ol>
    </section>

  
    <!-- Main content -->
    <section class="content-header">




    <div class="row">
   



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
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Setting List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Action</th>
                  <th>currency</th>
                  <th>code</th>
                  <th>timezone</th>
                  <th>hotline</th>
                  <th>contact</th>
                  <th>vat</th>
                  <th>support email</th>
                  <th>favicon</th>
                  <th>logo</th>
                  
                </tr>
                </thead>
                <tbody>
@foreach ($settings as $element)
     
     
                <tr>
                  


                  <td>
    
  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'settingUpdateModal'. $element['id']}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>


                  </td>


                  <td>{{$element['currency']}}</td>
                  <td>{{$element['code']}}</td>
                  <td>{{$element['timezone']}}</td>
                  <td>{{$element['hotline']}}</td>
                  <td>{{$element['contact']}}</td>
                  <td>{{$element['vat']}}</td>
                  <td>{{$element['semail']}}</td>
                  
<td> 

  @if ($element['favicon'])
  <a target="_blank" href="{{ asset( 'storage/app/d/nokia/' . $element['favicon']) }}">
    <img width="40px" height="20px" src="{{ asset( 'storage/app/d/nokia/' . $element['favicon']) }}"> 
  </a>
  @else
    No Image File
  @endif

</td>       

<td> 

  @if ($element['logo'])
  <a target="_blank" href="{{ asset( 'storage/app/d/nokia/' . $element['logo']) }}">
    <img width="40px" height="20px" src="{{ asset( 'storage/app/d/nokia/' . $element['logo']) }}"> 
  </a>
  @else
    No Image File
  @endif

</td>



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


<!--custom update modal part================================ -->


@forelse ($settings as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'settingUpdateModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['setting']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('admin.setting.update')}}" method="post" enctype="multipart/form-data">
  <h3 class="text-info">Do You Want To Update This Data ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element['id'] }}">

<div class="form-group {{ $errors->has('currency') ? 'has-error' : '' }}">
  <label for="currency">Currency:</label>
  <input type="text" id="currency" name="currency" class="form-control" placeholder="Enter Product Name" value="{{ $element['currency'] }}">
  <span class="text-danger">{{ $errors->first('currency') }}</span>
</div>

<div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
  <label for="code">Code:</label>
  <input type="text" id="code" name="code" class="form-control" placeholder="Enter Product Name" value="{{ $element['code'] }}">
  <span class="text-danger">{{ $errors->first('code') }}</span>
</div>

<div class="form-group {{ $errors->has('timezone') ? 'has-error' : '' }}">
  <label for="timezone">Timezone:</label>
  <input type="text" id="timezone" name="timezone" class="form-control" placeholder="Enter Product Name" value="{{ $element['timezone'] }}">
  <span class="text-danger">{{ $errors->first('timezone') }}</span>
</div>

<div class="form-group {{ $errors->has('hotline') ? 'has-error' : '' }}">
  <label for="hotline">Hotline:</label>
  <input type="text" id="hotline" name="hotline" class="form-control" placeholder="Enter Product Name" value="{{ $element['hotline'] }}">
  <span class="text-danger">{{ $errors->first('hotline') }}</span>
</div>

<div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
  <label for="contact">Contact:</label>
  <input type="text" id="contact" name="contact" class="form-control" placeholder="Enter Product Name" value="{{ $element['contact'] }}">
  <span class="text-danger">{{ $errors->first('contact') }}</span>
</div>


<div class="form-group {{ $errors->has('vat') ? 'has-error' : '' }}">
  <label for="vat">Vat parcent:</label>
  <input type="number" id="vat" name="vat" class="form-control" stape="any" placeholder="Enter Product Name" value="{{ $element['vat'] }}">
  <span class="text-danger">{{ $errors->first('vat') }}</span>
</div>

<div class="form-group {{ $errors->has('semail') ? 'has-error' : '' }}">
  <label for="semail">Service email:</label>
  <input type="text" id="semail" name="semail" class="form-control" placeholder="Enter Product Name" value="{{ $element['semail'] }}">
  <span class="text-danger">{{ $errors->first('semail') }}</span>
</div>


  
  <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
    <label for="image">Favicon Icon:</label>
    <input type="file" name="image">
    <span class="text-danger">{{ $errors->first('image') }}</span>
  </div>

  <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
    <label for="image">Site Logo:</label>
    <input type="file" name="image1">
    <span class="text-danger">{{ $errors->first('image') }}</span>
  </div>


  <div class="form-group">
    <button class="form-control btn btn-success">Update</button>
  </div>
</form>

<!-- body part -->
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
@empty
  {{'Data not found'}}
@endforelse
<!--custom update modal part================================ -->




@endsection