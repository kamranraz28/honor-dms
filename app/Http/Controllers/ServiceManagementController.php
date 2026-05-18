<?php

namespace App\Http\Controllers;

use App\Replace;
use App\Smsdetail;
use App\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

use DB;
use Illuminate\Http\Request;
use Session;

class ServiceManagementController extends Controller
{
    //
    public function DashboardView()
    {
        $replaces = Replace::all();
        $pending = $replaces->where('service_status', 'Pending')->count();
        // dd($pending);
        $received = $replaces->where('service_status', 'Receive')->count();
        $checking = $replaces->where('service_status', 'Checking')->count();
        $approvedDeliverd = $replaces->where('service_status', 'Approved & Deliverd')->count();
        $canceled = $replaces->where('service_status', 'Cancel')->count();
        $cancelDeliverd = $replaces->where('service_status', 'Cancel & Deliverd')->count();
        return view('service_management.dashboard', compact('pending', 'received', 'checking', 'approvedDeliverd', 'canceled', 'cancelDeliverd'));
    }


    public function receive_product()
    {
        // Get the session data for date filters
        $from_date = Session::get('from_date');
        $to_date = Session::get('to_date');
        $sno = Session::get('sno');

        // Initialize the query for the Replace model
        $replaces = Replace::with([
            'product.cat',
            'brand',
            'sms',
            'user'
        ])->where('service_status', 'Pending');

        // Apply date filters if they exist
        if ($from_date) {
            $replaces->whereDate('created_at', '>=', $from_date);
        }
        if ($to_date) {
            $replaces->whereDate('created_at', '<=', $to_date);
        }
        if ($sno) {
            $replaces->where('sno', $sno)->orWhere('imei', $sno);
        }


        // Get the results (limit to 100)
        $replaces = $replaces->paginate(100);

        // Map the data for the report
        $receiveReport = $replaces->map(function ($replace) {
            return [
                'id' => $replace->id ?? '-',
                'name' => $replace->product->name ?? '-',
                'model' => $replace->product->model ?? '-',
                'brand' => $replace->brand->name ?? '-',
                'problem' => $replace->problem ?? '-',
                'service' => $replace->service_type ?? '-',
                'category' => $replace->product->cat->name ?? '-',
                'send' => $replace->created_at ? $replace->created_at->format('Y-m-d') : '-',
                'imei1' => $replace->sno ?? '-',
                'imei2' => $replace->imei ?? '-',
                'replace1' => $replace->sms->sno ?? '-',
                'replace2' => $replace->sms->imei ?? '-',
                'username' => $replace->user->firstname ?? '-',
                'userid' => $replace->user->officeid ?? '-',
                'customername' => $replace->contact_name ?? '-',
                'number' => $replace->contact_no ?? '-',
                'memo' => $replace->memo ?? '',
            ];
        });

        // Pass the data to the view
        return view('service_management.receive_product', compact('receiveReport', 'replaces'));
    }

    public function receiveReportStore(Request $request)
    {
        // Store the date filters in the session
        Session::put([
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'sno' => $request->input('sno'),
        ]);

        // Redirect back to the receive product page
        return redirect(route('serviceManagement.receiveProduct'));
    }




    public function receive_confirm(Request $request, $id)
    {
        Replace::find($id)->update([
            'received' => $request->receive_date,
            'service_status' => 'Receive',
            'remarks' => $request->remarks,

        ]);
        return redirect()->back()->with('success', 'Poduct has been received successfully');
    }

    //Receive Excel Download Start

    public function receive_product_download(Request $request)
    {

        // Initialize the query for the Replace model
        $replaces = Replace::with([
            'product.cat',
            'brand',
            'sms',
            'user'
        ])->where('service_status', 'Pending')->get();


        // Map the data for the report
        $receiveReport = $replaces->map(function ($replace) {
            return [
                'Replace Id' => $replace->id ?? '',
                'Product Name' => $replace->product->name ?? '-',
                'Model' => $replace->product->model ?? '-',
                'Brand' => $replace->brand->name ?? '-',
                'Problem' => $replace->problem ?? '-',
                'Service' => $replace->service_type ?? '-',
                'Category' => $replace->product->cat->name ?? '-',
                'Send Date' => $replace->created_at ? $replace->created_at->format('Y-m-d') : '-',
                'IMEI-1' => $replace->sno ?? '-',
                'IMEI-2' => $replace->imei ?? '-',
                'Replace IMEI-1' => $replace->sms->sno ?? '-',
                'Replace IMEI-2' => $replace->sms->imei ?? '-',
                'User Name' => $replace->user->firstname ?? '-',
                'User ID' => $replace->user->officeid ?? '-',
                'Customer Name' => $replace->contact_name ?? '-',
                'Number' => $replace->contact_no ?? '-',
            ];
        });

        // Check if the request is for Excel download
        if ($request->has('export') && $request->export === 'excel') {
            $fileName = 'receive_product_report.xlsx';
            return (new FastExcel($receiveReport))->download($fileName);
        }

        // Pass the data to the view
        return view('service_management.receive_product', compact('receiveReport', 'replaces'));
    }

