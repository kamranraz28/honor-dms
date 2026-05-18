<?php

namespace App\Services;

class GeminiAiFixService
{
    public function analyze(string $prompt, string $controllerCode, string $bladeCode): ?array
    {
        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            $this->log('ERROR', 'Gemini API key missing');
            return null;
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        // ---- Build prompt (keep it strict & compact) ----
        $fullPrompt = <<<PROMPT
You are a senior Laravel engineer (PHP 7.1).

Error:
{$prompt}

Controller code:
{$controllerCode}

Blade view code:
{$bladeCode}

Rules:
- Return ONLY valid JSON
- No markdown
- No explanations
- One short sentence per report field

Return this exact structure:
{
  "diff": "...",
  "report": {
    "issue": "",
    "cause": "",
    "fix": "",
    "risk": "Low | Medium | High",
    "confidence": 1.0
  }
}
PROMPT;

        $this->log('FINAL_PROMPT', $fullPrompt);

        $payload = json_encode([
            'contents' => [[
                'parts' => [[ 'text' => $fullPrompt ]]
            ]]
        ]);

        // ---- Log outgoing request size ----
        $this->log('REQUEST_SIZE', strlen($payload) . ' bytes');

        // ---- Call Gemini ----
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if (!$response) {
            $this->log('CURL_ERROR', $curlError ?: 'Empty response');
            return null;
        }

        // ---- Log raw response ----
        $this->log('RAW_RESPONSE', $response);

        $decoded = json_decode($response, true);

        if (!is_array($decoded)) {
            $this->log('DECODE_ERROR', 'Invalid JSON from Gemini');
            return null;
        }

        // ---- Handle Gemini errors explicitly ----
        if (isset($decoded['error'])) {
            $this->log('GEMINI_ERROR', json_encode($decoded['error']));
            return null;
        }

        if (isset($decoded['promptFeedback'])) {
            $this->log('PROMPT_BLOCKED', json_encode($decoded['promptFeedback']));
            return null;
        }

        if (!isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
            $this->log('NO_CANDIDATE_TEXT', json_encode($decoded));
            return null;
        }

        $text = $decoded['candidates'][0]['content']['parts'][0]['text'];

        // ---- Log model text output ----
        $this->log('MODEL_TEXT', $text);

        // ---- Remove markdown fences ----
        $text = preg_replace('/```json|```/', '', $text);

        // ---- Extract JSON block only ----
        $start = strpos($text, '{');
        $end   = strrpos($text, '}');

        if ($start === false || $end === false) {
            $this->log('JSON_NOT_FOUND', $text);
            return null;
        }

        $jsonString = substr($text, $start, $end - $start + 1);

        $json = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->log('JSON_PARSE_ERROR', json_last_error_msg());
            $this->log('JSON_STRING', $jsonString);
            return null;
        }

        // ---- Validate required keys ----
        if (!isset($json['diff'], $json['report'])) {
            $this->log('INVALID_SCHEMA', json_encode($json));
            return null;
        }

        return $json;
    }

    /**
     * Simple logger for Gemini debugging
     */
    private function log(string $type, string $message): void
    {
        $line = '[' . date('Y-m-d H:i:s') . "] {$type}: {$message}\n";
        file_put_contents(
            storage_path('logs/gemini_ai_fix.log'),
            $line,
            FILE_APPEND
        );
    }
}
