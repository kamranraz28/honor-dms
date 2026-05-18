@extends('layouts.master_distributor')

@section('title')
  {{"Sales Automation Process :: Selling Product"}}
@endsection

@section('content')

<div class="content-wrapper">

  @include('distributor.bc.bc')

  <section class="content-header">
    <div class="row">
      <div class="">
        <div class="box box-warning">

          <div class="box-header with-border">
            <h3 class="box-title">Sale Product</h3>
          </div>

          <form class="form-horizontal" method="POST" action="{{ route('distributor.sale.store') }}" autocomplete="on" enctype="multipart/form-data">
            @csrf

            <div class="box-body">
              @if(count($errors))
                <div class="alert alert-danger alert-dismissible">
                  <strong>Whoops!</strong> There were some problems with your input.
                  <ul>
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade in">
                  <a href="#" class="close" data-dismiss="alert">&times;</a>
                  <strong>Success!</strong> {{Session::get('success')}}
                </div>
              @endif
            </div>

            <div class="box-body">

              <!------------------------ RETAILER FIELD ------------------------>
              <div class="form-group {{ $errors->has('retailer_id') ? 'has-error' : '' }}">
                <label class="col-md-2 control-label">Retailer :</label>
                <div class="col-md-5">
                  <select name="retailer_id" class="form-control select2" required>
                    <option value="">Select Retailer</option>
                    @foreach($retailers as $r)
                      <option value="{{ $r['id'] }}">{{ $r['name'] }} - {{ $r['officeid'] }}</option>
                    @endforeach
                  </select>
                  <span class="text-danger">{{ $errors->first('retailer_id') }}</span>
                </div>
              </div>


              <!------------------------ ADD SERIAL AREA ------------------------>
              <div class="form-group">
                <label class="col-sm-2 control-label">Add Serial Number</label>

                <div class="col-sm-10 container1" style="padding-top:10px">

                  <button type="button" class="add_form_field btn btn-warning btn-md" style="width:50%">
                    + Add Field
                  </button>

                  <br><br>

                </div>
              </div>

            </div>

            <div class="box-footer">
              <button type="button" class="btn btn-success pull-right" id="showModalBtn" data-toggle="modal" data-target="#myModal">
                Submit
              </button>
            </div>


            <!---------------------- SUMMARY MODAL ------------------------>
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Sale Summary</h4>
                  </div>

                  <div class="modal-body">
                    <table id="productTable" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Product Model</th>
                          <th>Quantity</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr><td>-</td><td>-</td></tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="modal-footer">
                    <button type="submit" class="btn btn-success" onclick="myFunction()">Confirm</button>
                  </div>

                </div>

              </div>
            </div>

          </form>

        </div>
      </div>
    </div>
  </section>

</div>

<!------------------------ JS FIXED + IMPROVED ------------------------>

<script>
function myFunction() {
  alert("You are confirming the sale. Are you sure about it?!");
}

$(document).ready(function() {

    let max_fields = 100;
    let wrapper = $(".container1");
    let add_button = $(".add_form_field");
    let x = 0;
    let productCounts = {};

    function attachEventListeners() {
        $('.snos').off("keyup").on("keyup", function() {
            let snoArea = $(this);
            let id = snoArea.attr('id');
            let index = id.replace("snos", "");
            let productArea = $("#product" + index);
            let route = "{{ route('ajax.varifyserialno') }}/" + snoArea.val();

            $.get(route, function(data) {

                if (data == 0) {
                    productArea.val('');
                    $("#" + id + "text").text("IMEI does not match");
                } else if (data == 1) {
                    productArea.val('');
                    $("#" + id + "text").text("Duplicate IMEI. Already Sold");
                } else if (data == 2) {
                    productArea.val('');
                    $("#" + id + "text").text("IMEI/SNO not available in your purchase");
                } else {
                    $("#" + id + "text").text("");
                    productArea.val(data);

                    productCounts[data] = (productCounts[data] ?? 0) + 1;
                    updateTable();
                }

            });
        });
    }

    // ADD FIELD BUTTON CLICK FIXED
    add_button.click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;

            let newField = `
              <div class="col-xs-12 dynamic-field" style="padding:5px; margin-bottom:10px; border:1px solid #eee; border-radius:6px; background:#fafafa;">

                <div class="col-xs-5">
                  <input type="text" name="snos[]" id="snos${x}" class="form-control snos" placeholder="IMEI 1" required autocomplete="off">
                  <span class="text-danger" id="snos${x}text"></span>
                </div>

                <div class="col-xs-4">
                  <input type="text" name="product" id="product${x}" disabled class="form-control" placeholder="Product">
                </div>

                <button type="button" class="delete btn btn-danger col-sm-2" style="font-size:16px; font-weight:bold;">
                  X
                </button>

              </div>
            `;

            wrapper.append(newField);
            attachEventListeners();
        }
    });

    // DELETE FIELD
    wrapper.on("click", ".delete", function() {
        let row = $(this).closest(".dynamic-field");
        let productName = row.find('input[name="product"]').val();

        if (productCounts[productName]) {
            productCounts[productName]--;
            if (productCounts[productName] <= 0) delete productCounts[productName];
        }

        updateTable();
        row.remove();
    });

    function updateTable() {
        let tbody = $("#productTable tbody");
        tbody.empty();

        $.each(productCounts, function(product, qty) {
            tbody.append(`<tr><td>${product}</td><td>${qty}</td></tr>`);
        });
    }

    // PREVENT SUBMIT ON OPENING MODAL
    $("#showModalBtn").click(function(e){
      e.preventDefault();
    });

    attachEventListeners();

});
</script>

<style>
  /* Light styling improvements */
  .dynamic-field:hover {
      background: #f0f8ff;
      border-color: #cce5ff;
      transition: 0.2s;
  }
  .add_form_field {
      font-weight: bold;
      border-radius: 6px;
  }
  .delete {
      border-radius: 50%;
      height: 35px;
      width: 35px;
      padding: 0;
  }
</style>

@endsection
