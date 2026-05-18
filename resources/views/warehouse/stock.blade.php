@extends('layouts.master_warehouse')

@section('title')
  {{ "Sales Automation Process :: Stock" }}
@endsection

@section('content')

<style>
  /* ===== SAFE MODERN ANIMATION (AdminLTE Friendly) ===== */

  /* Page load animation */
  .content-wrapper {
    animation: fadeSlide 0.5s ease-in-out;
  }

  @keyframes fadeSlide {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Box polish */
  .box {
    transition: box-shadow 0.25s ease, transform 0.25s ease;
  }

  .box:hover {
    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
    transform: translateY(-2px);
  }

  /* Inputs */
  .form-control {
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }

  .form-control:focus {
    border-color: #f39c12;
    box-shadow: 0 0 0 2px rgba(243,156,18,0.15);
  }

  /* Buttons */
  .btn {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.15);
  }

  /* Alerts */
  .alert {
    animation: alertFade 0.4s ease-in-out;
  }

  @keyframes alertFade {
    from {
      opacity: 0;
      transform: translateY(-6px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Dynamic fields animation */
  .container1 .row {
    animation: fieldFade 0.25s ease-in-out;
  }

  @keyframes fieldFade {
    from {
      opacity: 0;
      transform: translateY(6px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<!-- content part================================ -->
<div class="content-wrapper">

  {{-- Breadcrumb --}}
  @include('warehouse.bc.bc')

  <section class="content-header">
    <div class="row">
      <div class="">

        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title">
              <i class="fa fa-cubes"></i> Product Add
            </h3>
          </div>

          {{-- Messages --}}
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
                <strong>Success!</strong> {{ Session::get('success') }}
              </div>
            @endif
          </div>

          {{-- Form --}}
          <form class="form-horizontal"
                method="POST"
                action="{{ route('warehouse.stock.store') }}"
                enctype="multipart/form-data"
                autocomplete="off">

            @csrf

            <div class="box-body">

              {{-- Product --}}
              <div class="form-group {{ $errors->has('product_id') ? 'has-error' : '' }}">
                <label class="col-sm-2 control-label">Product :</label>
                <div class="col-sm-5">
                  <select name="product_id" class="form-control select2" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                      <option value="{{ $product['id'] }}">{{ $product['model'] }}</option>
                    @endforeach
                  </select>
                  <span class="text-danger">{{ $errors->first('product_id') }}</span>
                  <br><br>
                  <input type="number"
                         name="wperiods"
                         class="form-control"
                         required
                         placeholder="Warranty Period"
                         min="0">
                </div>
              </div>

              {{-- Product Details --}}
              <div class="form-group">
                <div class="container1">
                  <label class="col-sm-2 control-label">Product Details</label>
                  <div class="col-sm-10">
                    <button class="add_form_field btn btn-warning btn-md" style="width:49%">
                      <i class="fa fa-plus"></i> Add Field
                    </button>
                    <br><br>
                  </div>
                </div>
              </div>

            </div>

            <div class="box-footer">
              <button type="submit" class="btn btn-success pull-right">
                <i class="fa fa-check"></i> Submit
              </button>
            </div>

          </form>

        </div>

      </div>
    </div>
  </section>

</div>
<!-- content part================================ -->

{{-- ================== JS (UNCHANGED LOGIC) ================== --}}
<script>
$(document).ready(function() {

  var max_fields = 1000;
  var wrapper = $(".container1");
  var add_button = $(".add_form_field");
  var x = 1;

  $(add_button).click(function(e){
    e.preventDefault();
    if (x < max_fields) {
      x++;
      var newField = $(
        '<div class="row" style="padding:0px 30px 8px 212px">' +
          '<div class="col-xs-4">' +
            '<input type="text" name="snos[]" class="form-control" required placeholder="IMEI 1">' +
          '</div>' +
          '<div class="col-xs-4">' +
            '<input type="text" name="imeis[]" class="form-control" required placeholder="IMEI 2">' +
          '</div>' +
          '<button class="delete btn btn-danger col-sm-2">Delete</button>' +
        '</div>'
      );
      wrapper.append(newField);
      newField.find('input:first').focus();
    }
  });

  $(wrapper).on("click", ".delete", function(e){
    e.preventDefault();
    $(this).parent('div').remove();
    x--;
  });

});
</script>

@endsection
