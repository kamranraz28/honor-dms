@extends('layouts.master_distributor')

@section('title')
{{"E-Warranty Ststem :: Daily Stock Report"}}
@endsection


@section('content')


<!-- content part================================ -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- bc part================================ -->
  @include('distributor.bc.bc')
  <!-- bc part================================ -->

  <!-- Main content -->
  <section class="content">




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
            @foreach ($retailerReport['retailerName'] as $key => $name)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $name ?? 'N/A' }}</td>
                <td>{{ $retailerReport['contactName'][$key] ?? 'N/A' }}</td>
                <td>{{ $retailerReport['email'][$key] ?? 'N/A' }}</td>
                <td>{{ $retailerReport['retailerID'][$key] ?? 'N/A' }}</td>
                <td>{{ $retailerReport['retailerType'][$key] ?? 'N/A' }}</td>
                <td>{{ $retailerReport['contactNo'][$key] !== null && $retailerReport['contactNo'][$key] !== '' ? $retailerReport['contactNo'][$key] : 'N/A' }}</td>
                <td>{{ $retailerReport['marketName'][$key] ?? 'N/A' }}</td>
                <td>{{ $retailerReport['address'][$key] ?? 'N/A' }}</td>
                <td>{{ $retailerReport['division'][$key] ?? 'N/A' }}</td>
                <td>{{ $retailerReport['district'][$key] ?? 'N/A' }}</td>
                <td>{{ $retailerReport['upazila'][$key] ?? 'N/A' }}</td>
            </tr>
            
        @endforeach

            </tbody>

          </table>


          <table>

            <tbody>
              {{-- <tr>
                <td colspan="9">
                  {{ $retailerdwnlds->links() }}
                </td>
              </tr> --}}
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


  $('#division').on('change', function (e) {
    var division_id = e.target.value;
    //console.log(division_id);
    var route = "{{route('admin.districtSelectBoxOnDivisionWithAjax')}}/" + division_id;
    $.get(route, function (data) {
      //console.log(data);
      $('#district').empty();
      $('#district').append('<option value="' + '">Select District</option>');
      $.each(data, function (index, data) {
        $('#district').append('<option value="' + data.id + '">' + data.name + '</option>');
      });
    });
  });

  $('#district').on('change', function (e) {
    var district_id = e.target.value;
    //console.log(district_id);
    var route = "{{route('admin.upazilaSelectBoxOnDistrictWithAjax')}}/" + district_id;
    $.get(route, function (data) {
      //console.log(data);
      $('#upazila').empty();

      $.each(data, function (index, data) {
        $('#upazila').append('<option value="' + data.id + '">' + data.name + '</option>');
      });
    });
  });


</script>

<!-- // jquery area ========= -->


@endsection