@extends('layouts.master_service_management')

@section('title')
    {{"Sales Automation Process :: Dashboard"}}
@endsection

@section('content')
<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content">
        <br>
        <br>
        {{-- @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif --}}

{{-- <h2> This is testing csv form by Synergy. Don't Use it. We will remove it after testing. </h2>
        <form class="form-horizontal" method="POST" action="{{ route('serviceManagement.bulkUpload') }}" autocomplete="on" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="csv_file" class="control-label">Choose CSV File</label>
        <input type="file" class="form-control" name="csv_file" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Upload</button>
    </div>
</form> --}}

{{-- <a href="{{route('serviceManagement.download')}}">Download</a>
 --}}



        <div class="container">
            <div class="row">
                <!-- Box 1 -->
                <div class="box">
                    <h5>Pending Products <br><br>
                        {{$pending}}</h5>
                </div>

                <!-- Box 2 -->
                <div class="box">
                    <h5>Received Products <br><br> {{$received}} </h5>

                </div>

                <!-- Box 3 -->
                <div class="box">
                    <h5>Checking Products <br><br> {{$checking}} </h5>

                </div>

                <!-- Box 4 -->
                <div class="box">
                    <h5>Approve Delivered Products <br><br> {{$approvedDeliverd}} </h5>

                </div>

                <!-- Box 5 -->
                <div class="box">
                    <h5>Canceled Products <br><br> {{$canceled}} </h5>

                </div>

                <!-- Box 6 -->
                <div class="box">
                    <h5>Canceled Delivered Products <br><br> {{$cancelDeliverd}} </h5>

                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add your custom CSS below -->
<style>
    .container {
        width: 80%;
        margin: 0 auto;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .box {
        border-radius: 15px;
        padding: 30px;
        flex: 0 0 30%; /* 3 boxes per row */
        box-sizing: border-box;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .box:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .box h5 {
        margin-bottom: 10px;
        font-size: 1.5rem;
        font-weight: bold;
    }

    /* Responsive Design for smaller screens */
    @media (max-width: 768px) {
        .box {
            flex: 0 0 48%; /* 2 boxes per row for medium screens */
        }
    }

    @media (max-width: 480px) {
        .box {
            flex: 0 0 100%; /* 1 box per row for small screens */
        }
    }
</style>
@endsection