    //Receive Excel Download End

    public function checkProduct()
    {
        // Get the session data for date filters
        $from_date = Session::get('from_date');
        $to_date = Session::get('to_date');
        $sno = Session::get('sno');

        // Initialize the query for the Replace model
        $replaces = Replace::with([
            'product.cat',
            'brand',
            'sms',
            'user'
        ])->where('service_status', 'Receive');

        // Apply date filters if they exist
        if ($from_date) {
            $replaces->whereDate('created_at', '>=', $from_date);
        }
        if ($to_date) {
            $replaces->whereDate('created_at', '<=', $to_date);
        }
        if ($sno) {
            $replaces->where('sno', $sno)->orWhere('imei', $sno);
        }

        // Get the results (limit to 100)
        $replaces = $replaces->paginate(100);

        $receiveReport = $replaces->map(function ($replace) {
            return [
                'id' => $replace->id ?? '-',
                'name' => $replace->product->name ?? '-',
                'model' => $replace->product->model ?? '-',
                'brand' => $replace->brand->name ?? '-',
                'problem' => $replace->problem ?? '-',
                'service' => $replace->service_type ?? '-',
                'category' => $replace->product->cat->name ?? '-',
                'send' => $replace->created_at ? $replace->created_at->format('Y-m-d') : '-',
                'imei1' => $replace->sno ?? '-',
                'imei2' => $replace->imei ?? '-',
                'replace1' => $replace->sms->sno ?? '-',
                'replace2' => $replace->sms->imei ?? '-',
                'username' => $replace->user->firstname ?? '-',
                'userid' => $replace->user->officeid ?? '-',
                'customername' => $replace->contact_name ?? '-',
                'number' => $replace->contact_no ?? '-',
                'receive_date' => $replace->received ?? '-',
                'remarks' => $replace->remarks ?? '',
                'memo' => $replace->memo ?? '',
            ];
        });

        return view('service_management.check_product', compact('receiveReport', 'replaces'));
    }

    public function checkReportStore(Request $request)
    {
        // Store the date filters in the session
        Session::put([
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'sno' => $request->input('sno'),
        ]);

        // Redirect back to the receive product page
        return redirect(route('serviceManagement.checkProduct'));
    }

    //Check Product Excel Start
    public function checkProductExcel()
    {

        // Initialize the query for the Replace model
        $replaces = Replace::with([
            'product.cat',
            'brand',
            'sms',
            'user'
        ])->where('service_status', 'Receive')->get();

        // Map the data for export
        $receiveReport = $replaces->map(function ($replace) {
            return [
                'Replace Id' => $replace->id,
                'Product Name' => $replace->product->name ?? '-',
                'Model' => $replace->product->model ?? '-',
                'Brand' => $replace->brand->name ?? '-',
                'Problem' => $replace->problem ?? '-',
                'Service' => $replace->service_type ?? '-',
                'Category' => $replace->product->cat->name ?? '-',
                'Send Date' => $replace->created_at ? $replace->created_at->format('Y-m-d') : '-',
                'IMEI-1' => $replace->sno ?? '-',
                'IMEI-2' => $replace->imei ?? '-',
                'Replace IMEI-1' => $replace->sms->sno ?? '-',
                'Replace2 IMEI-2' => $replace->sms->imei ?? '-',
                'Username' => $replace->user->firstname ?? '-',
                'User ID' => $replace->user->officeid ?? '-',
                'Customer Name' => $replace->contact_name ?? '-',
                'Number' => $replace->contact_no ?? '-',
                'Receive Date' => $replace->received ?? '-',
                'Remarks' => $replace->remarks ?? '',
            ];
        });

        // Generate and download the Excel file
        return (new FastExcel($receiveReport))->download('check_product_report.xlsx');
    }
    //Check Product Excel End


    public function approveReceiveProduct(Request $request, $id)
    {
        Replace::find($id)->update([
            'service_status' => 'Checking',
            'remarks' => $request->remarks,
        ]);
        return redirect()->back()->with('success', 'Poduct has been approved successfully');
    }

    public function cancelProduct(Request $request, $id)
    {
        Replace::find($id)->update([
            'service_status' => 'Cancel',
            'remarks' => $request->remarks,
            'void' => $request->void,
        ]);
        return redirect()->back()->with('success', 'Poduct has been cancelled successfully');
    }

