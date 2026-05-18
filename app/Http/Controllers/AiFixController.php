<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use App\Services\GeminiAiFixService;

class AiFixController extends Controller
{
    public function index()
    {
        $fix = DB::table('ai_code_fixes')
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->first();

        if ($fix) {
            $fix->report = json_decode($fix->report, true);
        }

        return view('ai-fix.index', compact('fix'));
    }

    public function analyze(Request $request, GeminiAiFixService $ai)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        // Parse Laravel error
        $error = $this->parseLaravelError($request->prompt);

        if (!$error['variable'] || !$error['view']) {
            return back()->with('error', 'Unable to parse error');
        }

        // Resolve controller dynamically (example: ProductController)
        $controllerClass = 'App\\Http\\Controllers\\ProductController';

        if (!class_exists($controllerClass)) {
            return back()->with('error', 'Controller not found');
        }

        $reflection = new ReflectionClass($controllerClass);
        $controllerPath = $reflection->getFileName();

        $method = 'index';

        $controllerMethod = $this->extractControllerMethod($controllerPath, $method);
        $bladeSnippet = $this->extractBladeSnippet($error['view'], $error['variable']);

        $result = $ai->analyze(
            $request->prompt,
            $controllerMethod,
            $bladeSnippet
        );

        if (!$result || empty($result['diff'])) {
            return back()->with('error', 'AI analysis failed');
        }

        DB::table('ai_code_fixes')->insert([
            'prompt' => $request->prompt,
            'diff'   => $result['diff'], // semantic change, NOT patch
            'report' => json_encode($result['report']),
            'file'   => $controllerPath,
            'method' => $method,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('ai-fix.index');
    }

    public function approve($id)
    {
        $fix = DB::table('ai_code_fixes')->where('id', $id)->first();

        if (!$fix || !file_exists($fix->file)) {
            return back()->with('error', 'Fix target not found');
        }

        $fileContents = file_get_contents($fix->file);
        $methodCode   = $this->extractControllerMethod($fix->file, $fix->method);

        if (!$methodCode) {
            return back()->with('error', 'Method not found');
        }

        // Apply known fix (example for Undefined variable)
        $search  = "return view('products.index');";
        $replace = "return view('products.index', compact('products'));";

        if (strpos($methodCode, $search) === false) {
            return back()->with('error', 'Target line not found');
        }

        $updatedMethod = str_replace($search, $replace, $methodCode);
        $updatedFile   = str_replace($methodCode, $updatedMethod, $fileContents);

        file_put_contents($fix->file, $updatedFile);

        DB::table('ai_code_fixes')
            ->where('id', $id)
            ->update([
                'status' => 'approved',
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Fix applied successfully');
    }

    public function decline($id)
    {
        DB::table('ai_code_fixes')->where('id', $id)->update([
            'status' => 'declined',
            'updated_at' => now(),
        ]);

        return redirect()->route('ai-fix.index')
            ->with('success', 'Fix declined');
    }

    /* =======================
       Helper methods
       ======================= */

    private function parseLaravelError(string $error): array
    {
        preg_match('/Undefined variable:\s*(\w+)/', $error, $var);
        preg_match('/View:\s*([^)]+)/', $error, $view);

        return [
            'variable' => $var[1] ?? null,
            'view'     => $view[1] ?? null,
        ];
    }

    private function extractBladeSnippet(string $viewPath, string $variable, int $context = 3): string
    {
        $fullPath = resource_path('views/' . ltrim($viewPath, '/'));

        if (!file_exists($fullPath)) {
            return '';
        }

        $lines = file($fullPath);
        $result = [];

        foreach ($lines as $i => $line) {
            if (strpos($line, '$' . $variable) !== false) {
                for ($j = max(0, $i - $context); $j <= min(count($lines) - 1, $i + $context); $j++) {
                    $result[$j] = $lines[$j];
                }
            }
        }

        return implode('', $result);
    }

    private function extractControllerMethod(string $path, string $method): string
    {
        $code = file_get_contents($path);
        $pos  = strpos($code, 'function ' . $method);

        if ($pos === false) {
            return '';
        }

        $start = strpos($code, '{', $pos);
        $depth = 1;
        $i     = $start + 1;

        while ($i < strlen($code) && $depth > 0) {
            if ($code[$i] === '{') $depth++;
            if ($code[$i] === '}') $depth--;
            $i++;
        }

        return substr($code, $pos, $i - $pos);
    }
}
