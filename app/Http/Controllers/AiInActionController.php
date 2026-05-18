<?php

namespace App\Http\Controllers;

use App\Models\Ordersposting;
use App\Orderspostingdetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class AiInActionController extends Controller
{
    /* =========================
       VIEW
       ========================= */
    public function index()
    {
        if (Auth::user()->email !== 'info@synergyinterface.com') {
            return redirect()->route('logout');
        }

        return view('action');
    }

    /* =========================
       MAIN CHAT ENTRY
       ========================= */
    public function execute(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500'
        ]);

        $prompt = trim($request->prompt);
        Log::channel('ai')->info('AI Prompt', ['prompt' => $prompt]);

       /* 🔑 CONFIRMATION SHORT-CIRCUIT */
        if (
            session()->has('ai.intent') &&
            preg_match('/\b(yes|confirm)\b/i', $prompt)
        ) {
            $intent = session('ai.intent');

            return $this->handleDeleteOrderIntent([
                'intent' => 'delete_order',
                'order_id' => $intent['order_id'],
                'confirmation' => 'yes'
            ]);
        }

        /* Awaiting Order ID */
        if (session()->get('ai.awaiting_order_id')) {
            return $this->handleAwaitingOrderId($prompt);
        }

        /* AI intent detection */
        $aiResponse = $this->callAi($prompt);


        if (!$aiResponse) {
            return response()->json([
                'message' => 'Sorry, I couldn’t process that.'
            ]);
        }

        $data = json_decode($aiResponse, true);

        $data = array_merge([
            'intent' => null,
            'order_id' => null,
            'confirmation' => 'no',
            'text' => null,
        ], is_array($data) ? $data : []);

        if (!$data['intent']) {
            return response()->json([
                'message' => 'Sorry, I couldn’t understand that.'
            ]);
        }

        /* STEP 3: NATURAL MESSAGE */
        if ($data['intent'] === 'message') {
            return response()->json([
                'message' => $data['text']
            ]);
        }

        /* STEP 4: DELETE ORDER */
        if ($data['intent'] === 'delete_order') {
            return $this->handleDeleteOrderIntent($data);
        }

        return response()->json([
            'message' => 'I can only help with order-related requests.'
        ]);
    }

    /* =========================
       AWAITING ORDER ID
       ========================= */
    private function handleAwaitingOrderId(string $prompt)
    {
        if ($this->isCancelIntent($prompt)) {
            session()->forget(['ai.awaiting_order_id', 'ai.intent']);

            return response()->json([
                'message' => 'Okay, I’ve cancelled the delete request.'
            ]);
        }

        $orderId = $this->extractOrderId($prompt);

        if (!$orderId) {
            return response()->json([
                'message' => 'You can tell me the order ID whenever you’re ready.'
            ]);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'message' => 'I couldn’t find that order. Please provide a valid order ID.'
            ]);
        }

        session()->forget('ai.awaiting_order_id');
        session()->put('ai.intent', [
            'action' => 'delete_order',
            'order_id' => $orderId
        ]);

        return response()->json([
            'message' => "Order {$orderId} will be deleted. Please confirm."
        ]);
    }

    /* =========================
       DELETE ORDER INTENT
       ========================= */
    private function handleDeleteOrderIntent(array $data)
    {
        if (
            in_array(strtolower($data['confirmation']), ['yes', 'confirm']) &&
            empty($data['order_id']) &&
            session()->has('ai.intent')
        ) {
            $data['order_id'] = session('ai.intent.order_id');
        }

        if (empty($data['order_id'])) {
            session()->put('ai.awaiting_order_id', true);

            return response()->json([
                'message' => 'Sure — which order ID would you like to delete?'
            ]);
        }

        $order = Order::find($data['order_id']);

        if (!$order) {
            session()->put('ai.awaiting_order_id', true);

            return response()->json([
                'message' => 'I couldn’t find that order. Please provide a valid order ID.'
            ]);
        }

        if (!in_array(strtolower($data['confirmation']), ['yes', 'confirm'])) {

            session()->put('ai.intent', [
                'action' => 'delete_order',
                'order_id' => $data['order_id']
            ]);

            return response()->json([
                'message' => "Order {$data['order_id']} will be deleted. Please confirm."
            ]);
        }

        $this->performDeleteOrder($data['order_id']);

        session()->forget(['ai.intent', 'ai.awaiting_order_id']);

        Log::channel('ai')->warning('Order deleted via AI', [
            'order_id' => $data['order_id'],
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    }

    /* =========================
       DELETE ORDER TRANSACTION
       ========================= */
    private function performDeleteOrder(int $orderId)
    {
        DB::transaction(function () use ($orderId) {

            $order = Order::with('orderposting.OrderspostingDetails')
                ->find($orderId);

            if (!$order) return;

            $snapshot = [
                'type' => 'DELETE_ORDER',
                'tables' => [
                    'orders' => $order->toArray(),
                    'orderspostings' => $order->orderposting
                        ? $order->orderposting->toArray()
                        : null,
                    'orderspostingdetails' => $order->orderposting
                        ? $order->orderposting->OrderspostingDetails->toArray()
                        : []
                ]
            ];

            DB::table('ai_action_logs')->insert([
                'action' => 'DELETE_ORDER',
                'resource_id' => $order->id,
                'snapshot' => json_encode($snapshot),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if ($order->orderposting) {
                $order->orderposting->OrderspostingDetails()->delete();
                $order->orderposting->delete();
            }

            $order->delete();
        });
    }

    /* =========================
       UNDO
       ========================= */
    public function undoLast($id)
    {
        $log = DB::table('ai_action_logs')->find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Nothing to undo'
            ]);
        }

        $snapshot = json_decode($log->snapshot, true);

        DB::transaction(function () use ($snapshot, $log) {

            if ($snapshot['type'] === 'DELETE_ORDER') {
                $this->undoDeleteOrder($snapshot);
            }

            DB::table('ai_action_logs')
                ->where('id', $log->id)
                ->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Last action has been undone successfully'
        ]);
    }

    private function undoDeleteOrder(array $snapshot)
    {
        $orderData = $snapshot['tables']['orders'];
        unset($orderData['id'], $orderData['orderposting']);

        $order = Order::create($orderData);

        if (!empty($snapshot['tables']['orderspostings'])) {
            $postingData = $snapshot['tables']['orderspostings'];
            unset($postingData['id']);

            $postingData['orader_number'] = $order->id;
            $posting = Ordersposting::create($postingData);

            foreach ($snapshot['tables']['orderspostingdetails'] as $detail) {
                unset($detail['id']);
                $detail['orderspostings_id'] = $posting->id;
                Orderspostingdetail::create($detail);
            }
        }
    }

    /* =========================
       HISTORY
       ========================= */
    public function todayHistory()
    {
        $logs = DB::table('ai_action_logs')
            ->whereDate('created_at', date('Y-m-d'))
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($logs);
    }

    /* =========================
       AI CALL (ORIGINAL — UNCHANGED)
       ========================= */
    private function callAi($prompt)
    {
        $apiKey = config('services.gemini.key');

        if (!$apiKey) {
            Log::channel('ai')->error('Gemini API key missing');
            return null;
        }

        $systemPrompt = $this->buildSystemPrompt($prompt);

        $payload = json_encode([
            'contents' => [
                [
                    'parts' => [['text' => $systemPrompt]]
                ]
            ]
        ]);

        $ch = curl_init(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}"
        );

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 15
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) return null;

        $decoded = json_decode($response, true);
        $text = data_get($decoded, 'candidates.0.content.parts.0.text');

        if (!$text) return null;

        Log::channel('ai')->info('AI Raw Response', ['response' => $text]);

        return $text;
    }

    /* =========================
       SYSTEM PROMPT
       ========================= */
    private function buildSystemPrompt($userPrompt)
    {
        return <<<PROMPT
        You are an internal assistant who will help user with DMS tasks.
        If the user greets you or says thanks, respond politely according to the context.

        You MUST respond in valid JSON only.

        FORMAT A (Natural message):
        {
        "intent": "message",
        "order_id": null,
        "confirmation": "no",
        "text": "<natural response>"
        }

        FORMAT B (Delete order intent):
        {
        "intent": "delete_order",
        "order_id": "<order_id or null>",
        "confirmation": "no"
        }

        FORMAT C (Delete confirmation):
        {
        "intent": "delete_order",
        "order_id": "<order_id>",
        "confirmation": "yes"
        }

        RULES:
        - Use FORMAT C only when the user clearly confirms.
        - If order ID is unknown, set order_id to null.
        - Never explain actions.
        - Never include markdown.
        - Never include extra text.
        - Only discuss order-related topics.

        User input:
        {$userPrompt}
        PROMPT;
    }

    /* =========================
       HELPERS
       ========================= */
    private function extractOrderId($text)
    {
        preg_match('/\b\d+\b/', $text, $m);
        return $m[0] ?? null;
    }

    private function isCancelIntent($text)
    {
        return preg_match('/\b(cancel|stop|never mind|abort)\b/i', $text);
    }
}
