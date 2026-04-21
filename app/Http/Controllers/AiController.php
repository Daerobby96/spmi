<?php
namespace App\Http\Controllers;

use App\Services\AiService;
use Illuminate\Http\Request;

class AiController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Analyze Root Cause
     */
    public function analyzeRootCause(Request $request)
    {
        $request->validate(['text' => 'required|string|min:10']);
        $result = $this->aiService->analyzeRootCause($request->text);
        return response()->json($result);
    }

    /**
     * Suggest Recommendation
     */
    public function suggestRecommendation(Request $request)
    {
        $request->validate(['text' => 'required|string|min:10']);
        $result = $this->aiService->suggestCorrectiveAction($request->text);
        return response()->json($result);
    }

    /**
     * Summarize Text
     */
    public function summarize(Request $request)
    {
        $request->validate(['text' => 'required|string|min:10']);
        $result = $this->aiService->summarizeNarration($request->text);
        return response()->json($result);
    }

    /**
     * Generate Audit Executive Summary
     */
    public function generateAuditSummary(Request $request)
    {
        $audit = \App\Models\Audit::with('temuans')->findOrFail($request->audit_id);
        $findingTexts = $audit->temuans->map(fn($t) => "- [{$t->kategori}] {$t->uraian_temuan}")->join("\n");
        $unit = $audit->unit_yang_diaudit;
        
        $prompt = "Sebagai pakar SPMI (Sistem Penjaminan Mutu Internal), buat laporan **Ringkasan Eksekutif (Executive Summary)** untuk unit $unit berdasarkan temuan audit berikut:\n\n$findingTexts\n\n" .
                  "STRUKTUR JAWABAN (WAJIB):\n" .
                  "1. Gunakan tag HTML sederhana: <strong> untuk penebalan, <ul> dan <li> untuk poin-poin.\n" .
                  "2. Bagian 1: **Gambaran Kepatuhan Umum** (analisa tingkat ketaatan).\n" .
                  "3. Bagian 2: **Masalah Kritis** (soroti temuan yang paling berdampak).\n" .
                  "4. Bagian 3: **Saran Strategis** (langkah konkret peningkatan mutu).\n\n" .
                  "JANGAN gunakan Markdown triple backticks. Berikan langsung kontennya. Gunakan bahasa Indonesia resmi dan profesional.";
        
        $result = $this->aiService->generate($prompt);
        
        if ($result['status'] === 'success') {
            $audit->update(['ai_summary' => $result['data']]);
        }
        
        return response()->json($result);
    }
}