    public function deliverProduct()
    {
        // Get the session data for date filters
        $from_date = Session::get('from_date');
        $to_date = Session::get('to_date');
        $sno = Session::get('sno');

        // Initialize the query for the Replace model
        $replaces = Replace::with([
            'product.cat',
            'brand',
            'sms',
            'user'
        ])->where('service_status', 'Checking');

        // Apply date filters if they exist
        if ($from_date) {
            $replaces->whereDate('created_at', '>=', $from_date);
        }
        if ($to_date) {
            $replaces->whereDate('created_at', '<=', $to_date);
        }
        if ($sno) {
            $replaces->where('sno', $sno)->orWhere('imei', $sno);
        }

        // Get the results (limit to 100)
        $replaces = $replaces->paginate(100);

        $receiveReport = $replaces->map(function ($replace) {
            return [
                'id' => $replace->id ?? '-',
                'name' => $replace->product->name ?? '-',
                'model' => $replace->product->model ?? '-',
                'brand' => $replace->brand->name ?? '-',
                'problem' => $replace->problem ?? '-',
                'service' => $replace->service_type ?? '-',
                'category' => $replace->product->cat->name ?? '-',
                'send' => $replace->created_at ? $replace->created_at->format('Y-m-d') : '-',
                'imei1' => $replace->sno ?? '-',
                'imei2' => $replace->imei ?? '-',
                'replace1' => $replace->sms->sno ?? '',
                'replace2' => $replace->sms->imei ?? '',
                'username' => $replace->user->firstname ?? '-',
                'userid' => $replace->user->officeid ?? '-',
                'customername' => $replace->contact_name ?? '-',
                'number' => $replace->contact_no ?? '-',
                'receive_date' => $replace->received ?? '-',
                'remarks' => $replace->remarks ?? '',
                'memo' => $replace->memo ?? '',
            ];
        });

        return view('service_management.deliver_product', compact('receiveReport', 'replaces'));
    }

    //Deliver Product Excel Start
    public function deliverProductExcel()
    {

        // Initialize the query for the Replace model
        $replaces = Replace::with([
            'product.cat',
            'brand',
            'sms',
            'user'
        ])->where('service_status', 'Checking')->get();

        $receiveReport = $replaces->map(function ($replace) {
            return [
                'Replace ID' => $replace->id ?? '-',
                'Name' => $replace->product->name ?? '-',
                'Model' => $replace->product->model ?? '-',
                'Brand' => $replace->brand->name ?? '-',
                'Problem' => $replace->problem ?? '-',
                'Service' => $replace->service_type ?? '-',
                'Category' => $replace->product->cat->name ?? '-',
                'Send Date' => $replace->created_at ? $replace->created_at->format('Y-m-d') : '-',
                'IMEI1' => $replace->sno ?? '-',
                'IMEI2' => $replace->imei ?? '-',
                'Replace1' => $replace->sms->sno ?? '-',
                'Replace2' => $replace->sms->imei ?? '-',
                'Username' => $replace->user->firstname ?? '-',
                'User ID' => $replace->user->officeid ?? '-',
                'Customer Name' => $replace->contact_name ?? '-',
                'Number' => $replace->contact_no ?? '-',
                'Receive Date' => $replace->received ?? '-',
                'Remarks' => $replace->remarks ?? '',
            ];
        });

        return (new FastExcel($receiveReport))->download('deliver_product_report.xlsx');
    }
    //Deliver Product Excel End

    public function deliverReportStore(Request $request)
    {
        // Store the date filters in the session
        Session::put([
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'sno' => $request->input('sno')
        ]);

        // Redirect back to the receive product page
        return redirect(route('serviceManagement.deliverProduct'));
    }

