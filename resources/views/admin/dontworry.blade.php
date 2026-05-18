@extends('layouts.master_admin')

@section('title')
  {{"E-Warranty Ststem :: Return Product"}}
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
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Don't Worry Customer List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<div class="information">
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


              <table id="example" class="ui celled table" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Status</th>
                  <th>Action</th>

                  <th>Customer </th>
                  <th>Mobile </th>
                  <th>UserID </th>
                  <th>Product </th>
                  <th>Model </th>
                  <th>Brand </th>
                  <th>IMEI </th>
                  <th>S.No </th>
                  <th>Created Date </th>
                  <th>Updated Date </th>
                </tr>
                </thead>
                <tbody>
@foreach ($dwrecords as $key => $element)
  
  <tr>
    <td>{{$key + 1}} </td>
<td>
  @if ($element->status == true)
       <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'ratailerDWStatusModal'. $element->id}}">Approve</button>
  @else
    <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'ratailerDWStatusModal'. $element->id}}">Pending</button>
  @endif
</td>


<td>
  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'catDeleteModal'. $element['id']}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
</td>

    <td>{{$element['customer']}} </td>
    <td>{{$element['mobile']}} </td>
    

    <td>{{$element['user']['firstname']}} - {{$element['user']['officeid']}} </td>
    <td>{{$element['product']['name']}} </td>
    <td>{{$element['product']['model']}} </td>
    <td>{{$element['brand']['name']}} </td>
    <td>{{$element['imei']}} </td>
    <td>{{$element['sno']}} </td>
    <td>{{date_format(date_create($element['created_at']),"d-M-Y")}}</td>
    <td>{{date_format(date_create($element['updated_at']),"d-M-Y")}}</td>

  </tr>


@endforeach
                </tbody>
               
              </table>

<table>
  
  <tbody>
      <tr>
        <td colspan="13">
          {{ $dwrecords->links() }}
        </td>
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


<!--custom delete modal part================================ -->


@forelse ($dwrecords as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'catDeleteModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['name']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{route('admin.dontWorry.delete',$element['id'])}}" method="post">
   <h3 class="text-info">Do You Want To Delete This Data ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input name="_method" type="hidden" value="delete">
    
    <div class="form-group">
      <button class="form-control btn btn-danger">Delete</button>
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
<!--custom delete modal part================================ -->



<!--custom hospitaldetailStatusModal modal part================================ -->


@forelse ($dwrecords as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'ratailerDWStatusModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->email}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->


@if ($element->status == true)
  <form action="{{ route('admin.dontWorry.inactive') }}" method="post">
   <h3 class="text-info">Do You Want To Pending This Retailer ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $element->id }}">
    <!-- <input name="_method" type="hidden" value="delete"> -->
    
    <div class="form-group">
      <button class="form-control btn btn-danger">Pending</button>
    </div>

  </form>
@else
   <form action="{{ route('admin.dontWorry.active') }}" method="post">
   <h3 class="text-info">Do You Want To Active This Option ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $element->id }}">
    <!-- <input name="_method" type="hidden" value="delete"> -->
    
    <div class="form-group">
      <button class="form-control btn btn-primary">Active</button>
    </div>

  </form>
@endif



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
<!--custom hospitaldetailStatusModal modal part================================ -->






@endsection