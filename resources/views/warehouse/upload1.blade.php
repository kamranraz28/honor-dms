@extends('layouts.master_warehouse')

@section('title')
  Sales Automation Process :: Upload File
@endsection

@section('content')

<style>
  /* ===== Advanced but SAFE animation & polish ===== */

  /* Page fade-in */
  .content-wrapper {
    animation: pageFade 0.5s ease-in-out;
  }

  @keyframes pageFade {
    from {
      opacity: 0;
      transform: translateY(12px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Card styling */
  .clean-box {
    background: #fff;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: box-shadow 0.25s ease, transform 0.25s ease;
  }

  .clean-box:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transform: translateY(-2px);
  }

  .clean-box .box-header {
    border-bottom: 1px solid #e5e7eb;
  }

  .clean-box .box-title {
    font-size: 20px;
    font-weight: 600;
    color: #374151;
  }

  .clean-box .box-body {
    padding: 30px;
  }

  /* Labels */
  .clean-label {
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
  }

  /* Inputs */
  .clean-input {
    height: 42px;
    border-radius: 4px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }

  .clean-input:focus {
    border-color: #3c8dbc;
    box-shadow: 0 0 0 2px rgba(60,141,188,0.15);
  }

  /* Helper text */
  .clean-help {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
  }

  /* Button */
  .btn-clean {
    padding: 8px 22px;
    font-weight: 500;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .btn-clean:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.15);
  }

  /* Alerts fade */
  .alert {
    animation: alertFade 0.4s ease-in-out;
  }

  @keyframes alertFade {
    from {
      opacity: 0;
      transform: translateY(-8px);
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

  <section class="content">
    <div class="row">

      <div class="col-md-8 col-md-offset-2">

        <div class="box clean-box">

          <div class="box-header">
            <h3 class="box-title">Bulk Upload via CSV</h3>
          </div>

          <div class="box-body">

            {{-- Messages --}}
            @if(count($errors))
              <div class="alert alert-danger">
                <strong>Whoops!</strong> Please fix the errors below.
                <ul>
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            @if(Session::has('success'))
              <div class="alert alert-success">
                <strong>Success!</strong> {{ Session::get('success') }}
              </div>
            @endif

            {{-- Form --}}
            <form class="form-horizontal"
                  method="POST"
                  action="{{ route('warehouse.upload1.store') }}"
                  enctype="multipart/form-data">

              @csrf

              {{-- CSV file --}}
              <div class="form-group">
                <label class="clean-label">Upload CSV File</label>
                <input type="file"
                       name="csv_file"
                       class="form-control clean-input"
                       required>
                <div class="clean-help">
                  Accepted format: .csv
                </div>
              </div>

              {{-- Upload type --}}
              <div class="form-group">
                <label class="clean-label">Select Upload Type</label>
                <select name="type" class="form-control clean-input" required>
                  <option value="">Choose upload type...</option>
                  <option value="5">Stock Upload</option>
                  <option value="12">Primary Sale Upload</option>
                  <option value="13">Secondary Sale Upload</option>
                  <option value="14">Tertiary Sale Upload</option>
                  <option value="15">Retailer Upload</option>
                  <option value="16">Dealer Upload</option>
                  <option value="17">Dealer–Retailer Mapping</option>
                </select>
              </div>

              {{-- Submit --}}
              <div class="form-group text-right">
                <button type="submit" class="btn btn-primary btn-clean">
                  <i class="fa fa-upload"></i> Upload
                </button>
              </div>

            </form>

          </div>
        </div>

      </div>

    </div>
  </section>

</div>
<!-- content part================================ -->

@endsection
