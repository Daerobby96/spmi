<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected $apiKey;
    protected $model;
    protected $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
        $this->model = config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    /**
     * Generate content using Groq AI (OpenAI Compatible)
     */
    public function generate($prompt)
    {
        if (!$this->apiKey) {
            return [
                'status' => 'error',
                'message' => 'API Key Groq belum dikonfigurasi. Silakan tambahkan GROQ_API_KEY di file .env.',
            ];
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1024,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['choices'][0]['message']['content'] ?? '';
                return [
                    'status' => 'success',
                    'data' => trim($text),
                ];
            }

            Log::error('Groq API Error: ' . $response->body());
            $errorData = $response->json();
            $errorMessage = $errorData['error']['message'] ?? 'Gagal terhubung ke layanan Groq AI.';
            
            return [
                'status' => 'error',
                'message' => 'Groq AI: ' . $errorMessage,
            ];

        } catch (\Exception $e) {
            Log::error('AiService Exception: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem saat menghubungi Groq AI.',
            ];
        }
    }

    /**
     * Analyze root cause based on finding description
     */
    public function analyzeRootCause($uraianTemuan)
    {
        $prompt = "Sebagai pakar SPMI, berikan analisa akar masalah (Root Cause) yang paling kritikal untuk temuan: \"$uraianTemuan\".\n\nATURAN OUTPUT:\n1. Maksimal 5 poin paling relevan.\n2. Gunakan format poin (•).\n3. Langsung ke isi, JANGAN gunakan salam pembuka atau penutup.\n4. Gunakan bahasa Indonesia profesional.";
        return $this->generate($prompt);
    }

    /**
     * Suggest corrective actions based on finding
     */
    public function suggestCorrectiveAction($uraianTemuan)
    {
        $prompt = "Sebagai pakar SPMI, berikan rencana tindakan koreksi yang konkret dan solutif untuk temuan: \"$uraianTemuan\".\n\nATURAN OUTPUT:\n1. Maksimal 5 poin tindakan.\n2. Gunakan format poin (•).\n3. Langsung ke isi, JANGAN gunakan salam pembuka atau penutup.\n4. Gunakan bahasa Indonesia instruksional.";
        return $this->generate($prompt);
    }

    /**
     * Summarize self-evaluation or audit narration
     */
    public function summarizeNarration($narration)
    {
        $prompt = "Ringkaslah narasi evaluasi diri berikut menjadi poin-poin eksekutif: \"$narration\".\n\nATURAN OUTPUT:\n1. Maksimal 4 poin utama.\n2. Gunakan format poin (•).\n3. JANGAN gunakan pembuka/penutup.\n4. Bahasa Indonesia profesional.";
        return $this->generate($prompt);
    }
}
