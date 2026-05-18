@extends('layouts.master_admin')

@section('title')
  {{"Sales Automation Process :: Add Retailer"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('admin.bc.bc')
    <!-- bc part================================ -->
    </section>

    
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Retailer List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="ui celled table" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Retailer Name</th>
                  <th>Contact Name</th>
                  <th>Email</th>
                  <th>Retailer ID</th>
                  <th>Retailer Type</th>
                  <th>Contact No.</th>
                  <th>Market Name</th>
                   <th>Address</th>

<th>Division</th>
<th>District</th>
<th>Upazila</th>

                </tr>
                </thead>
                <tbody>
@foreach ($retailerdwnlds as $key=>$element)
        <tr>
          
          <td>{{$key + 1}} </td>


          <td>{{$element->firstname}} {{$element->lastname}}</td>
          <td>{{$element->contact_name}}</td>
          <td>{{$element->email}}</td>
<td>
  {{$element->officeid}}
</td>
<td>
  {{$element->store_type}}
</td>

          <td>{{$element->contact}}</td>
          <td>{{$element->market_name}}</td>
            <td>{{$element->address}}</td>




<td>
  @if ($element['division']['name'])
    {{$element['division']['name']}}
  @else
    -
  @endif
</td>

<td>
  @if ($element['district']['name'])
    {{$element['district']['name']}}
  @else
    -
  @endif
</td>

<td>
  @if ($element['upazila']['name'])
    {{$element['upazila']['name']}}
  @else
    -
  @endif
</td>



        </tr>
@endforeach
                

              
                </tbody>
               
              </table>


<table>
  
  <tbody>
      <tr>
        <td colspan="9">
          {{ $retailerdwnlds->links() }}
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
  <!-- /.content-wrapper -->
<!-- content part================================ -->











<!-- // jquery area ========= -->
<!-- // jquery area ========= -->
<script type="text/javascript">

  /*$('#level').on('change', function(e){
    var level = e.target.value;
    //console.log(level);
    if (level == 300 || level == 100) {
      $('#retailerArea').css({'display':'block'});
    } else {
      //$('#user_id').empty();
      $('#retailerArea').css({'display':'none'});
      //$('#user_id').val('');
    }
  });*/


  $('#division').on('change', function(e){
    var division_id = e.target.value;
    //console.log(division_id);
    var route = "{{route('admin.districtSelectBoxOnDivisionWithAjax')}}/"+division_id;
    $.get(route, function(data) {
      //console.log(data);
      $('#district').empty();
      $('#district').append('<option value="'+'">Select District</option>');
      $.each(data, function(index,data){
        $('#district').append('<option value="' + data.id + '">' + data.name + '</option>');
      });
    });
  });

  $('#district').on('change', function(e){
    var district_id = e.target.value;
    //console.log(district_id);
    var route = "{{route('admin.upazilaSelectBoxOnDistrictWithAjax')}}/"+district_id;
    $.get(route, function(data) {
      //console.log(data);
      $('#upazila').empty();
      
      $.each(data, function(index,data){
        $('#upazila').append('<option value="' + data.id + '">' + data.name +'</option>');
      });
    });
  });


</script>

<!-- // jquery area ========= -->


@endsection