    public function approveDelivery(Request $request, $id)
    {
        $imei1 = $request->imei;

        // Find the matching stock record
        $stock = Stock::where('sno', $imei1)
            ->orWhere('imei', $imei1)
            ->first();

        // Determine the value of $imei2 based on the match
        $imei2 = $stock
            ? ($stock->sno === $imei1 ? $stock->imei : $stock->sno)
            : null;

        // Update the Replace record
        $replace = Replace::findOrFail($id);
        $replace->update([
            'service_status' => 'Approved & Deliverd',
            'remarks' => $request->remarks,
            'delivery_date' => $request->delivery_date,
        ]);

        // Update the Smsdetail record
        if ($replace->smsdetail_id) {
            $sms = Smsdetail::find($replace->smsdetail_id);
            if ($sms) {
                $sms->update([
                    'sno' => $imei1,
                    'imei' => $imei2,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Product has been delivered successfully.');
    }


    public function cancelDeliveryProduct(Request $request, $id)
    {
        Replace::find($id)->update([
            'service_status' => 'Cancel',
            'remarks' => $request->remarks,
            'void' => $request->void,
        ]);
        return redirect()->back()->with('success', 'Poduct has been cancelled successfully');
    }

    public function approveDeliverProduct()
    {
        // Get the session data for date filters
        $from_date = Session::get('from_date');
        $to_date = Session::get('to_date');
        $sno = Session::get('sno');

        // Initialize the query for the Replace model
        $replaces = Replace::with([
            'product.cat',
            'brand',
            'sms',
            'user'
        ])->where('service_status', 'Approved & Deliverd');

        // Apply date filters if they exist
        if ($from_date) {
            $replaces->whereDate('created_at', '>=', $from_date);
        }
        if ($to_date) {
            $replaces->whereDate('created_at', '<=', $to_date);
        }
        if ($sno) {
            $replaces->where('sno', $sno)->orWhere('imei', $sno);
        }

        // Get the results (limit to 100)
        $replaces = $replaces->paginate(100);

        $receiveReport = $replaces->map(function ($replace) {
            return [
                'id' => $replace->id ?? '-',
                'name' => $replace->product->name ?? '-',
                'model' => $replace->product->model ?? '-',
                'brand' => $replace->brand->name ?? '-',
                'problem' => $replace->problem ?? '-',
                'service' => $replace->service_type ?? '-',
                'category' => $replace->product->cat->name ?? '-',
                'send' => $replace->created_at ? $replace->created_at->format('Y-m-d') : '-',
                'imei1' => $replace->sno ?? '-',
                'imei2' => $replace->imei ?? '-',
                'replace1' => $replace->sms->sno ?? '',
                'replace2' => $replace->sms->imei ?? '',
                'username' => $replace->user->firstname ?? '-',
                'userid' => $replace->user->officeid ?? '-',
                'customername' => $replace->contact_name ?? '-',
                'number' => $replace->contact_no ?? '-',
                'receive_date' => $replace->received ?? '-',
                'remarks' => $replace->remarks ?? '',
                'delivery_date' => $replace->delivery_date ?? '',
                'memo' => $replace->memo ?? '',
            ];
        });

        return view('service_management.approve_deliver_product', compact('receiveReport', 'replaces'));
    }

    public function approveDeliverReportStore(Request $request)
    {
        // Store the date filters in the session
        Session::put([
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'sno' => $request->input('sno')
        ]);

        // Redirect back to the receive product page
        return redirect(route('serviceManagement.approveDeliverProduct'));
    }

    //Approved Deliverd Excel Start
    public function approveDeliverProductExcel()
    {

        // Initialize the query for the Replace model
        $replaces = Replace::with([
            'product.cat',
            'brand',
            'sms',
            'user'
        ])->where('service_status', 'Approved & Deliverd')->get();

        // Map the data for export
        $receiveReport = $replaces->map(function ($replace) {
            return [
                'Replace ID' => $replace->id ?? '-',
                'Name' => $replace->product->name ?? '-',
                'Model' => $replace->product->model ?? '-',
                'Brand' => $replace->brand->name ?? '-',
                'Problem' => $replace->problem ?? '-',
                'Service' => $replace->service_type ?? '-',
                'Category' => $replace->product->cat->name ?? '-',
                'Send Date' => $replace->created_at ? $replace->created_at->format('Y-m-d') : '-',
                'IMEI-1' => $replace->sno ?? '-',
                'IMEI-2' => $replace->imei ?? '-',
                'Replace IMEI-1' => $replace->sms->sno ?? '-',
                'Replace IMEI-2' => $replace->sms->imei ?? '-',
                'Username' => $replace->user->firstname ?? '-',
                'User ID' => $replace->user->officeid ?? '-',
                'Customer Name' => $replace->contact_name ?? '-',
                'Number' => $replace->contact_no ?? '-',
                'Receive Date' => $replace->received ?? '-',
                'Remarks' => $replace->remarks ?? '',
                'Delivery Date' => $replace->delivery_date ?? '',
            ];
        });

        // Generate and download the Excel file
        return (new FastExcel($receiveReport))->download('approve_deliver_product_report.xlsx');
    }
    //Approved Deliverd Excel End

    public function canceledProduct()
    {
        // Get the session data for date filters
        $from_date = Session::get('from_date');
        $to_date = Session::get('to_date');
        $sno = Session::get('sno');

        // Initialize the query for the Replace model
        $replaces = Replace::with([
            'product.cat',
            'brand',
            'sms',
            'user'
        ])->where('service_status', 'Cancel');

        // Apply date filters if they exist
        if ($from_date) {
            $replaces->whereDate('created_at', '>=', $from_date);
        }
        if ($to_date) {
            $replaces->whereDate('created_at', '<=', $to_date);
        }
        if ($sno) {
            $replaces->where('sno', $sno)->orWhere('imei', $sno);
        }

        // Get the results (limit to 100)
        $replaces = $replaces->paginate(100);

        $receiveReport = $replaces->map(function ($replace) {
            return [
                'id' => $replace->id ?? '-',
                'name' => $replace->product->name ?? '-',
                'model' => $replace->product->model ?? '-',
                'brand' => $replace->brand->name ?? '-',
                'problem' => $replace->problem ?? '-',
                'service' => $replace->service_type ?? '-',
                'category' => $replace->product->cat->name ?? '-',
                'send' => $replace->created_at ? $replace->created_at->format('Y-m-d') : '-',
                'imei1' => $replace->sno ?? '-',
                'imei2' => $replace->imei ?? '-',
                'replace1' => $replace->sms->sno ?? '',
                'replace2' => $replace->sms->imei ?? '',
                'username' => $replace->user->firstname ?? '-',
                'userid' => $replace->user->officeid ?? '-',
                'customername' => $replace->contact_name ?? '-',
                'number' => $replace->contact_no ?? '-',
                'receive_date' => $replace->received ?? '-',
                'remarks' => $replace->remarks ?? '',
                'delivery_date' => $replace->delivery_date ?? '',
                'void' => $replace->void ?? '',
                'memo' => $replace->memo ?? '',
            ];
        });

        return view('service_management.canceled_product', compact('receiveReport', 'replaces'));
    }

    //Cancel Product Excel Start
    public function canceledProductExcel()
    {
        // Initialize the query for the Replace model
        $replaces = Replace::with([
            'product.cat',
            'brand',
            'sms',
            'user'
        ])->where('service_status', 'Cancel')->get();

        // Map the data for export
        $receiveReport = $replaces->map(function ($replace) {
            return [
                'Replace ID' => $replace->id ?? '-',
                'Name' => $replace->product->name ?? '-',
                'Model' => $replace->product->model ?? '-',
                'Brand' => $replace->brand->name ?? '-',
                'Problem' => $replace->problem ?? '-',
                'Service' => $replace->service_type ?? '-',
                'Category' => $replace->product->cat->name ?? '-',
                'Send Date' => $replace->created_at ? $replace->created_at->format('Y-m-d') : '-',
                'IMEI-1' => $replace->sno ?? '-',
                'IMEI-2' => $replace->imei ?? '-',
                'Replace IMEI-1' => $replace->sms->sno ?? '-',
                'Replace IMEI-2' => $replace->sms->imei ?? '-',
                'Username' => $replace->user->firstname ?? '-',
                'User ID' => $replace->user->officeid ?? '-',
                'Customer Name' => $replace->contact_name ?? '-',
                'Number' => $replace->contact_no ?? '-',
                'Receive Date' => $replace->received ?? '-',
                'Remarks' => $replace->remarks ?? '',
                'Delivery Date' => $replace->delivery_date ?? '',
                'Void' => $replace->void ?? '',

            ];
        });

        // Generate and download the Excel file
        return (new FastExcel($receiveReport))->download('canceled_product_report.xlsx');
    }
    //Cancel Product Excel End
    public function cancelReportStore(Request $request)
    {
        // Store the date filters in the session
        Session::put([
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'sno' => $request->input('sno')
        ]);

        // Redirect back to the receive product page
        return redirect(route('serviceManagement.canceledProduct'));
    }

    public function sendToReceive(Request $request, $id)
    {
        Replace::find($id)->update([
            'service_status' => 'Pending',
            'remarks' => $request->remarks,
        ]);
        return redirect()->back()->with('success', 'Poduct has been sent to Receive Status successfully');
    }

    public function cancelDeliveryConfirm(Request $request, $id)
    {
        Replace::find($id)->update([
            'service_status' => 'Cancel & Deliverd',
            'remarks' => $request->remarks,
            'delivery_date' => $request->delivery_date,
        ]);
        return redirect()->back()->with('success', 'Poduct has been delivered successfully');
    }

    public function canceledDeliveredProduct()
    {
        // Get the session data for date filters
        $from_date = Session::get('from_date');
        $to_date = Session::get('to_date');
        $sno = Session::get('sno');

        // Initialize the query for the Replace model
        $replaces = Replace::with([
            'product.cat',
            'brand',
            'sms',
            'user'
        ])->where('service_status', 'Cancel & Deliverd');

        // Apply date filters if they exist
        if ($from_date) {
            $replaces->whereDate('created_at', '>=', $from_date);
        }
        if ($to_date) {
            $replaces->whereDate('created_at', '<=', $to_date);
        }
        if ($sno) {
            $replaces->where('sno', $sno)->orWhere('imei', $sno);
        }

        // Get the results (limit to 100)
        $replaces = $replaces->paginate(100);

        $receiveReport = $replaces->map(function ($replace) {
            return [
                'id' => $replace->id ?? '-',
                'name' => $replace->product->name ?? '-',
                'model' => $replace->product->model ?? '-',
                'brand' => $replace->brand->name ?? '-',
                'problem' => $replace->problem ?? '-',
                'service' => $replace->service_type ?? '-',
                'category' => $replace->product->cat->name ?? '-',
                'send' => $replace->created_at ? $replace->created_at->format('Y-m-d') : '-',
                'imei1' => $replace->sno ?? '-',
                'imei2' => $replace->imei ?? '-',
                'replace1' => $replace->sms->sno ?? '',
                'replace2' => $replace->sms->imei ?? '',
                'username' => $replace->user->firstname ?? '-',
                'userid' => $replace->user->officeid ?? '-',
                'customername' => $replace->contact_name ?? '-',
                'number' => $replace->contact_no ?? '-',
                'receive_date' => $replace->received ?? '-',
                'remarks' => $replace->remarks ?? '',
                'delivery_date' => $replace->delivery_date ?? '',
                'void' => $replace->void ?? '',
                'memo' => $replace->memo ?? '',
            ];
        });

        return view('service_management.canceled_delivered_product', compact('receiveReport', 'replaces'));
    }

    public function cancelDeliverdReportStore(Request $request)
    {
        // Store the date filters in the session
        Session::put([
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'sno' => $request->input('sno')
        ]);

        // Redirect back to the receive product page
        return redirect(route('serviceManagement.canceledDeliveredProduct'));
    }

    public function bulkUpload18(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Retrieve the file path
        $path = $request->file('csv_file')->getRealPath();

        // Read and process the CSV file
        $data = array_map('str_getcsv', file($path));

        // Ensure the file is not empty and has data
        if (count($data) <= 1) {
            return redirect()->back()->with('error', 'The uploaded file is empty or has invalid data.');
        }

        // Extract the rows, skipping the header row
        $csv_data = array_slice($data, 1);

        foreach ($csv_data as $value) {
            // Ensure the row has at least the `sno` column (as it is required for matching)
            if (empty($value[0])) {
                continue;
            }

            $sno = trim($value[0]);
            $received_date = isset($value[1]) ? trim($value[1]) : '';
            $delivery_date = isset($value[2]) ? trim($value[2]) : '';
            $remarks = isset($value[3]) ? trim($value[3]) : '';

            // Update the database if `imei` or `sno` matches
            Replace::where('imei', $sno)
                ->orWhere('sno', $sno)
                ->update([
                        'remarks' => $remarks,
                        'service_status' => 'Approved & Deliverd',
                        'received' => $received_date,
                        'delivery_date' => $delivery_date
                    ]);
        }

        return redirect()->back()->with('success', 'Products have been updated successfully.');
    }



    public function bulkUpload13(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Retrieve the file path
        $path = $request->file('csv_file')->getRealPath();

        // Read and process the CSV file
        $data = array_map('str_getcsv', file($path));

        // Ensure the file is not empty and has data
        if (count($data) <= 1) {
            return redirect()->back()->with('error', 'The uploaded file is empty or has invalid data.');
        }

        // Extract the rows, skipping the header row
        $csv_data = array_slice($data, 1);

        foreach ($csv_data as $value) {
            // Ensure the row has at least the `sno` column (as it is required for matching)
            if (empty($value[0])) {
                continue;
            }

            $sno = trim($value[0]);
            $received_date = isset($value[1]) ? trim($value[1]) : '';
            $void = isset($value[2]) ? trim($value[2]) : '';
            $remarks = isset($value[3]) ? trim($value[3]) : '';

            // Update the database if `imei` or `sno` matches
            Replace::where('imei', $sno)
                ->orWhere('sno', $sno)
                ->update([
                        'void' => $void,
                        'remarks' => $remarks,
                        'service_status' => 'Cancel',
                        'received' => $received_date,
                    ]);
        }

        return redirect()->back()->with('success', 'Products have been updated successfully.');
    }


    public function bulkUpload12(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Retrieve the file path
        $path = $request->file('csv_file')->getRealPath();

        // Read and process the CSV file
        $data = array_map('str_getcsv', file($path));

        // Ensure the file is not empty and has data
        if (count($data) <= 1) {
            return redirect()->back()->with('error', 'The uploaded file is empty or has invalid data.');
        }

        // Extract the rows, skipping the header row
        $csv_data = array_slice($data, 1);

        foreach ($csv_data as $value) {
            // Ensure the row has at least the `sno` column (as it is required for matching)
            if (empty($value[0])) {
                continue;
            }

            $sno = trim($value[0]);
            $received_date = isset($value[1]) ? trim($value[1]) : '';
            $remarks = isset($value[2]) ? trim($value[2]) : '';

            // Update the database if `imei` or `sno` matches
            Replace::where('imei', $sno)
                ->orWhere('sno', $sno)
                ->update([
                        'remarks' => $remarks,
                        'service_status' => 'Receive',
                        'received' => $received_date,
                    ]);
        }

        return redirect()->back()->with('success', 'Products have been updated successfully.');
    }


    public function bulkUpload0(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Retrieve the file path
        $path = $request->file('csv_file')->getRealPath();

        // Read and process the CSV file
        $data = array_map('str_getcsv', file($path));

        // Ensure the file is not empty and has data
        if (count($data) <= 1) {
            return redirect()->back()->with('error', 'The uploaded file is empty or has invalid data.');
        }

        // Extract the rows, skipping the header row
        $csv_data = array_slice($data, 1);

        foreach ($csv_data as $value) {
            // Ensure the row has at least the `sno` column (as it is required for matching)
            if (empty($value[0])) {
                continue;
            }

            $sno = trim($value[0]);
            $received_date = isset($value[1]) ? trim($value[1]) : '';
            $remarks = isset($value[2]) ? trim($value[2]) : '';

            // Update the database if `imei` or `sno` matches
            Replace::where('imei', $sno)
                ->orWhere('sno', $sno)
                ->update([
                        'remarks' => $remarks,
                        'service_status' => 'Checking',
                        'received' => $received_date,
                    ]);
        }

        return redirect()->back()->with('success', 'Products have been updated successfully.');
    }

    //Deliver Product Start
    public function bulkUpload6(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $csv_data = array_slice($data, 1);

        $replacementsToUpdate = [];
        $smsDetailsToUpdate = [];

        foreach ($csv_data as $value) {

            $sno = trim($value[0]);
            $new_sno = trim($value[1]);
            $received_date = isset($value[2]) ? trim($value[2]) : '';
            $delivery_date = isset($value[3]) ? trim($value[3]) : '';
            $remarks = isset($value[4]) ? trim($value[4]) : '';

            // Fetch stock info
            $stock = Stock::where('sno', $new_sno)->orWhere('imei', $new_sno)->first();

            // If no stock found for new_sno, skip the row
            if (!$stock) {
                continue;
            }

            // Determine the value of $imei2 based on the match
            $new_sno2 = ($stock->sno === $new_sno) ? $stock->imei : $stock->sno;

            // Get smsdetail_id for the replacement entry
            $smsdetail_id = Replace::where('sno', $sno)->orWhere('imei', $sno)->value('smsdetail_id');

            // Prepare Smsdetail update only if it exists
            if ($smsdetail_id) {
                $smsDetailsToUpdate[] = [
                    'id' => $smsdetail_id,
                    'sno' => $new_sno,
                    'imei' => $new_sno2,
                ];
            }

            // Prepare Replace update data
            $replacementsToUpdate[] = [
                'sno' => $sno,
                'imei' => $sno,
                'remarks' => $remarks,
                'service_status' => 'Approved & Delivered',
                'received' => $received_date,
                'delivery_date' => $delivery_date,
            ];
        }

        // Bulk update Smsdetail records
        if (!empty($smsDetailsToUpdate)) {
            foreach ($smsDetailsToUpdate as $updateData) {
                Smsdetail::where('id', $updateData['id'])->update([
                    'sno' => $updateData['sno'],
                    'imei' => $updateData['imei'],
                ]);
            }
        }

        // Bulk update Replace records
        if (!empty($replacementsToUpdate)) {
            foreach ($replacementsToUpdate as $updateData) {
                Replace::where('sno', $updateData['sno'])
                    ->orWhere('imei', $updateData['imei'])
                    ->update([
                            'remarks' => $updateData['remarks'],
                            'service_status' => $updateData['service_status'],
                            'received' => $updateData['received'],
                            'delivery_date' => $updateData['delivery_date'],
                        ]);
            }
        }

        return redirect()->back()->with('success', 'Products have been updated successfully.');
    }



    public function download()
    {
        $replaces = Replace::where('created_at', '>', '2025-01-27 23:59:59')->get();

        $receiveReport = $replaces->map(function ($replace) {
            return [
                'smsdetail_id' => $replace->smsdetail_id,
                'user_id' => $replace->user_id,
                'memo' => $replace->memo,
                'imei' => $replace->imei,
                'sno' => $replace->sno,
                'replace_imei2' => $replace->replace_imei2,
                'product_id' => $replace->product_id,
                'brand_id' => $replace->brand_id,
                'contact_name' => $replace->contact_name,
                'contact_no' => $replace->contact_no,
                'service_type' => $replace->service_type,
                'problem' => $replace->problem,
                'service_status' => $replace->service_status,
                'created_at' => $replace->created_at

            ];
        });

        // Generate and download the Excel file
        return (new FastExcel($receiveReport))->download('28th-all.xlsx');


    }





    public function bulkUploadBaad(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        if (count($data) <= 1) {
            return redirect()->back()->with('error', 'CSV file is empty or has no valid data.');
        }

        $csv_data = array_slice($data, 1);
        $insertData = [];

        foreach ($csv_data as $value) {
            if (count($value) < 13) {
                continue;
            }

            $insertData[] = [
                'smsdetail_id' => $value[0],
                'user_id' => $value[1],
                'memo' => $value[2],
                'imei' => $value[3],
                'sno' => $value[4],
                'replace_imei2' => $value[5],
                'product_id' => $value[6],
                'brand_id' => $value[7],
                'contact_name' => $value[8],
                'contact_no' => $value[9],
                'service_type' => $value[10],
                'problem' => $value[11],
                'service_status' => 'Pending',
                'received' => null,
                'void' => null,
                'delivery_date' => null,
                'remarks' => null,
            ];
        }

        if (!empty($insertData)) {
            try {
                DB::table('replaces')->insert($insertData);
                return redirect()->back()->with('success', 'Products have been uploaded successfully.');
            } catch (\Exception $e) {
                Log::error('Bulk Insert Failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Error inserting data: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'No valid data found in the CSV file.');
    }


    public function bulkUploadView()
    {
        return view('service_management.bulk_upload');
    }

    public function bulkUpload(Request $request)
    {
        $type = $request->type;

        if ($type == 1) {
            // Validate the uploaded file
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            ]);

            // Retrieve the file path
            $path = $request->file('csv_file')->getRealPath();

            // Read and process the CSV file
            $data = array_map('str_getcsv', file($path));

            // Ensure the file is not empty and has data
            if (count($data) <= 1) {
                return redirect()->back()->with('error', 'The uploaded file is empty or has invalid data.');
            }

            // Extract the rows, skipping the header row
            $csv_data = array_slice($data, 1);

            foreach ($csv_data as $value) {
                // Ensure the row has at least the `sno` column (as it is required for matching)
                if (empty($value[0])) {
                    continue;
                }

                $replace_id = $value[0];
                $received_date = isset($value[1]) ? trim($value[1]) : '';
                $remarks = isset($value[2]) ? trim($value[2]) : '';

                Replace::find($replace_id)->update([
                    'remarks' => $remarks,
                    'service_status' => 'Receive',
                    'received' => $received_date,
                ]);

            }

            return redirect()->back()->with('success', 'Products have been updated successfully.');
        } else if ($type == 2) {
            // Validate the uploaded file
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            ]);

            // Retrieve the file path
            $path = $request->file('csv_file')->getRealPath();

            // Read and process the CSV file
            $data = array_map('str_getcsv', file($path));

            // Ensure the file is not empty and has data
            if (count($data) <= 1) {
                return redirect()->back()->with('error', 'The uploaded file is empty or has invalid data.');
            }

            // Extract the rows, skipping the header row
            $csv_data = array_slice($data, 1);

            foreach ($csv_data as $value) {
                // Ensure the row has at least the `sno` column (as it is required for matching)
                if (empty($value[0])) {
                    continue;
                }

                $replace_id = $value[0];
                $received_date = isset($value[1]) ? trim($value[1]) : '';
                $remarks = isset($value[2]) ? trim($value[2]) : '';

                Replace::find($replace_id)->update([
                    'remarks' => $remarks,
                    'service_status' => 'Checking',
                    'received' => $received_date,
                ]);

            }

            return redirect()->back()->with('success', 'Products have been updated successfully.');
        } else if ($type === 3) {
            // Validate the uploaded file
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            ]);

            // Retrieve the file path
            $path = $request->file('csv_file')->getRealPath();

            // Read and process the CSV file
            $data = array_map('str_getcsv', file($path));

            // Ensure the file is not empty and has data
            if (count($data) <= 1) {
                return redirect()->back()->with('error', 'The uploaded file is empty or has invalid data.');
            }

            // Extract the rows, skipping the header row
            $csv_data = array_slice($data, 1);

            foreach ($csv_data as $value) {
                // Ensure the row has at least the `sno` column (as it is required for matching)
                if (empty($value[0])) {
                    continue;
                }

                $replace_id = $value[0];
                $received_date = isset($value[1]) ? trim($value[1]) : '';
                $void = isset($value[2]) ? trim($value[2]) : '';
                $remarks = isset($value[3]) ? trim($value[3]) : '';

                Replace::find($replace_id)->update([
                    'void' => $void,
                    'remarks' => $remarks,
                    'service_status' => 'Cancel',
                    'received' => $received_date,
                ]);


            }

            return redirect()->back()->with('success', 'Products have been updated successfully.');
        } else if ($type === 4) {
            // Validate the uploaded file
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            ]);

            // Retrieve the file path
            $path = $request->file('csv_file')->getRealPath();

            // Read and process the CSV file
            $data = array_map('str_getcsv', file($path));

            // Ensure the file is not empty and has data
            if (count($data) <= 1) {
                return redirect()->back()->with('error', 'The uploaded file is empty or has invalid data.');
            }

            // Extract the rows, skipping the header row
            $csv_data = array_slice($data, 1);

            foreach ($csv_data as $value) {
                // Ensure the row has at least the `sno` column (as it is required for matching)
                if (empty($value[0])) {
                    continue;
                }

                $replace_id = $value[0];
                $received_date = isset($value[1]) ? trim($value[1]) : '';
                $void = isset($value[2]) ? trim($value[2]) : '';
                $remarks = isset($value[3]) ? trim($value[3]) : '';
                $delivery_date = isset($value[4]) ? trim($value[4]) : '';

                Replace::find($replace_id)->update([
                    'void' => $void,
                    'remarks' => $remarks,
                    'service_status' => 'Cancel & Deliverd',
                    'received' => $received_date,
                    'delivery_date' => $delivery_date
                ]);


            }

            return redirect()->back()->with('success', 'Products have been updated successfully.');
        }



    }


}
