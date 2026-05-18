<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderPostingService;
use App\Services\OrderService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Mpdf\Mpdf;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    protected $orderPostingService;
    protected $orderService;
    public function __construct(OrderPostingService $orderPostingService, OrderService $orderService)
    {
        $this->orderPostingService = $orderPostingService;
        $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::paginate();

        return view('order.index', compact('orders'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $order = new Order();
        return view('order.create', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Order::$rules);

        $order = Order::create($request->all());

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);

        return view('order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);

        return view('order.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        request()->validate(Order::$rules);

        $order->update($request->all());

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $order = Order::find($id)->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully');
    }

    public function orderList(Request $request)
    {
        $search = $request->query('search');

        $result = $this->orderPostingService->list($search);

        $ordersposting = $result['orders'];
        $queryarray = $result['query'];

        return view('admin.order.list', compact('ordersposting', 'queryarray'))
            ->with('i', (request()->input('page', 1) - 1) * $ordersposting->perPage());
    }

    public function orderEdit($id, OrderPostingService $orderPostingService)
    {
        $data = $orderPostingService->getEditData($id);
        return view('admin.order.edit', $data);
    }
    public function orderUpdate(Request $request, $id)
    {
        $this->orderPostingService->updateOrder($id, $request);

        return redirect()
            ->route('admin.orderList')
            ->with('success', 'Order updated successfully!');
    }

    public function orderListSearch(Request $request)
    {
        $request->validate([
            'order' => 'required'
        ]);

        $this->orderPostingService->storeOrderNumber($request->order);

        return redirect()->route('admin.orderList');
    }
    public function orderDelete(Request $request)
    {
        $response = $this->orderPostingService->destroy($request->id);

        if ($response instanceof RedirectResponse) {
            return $response; // return error redirect from service
        }

        return redirect()->back()->with('success', 'Order deleted successfully!');
    }

    public function orderChangeStatus(Request $request)
    {
        $response = $this->orderPostingService->statusUpdate($request->id, $request->status);

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return redirect()->back()->with('success', 'Order status changed successfully!');
    }

    public function tsoOrderList(Request $request)
    {
        $queryarray = $request->query('search');
        $orderList = $this->orderService->tsoOrderList($queryarray);
        return view('tso.orader.list', compact('orderList', 'queryarray'));
    }

    public function tsoOrderCreate()
    {
        $data = $this->orderService->createTsoOrder();
        return view('tso.orader.create', [
            'upazilas' => $data['distributors'],
            'productList' => $data['products']
        ]);
    }

    public function tsoOrderStore(Request $request)
    {
        $validated = $request->validate([
            'upazila_id' => 'required|numeric|min:1',
            'quintity' => 'required|array|min:1',
            'quintity.*' => 'required|numeric|min:1',
            'model' => 'required|array|min:1',
            'model.*' => 'required|numeric|distinct|min:1',
            'remarks' => 'nullable',
        ]);
        $order = $this->orderService->storeTsoOrder($validated);
        return redirect()->route('tso.details', ['orader_no' => $order->id])
            ->with('success', 'Order Created Successfully.');
    }
    public function tsoOrderDetails($orader_no)
    {
        $data = $this->orderService->orderDetails($orader_no);
        $orader = $data['order'];
        $oraderdetails = $data['orderDetails'];
        $postings = $data['postings'];
        return view('tso.orader.show', compact('orader', 'oraderdetails', 'postings'));
    }

    public function printOrder($orader_no)
    {
        $data = $this->orderService->orderDetails($orader_no);
        $orader = $data['order'];
        $oraderdetails = $data['orderDetails'];
        $postings = $data['postings'];

        $html = view('tso.orader.pdf', compact('orader', 'oraderdetails', 'postings'))->render();

        $mpdf = new Mpdf([
            'margin_top' => 20,
            'margin_bottom' => 15,
            'margin_left' => 18,
            'margin_right' => 18,
            'format' => 'A4',
            'default_font_size' => 12,
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('Invoice_' . '.pdf', 'I');
    }
    public function tsoOrderDestroy($orader_no)
    {
        $this->orderService->orderDelete($orader_no);
        return redirect()->back()->with('success', 'Order deleted successfully!');
    }
}
