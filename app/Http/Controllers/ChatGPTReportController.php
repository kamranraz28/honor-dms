<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;


class ChatGPTReportController extends Controller
{

    public function generateReportShow()
    {
        return view('generate-order-report');
    }


    public function generateReport(Request $request)
    {
        // Get the natural language prompt
        $prompt = $request->input('prompt');

        DB::table('prompts')->insert([
            'prompt' => $prompt,
        ]);
        $prompts = DB::select('SELECT * FROM prompts ORDER BY id DESC');

        // --- Start Prompt Pre-processing ---
        // Map readable statuses to numeric values
        $statusMap = [
            'complete' => 5,
            'pending' => 1,
            'processing' => 3,
            'draft' => 0,
        ];

        foreach ($statusMap as $word => $value) {
            $prompt = preg_replace("/\b$word\b/i", "status=$value", $prompt);
        }

        $userMap = [
            'distributor' => 100,
            'ld' => 100,
            'dealer' => 100,
            'retail' => 200,
            'retailer' => 200,
            'store' => 200,
            'telecom' => 200,
            'shop' => 200,
        ];

        // Normalize role terms to `level=X`
        foreach ($userMap as $term => $level) {
            $prompt = preg_replace("/\b$term\b/i", "level=$level", $prompt);
        }

        // Region replacements: convert "from Chattagram division" or "from Cox's Bazar district" or "from Hathazari upazila" to IDs
        $prompt = preg_replace_callback('/from\s+([a-zA-Z\s]+?)\s+(division|district|upazila)/i', function ($matches) {
            $regionType = strtolower($matches[2]); // division, district, upazila
            $regionName = trim($matches[1]);

            if ($regionType === 'division') {
                $region = DB::table('divisions')->whereRaw('LOWER(name) = ?', [strtolower($regionName)])
                ->first();
                if ($region) {
                    return "division_id={$region->id}";
                }
            } elseif ($regionType === 'district') {
                $region = DB::table('districts')->whereRaw('LOWER(name) = ?', [strtolower($regionName)])->first();
                if ($region) {
                    return "district_id={$region->id}";
                }
            } elseif ($regionType === 'upazila') {
                $region = DB::table('upazilas')->whereRaw('LOWER(name) = ?', [strtolower($regionName)])->first();
                if ($region) {
                    return "upazila_id={$region->id}";
                }
            }

            return $matches[0];
        }, $prompt);


        // Define the database schema structure
        $schemaDefinition = file_get_contents(storage_path('schema.txt'));

        $systemPrompt = <<<EOD
        You are an expert SQL query generator.

        You will be given a user's natural language request. Your task is to generate a valid, optimized SQL query strictly using only the tables, columns, and relationships defined in the following database schema:

        $schemaDefinition

        Strict guidelines you must follow:
        - ONLY output the raw SQL query — no markdown, no formatting wrappers like triple backticks, no explanations.
        - Use only table and column names exactly as defined in the schema.
        - All joins must follow the relationships in the schema.
        - Do NOT assume or invent columns, tables, or relationships.
        - If the query is complex, format it with line breaks and aliases for readability.
        - If selecting from orders, always join with users to fetch firstname, officeid. not show upazila_id and replace status value (1 to pending, 3 to processing, 5 to complete, 0 to draft) and replace (id to Order Number),
        - If selecting from stocks, always join with products to fetch name, model and brands to fetch name.
        - If selecting from purchases, always join with products to fetch name, model and user to fetch firstname, officeid
        - If selecting from smsdetails, always join with products to fetch name, model and user to fetch firstname, officeid and in response replace (firstanme to Retailer Name, officeid to Retailer ID)
        - If user prompt has "tertiary" that's for smsdetails table and "primary" for purchases table and secondary for sales table.
        - If the query involves the sales table, always join with the products table to fetch the product's name and model, and join with the users table twice to fetch the firstname and officeid of both the user (user_id) and the retailer (ruser_id). If the prompt contains the word “retail” or “retailer”, ensure that conditions or filters are applied on the ruser relationship.
        - If the prompt asks for “most purchased product”, generate a query that finds the product_id with the highest total quantity from the purchases table, and return the product’s name, model, brand name, and total purchased quantity.
        - If the prompt asks for “most sold product”, generate a query that finds the product_id with the highest total quantity from the sales table, and return the product’s name, model, brand name, and total sold quantity.
        - If the prompt asks for “most smsdetails product”, generate a query that finds the product_id with the highest total quantity from the smsdetails table, and return the product’s name, model, and total quantity.
        - If the prompt asks for "life cycle report", generate a query that finds given imei in stocks table (check imei and sno column) and fetch product name and model, show it's either available in stocks or not, then check on purchases table, then sales table. Give response with the created_at for that table with user(distributor) and ruser(retailer) information. response column (# for numbering (not the id, just a serial nuumbering), Product Model, IMEI-1 for sno, IMEI-2 for imei, Stock Date for stocks.created_at, Distributor Name (ID) for purchases.user_id, Primary Date for purchases.created_at,  Retailer Name (ID) for sales.ruser_id, Secondary Date for sales.created_at, Tertiary Date for smsdetails.created_at) only.
        - If the prompt contains the words "prediction", "forecast", or "future", fetch product models from the products table and calculate total quantities per product from stocks (total stocks till date including sold also), purchases (Primary Sales: Warehouse to Dealer), sales (Secondary Sales: Dealer to Retailer), smsdetails (Tertiary Sales: Retailer to Customer), replaces (count of replaced units, re-entering market), and preturns (count of returned units, not resold). For each product, analyze all these numbers to generate a unique and realistic prediction response named "Prediction" that includes how the product is likely to perform in the future, what trends are observed, what factors are helping or hurting sales, and specific suggestions on how to improve performance. The prediction must be different per product based on actual data behavior and market flow and definitely (obvious) include to know which product will run high or low in market for next three months. Current stock is (total stock - purchases) only, other sales data are already in purchases
        - for all tables in response add Number (numbering) at first column and replace (number to #, user to distributor, ruser to retailer, firstname to name, officeid to id, sno to imei-1, imei to imei-2, created_at to sale date) not take not any tables id.
        Output format: just the SQL query as plain text. No extra characters or lines.


        Use standard MySQL amd optimized query.
EOD;

        // --- END Prompt Pre-processing and System Prompt Definition ---


        // --- PROXY CALL START ---
        $proxyUrl = 'https://synergyinterface.com/proxiApi.php';
        $sqlQuery = null;

        if (empty($proxyUrl)) {
            Log::error('SQL_PROXY_URL is not set.');
            $sqlQuery = null;
        } else {
            $payload = [
                // Combine system instruction and user prompt into a single text string for the proxy
                'prompt_text' => $systemPrompt . "\n\nUser Request: " . $prompt,
            ];

            // --- cURL Request to the Proxy ---
            $ch = curl_init($proxyUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            // 🛑 SOLUTION: DISABLE SSL CHECK 🛑
            // This bypasses the "unable to get local issuer certificate" error.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);    
            // ----------------------------------

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            $responseBody = curl_exec($ch);
            $curlErr = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            //dd($responseBody);

            // 3. Handle the Proxy's Response
            // The curl_exec failure logic remains, though it should be caught here now.
            if ($responseBody === false || $curlErr) {
                // Now this block will catch any *other* potential failure (e.g., network timeout)
                Log::error('Error calling SQL Proxy: ' . $curlErr);
                $sqlQuery = null;
            } else {
                $body = json_decode($responseBody, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Invalid JSON from SQL Proxy. Raw response: ' . $responseBody);
                    $sqlQuery = null;
                } elseif (isset($body['error'])) {
                    // Proxy relayed an API error (e.g., from Gemini)
                    Log::error('SQL Proxy Error: ' . ($body['error']['message'] ?? 'Unknown API Error'));
                    $sqlQuery = null;
                } elseif (isset($body['sql']) && is_string($body['sql'])) {
                    // SUCCESS: Extract the generated SQL query string
                    $sqlQuery = $body['sql'];
                } else {
                    // Unexpected successful response format (e.g., JSON received but no 'sql' key)
                    Log::error('Unexpected successful response format from SQL Proxy. Body: ' . $responseBody);
                    $sqlQuery = null;
                }
            }
        }
        // --- PROXY CALL END ---

        // Assign the extracted SQL (or null) to the $query variable
        $query = $sqlQuery;


        // Clean unwanted phrases and format the query
        if (!empty($query)) {
            // Remove markdown fences and normalize whitespace
            $query = preg_replace('/^```(?:sql)?\s*|\s*```$/i', '', $query);
            $query = preg_replace('/[\r\t]+/', ' ', $query);
            $query = preg_replace('/\s+/', ' ', $query);
            $query = preg_replace(
                "/(\b(?:o|orders|stocks|purchases|sales|smsdetails)\.created_at)\s*=\s*'(\d{4}-\d{2}-\d{2})'/i",
                "DATE($1) = '$2'",
                $query
            );

            // Clean unwanted phrases and format the query
            $query = preg_replace('/^(Sure|Here|Please|This).*|```.*```/is', '', $query);
            $query = str_replace(['OrderDate', 'Created_Date', 'order_date'], 'created_at', $query);
            $query = str_replace(['Orders'], 'orders', $query);
            $query = trim($query);
        }

        // Validate SQL query before execution
        if (empty($query)) {
            return view('generate-order-report', ['report' => 'Error: Generated SQL query is empty.']);
        }

        if (!preg_match('/^(SELECT|UPDATE|INSERT|DELETE)\s+/i', $query)) {
            return view('generate-order-report', ['report' => 'Error: Invalid SQL query generated. Query: ' . $query]);
        }

        // Execute the SQL query securely
        try {
            $results = DB::select($query);

            if (empty($results)) {
                DB::table('prompts')->where('prompt', $prompt)->update(['status' => 'No']);
            }
        } catch (\Exception $e) {
            Log::error('SQL Execution Error: ' . $e->getMessage() . ' for query: ' . $query);
            DB::table('prompts')->where('prompt', $prompt)->update(['status' => 'No']);
            return view('generate-order-report', ['report' => 'Database Error: Could not execute the generated query.']);
        }


        $resultType = null; // default

        if (!empty($results)) {
            $firstRow = (array) $results[0];

            if (stripos($query, 'from purchases') !== false) {
                $resultType = 'purchases';
            } elseif (stripos($query, 'from sales') !== false) {
                $resultType = 'sales';
            } elseif (stripos($query, 'from smsdetails') !== false) {
                $resultType = 'smsdetails';
            } elseif (stripos($query, 'from stocks') !== false) {
                $resultType = 'stocks';
            } elseif (array_key_exists('order_id', $firstRow)) {
                $resultType = 'combined';
            } elseif (array_key_exists('firstname', $firstRow)) {
                $resultType = 'users';
            } elseif (stripos($query, 'from orders') !== false) {
                $resultType = 'orders';
            } elseif (stripos($query, 'Prediction') !== false) {
                $resultType = 'prediction';
            }
        }

        session()->flash('prompt', $request->prompt);
        //dd($prompts);

        // Convert results based on result type
        if ($resultType === 'orders') {
            $orders = collect($results);
            $report = json_encode($orders, JSON_PRETTY_PRINT);

            return view('generate-order-report', [
                'report' => json_decode($orders, true),
                'users' => null,
                'combined' => null,
                'stocks' => null,
                'purchases' => null,
                'sales' => null,
                'smsdetails' => null,
                'prediction' => null,
                'prompts' => $prompts,
            ]);
        } elseif ($resultType === 'users') {
            $users = collect($results);
            $report = json_encode($users, JSON_PRETTY_PRINT);

            return view('generate-order-report', [
                'users' => json_decode($report, true),
                'report' => null,
                'combined' => null,
                'stocks' => null,
                'purchases' => null,
                'sales' => null,
                'smsdetails' => null,
                'prediction' => null,
                'prompts' => $prompts,
            ]);
        } elseif ($resultType === 'stocks') {
            $stocks = collect($results);
            $report = json_encode($stocks, JSON_PRETTY_PRINT);

            return view('generate-order-report', [
                'stocks' => json_decode($report, true),
                'users' => null,
                'report' => null,
                'combined' => null,
                'purchases' => null,
                'sales' => null,
                'smsdetails' => null,
                'prediction' => null,
                'prompts' => $prompts,
            ]);
        } elseif ($resultType === 'purchases') {
            $purchases = collect($results);
            $report = json_encode($purchases, JSON_PRETTY_PRINT);

            return view('generate-order-report', [
                'purchases' => json_decode($report, true),
                'users' => null,
                'report' => null,
                'combined' => null,
                'stocks' => null,
                'sales' => null,
                'smsdetails' => null,
                'prediction' => null,
                'prompts' => $prompts,
            ]);
        } elseif ($resultType === 'sales') {
            $sales = collect($results);
            $report = json_encode($sales, JSON_PRETTY_PRINT);

            return view('generate-order-report', [
                'sales' => json_decode($report, true),
                'users' => null,
                'report' => null,
                'combined' => null,
                'stocks' => null,
                'purchases' => null,
                'smsdetails' => null,
                'prediction' => null,
                'prompts' => $prompts,
            ]);
        } elseif ($resultType === 'smsdetails') {
            $smsdetails = collect($results);
            $report = json_encode($smsdetails, JSON_PRETTY_PRINT);

            return view('generate-order-report', [
                'smsdetails' => json_decode($report, true),
                'users' => null,
                'report' => null,
                'combined' => null,
                'stocks' => null,
                'purchases' => null,
                'sales' => null,
                'prediction' => null,
                'prompts' => $prompts,
            ]);
        } elseif ($resultType === 'prediction') {
            $prediction = collect($results);
            $report = json_encode($prediction, JSON_PRETTY_PRINT);

            return view('generate-order-report', [
                'prediction' => json_decode($report, true),
                'users' => null,
                'report' => null,
                'combined' => null,
                'stocks' => null,
                'purchases' => null,
                'sales' => null,
                'smsdetails' => null,
                'prompts' => $prompts,
            ]);
        } else {
            $combined = collect($results);
            return view('generate-order-report', [
                'combined' => $combined,
                'report' => null,
                'users' => null,
                'stocks' => null,
                'purchases' => null,
                'sales' => null,
                'smsdetails' => null,
                'prediction' => null,
                'prompts' => $prompts,
            ]);
        }

    }
}