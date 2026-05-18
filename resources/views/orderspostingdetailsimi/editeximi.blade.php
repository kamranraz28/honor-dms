@extends('layouts.master_warehouse2')

@section('title')
    {{ 'E-Warranty System :: Edit IMEI' }}
@endsection

@push('customheader')
    {{--  X-editable dependencies  --}}
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css" rel="stylesheet" />
    <script>
        $.fn.poshytip = {
            defaults: null
        }
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js"></script>

    {{-- Page-scoped styles (no conflict with master) --}}
    <style>
        /* Scope everything to this page only */
        .imei-edit-wrapper {
            background: #f3f4f6;
        }

        .imei-edit-wrapper .new-box {
            max-width: 1000px;
            margin: 20px auto 40px;
            background: #ffffff;
            border-radius: 18px;
            padding: 20px 24px 26px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.14);
            border: 1px solid #e5e7eb;
            animation: imeiCardIn 0.4s ease-out;
        }

        .imei-edit-wrapper .orader {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 14px;
            color: #111827;
        }

        .imei-edit-wrapper .orader::after {
            content: "";
            display: block;
            margin-top: 4px;
            width: 70px;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, #0ea5e9, #6366f1);
        }

        /* Table styling */
        .imei-edit-wrapper .data-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 14px;
            overflow: hidden;
            background: #ffffff;
        }

        .imei-edit-wrapper .data-table thead tr {
            background: linear-gradient(90deg, #eef2ff, #e0f2fe);
        }

        .imei-edit-wrapper .data-table thead th {
            padding: 10px 12px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #4b5563;
            border-bottom: 1px solid #d1d5db;
        }

        .imei-edit-wrapper .data-table tbody td {
            padding: 10px 12px;
            font-size: 13px;
            border-bottom: 1px solid #e5e7eb;
            color: #111827;
        }

        .imei-edit-wrapper .data-table tbody tr:nth-child(odd) {
            background-color: #f9fafb;
        }

        .imei-edit-wrapper .data-table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .imei-edit-wrapper .data-table tbody tr {
            transition: background-color 0.2s ease, transform 0.15s ease, box-shadow 0.15s ease;
        }

        .imei-edit-wrapper .data-table tbody tr:hover {
            background-color: #e5f3ff;
            transform: scale(1.002);
            box-shadow: 0 8px 16px rgba(15, 23, 42, 0.08);
        }

        /* Editable IMEI link styling */
        .imei-edit-wrapper a.update {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.06);
            color: #1d4ed8;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease, transform 0.1s ease;
        }

        .imei-edit-wrapper a.update:hover {
            background: rgba(37, 99, 235, 0.12);
            color: #1d4ed8;
            transform: translateY(-1px);
        }

        .imei-edit-wrapper a.update:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }

        /* Non-editable (Delivered) IMEI text */
        .imei-edit-wrapper td:nth-child(3):not(:has(a.update)) {
            font-weight: 500;
        }

        /* Animation */
        @keyframes imeiCardIn {
            from {
                opacity: 0;
                transform: translateY(16px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 768px) {
            .imei-edit-wrapper .new-box {
                padding: 16px 14px 18px;
                margin: 10px 8px 25px;
            }

            .imei-edit-wrapper .data-table thead {
                font-size: 11px;
            }

            .imei-edit-wrapper .data-table tbody td {
                font-size: 12px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- content part================================ -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper imei-edit-wrapper">
        <!-- Content Header (Page header) -->
        <!-- bc part================================ -->
        @include('warehouse.bc.bc')
        <!-- bc part================================ -->

        <!-- Main content -->
        <div class="new-box">
            <h4 class="orader">Order number: {{ $oraders->orader_number }}</h4>

            <table class="table-bordered data-table table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product</th>
                        <th>IMEI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($oraderdetails as $oraderdetail)
                        @foreach ($oraderdetail->imeilist as $imei)
                            {{--  @dump($imei)  --}}
                            <tr>
                                <td>{{ $imei->id }}</td>
                                <td>
                                    {{ $oraderdetail->Product->name }} ({{ $oraderdetail->Product->model }})
                                </td>
                                <td>
                                    @if ($oraders->status == 5)
                                        {{ $imei->imi }}
                                    @else
                                        <a href=""
                                           class="update"
                                           data-name="imei"
                                           data-type="text"
                                           data-pk="{{ $imei->id }}"
                                           data-title="IMEI">
                                            {{ $imei->imi }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $.fn.editable.defaults.mode = 'inline';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $('.update').editable({
            url: "{{ route('update.imei') }}",
            type: 'text',
            pk: 1,
            name: 'imei',
            title: 'Enter IMEI',
            success: function (response, newValue) {
                if (response.success) {
                    alert("Update Complete");
                } else {
                    alert('Update Failed. Please check the IMEI Number.');
                    location.reload();
                }
            }
        });
    </script>
@endpush
