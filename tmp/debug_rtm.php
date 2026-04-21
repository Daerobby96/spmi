<?php
use App\Models\Periode;
use App\Models\Temuan;
use App\Models\Audit;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$activePeriode = Periode::where('is_aktif', true)->first();
$id = $activePeriode ? $activePeriode->id : 'NONE';
echo "ACTIVE PERIODE: " . $id . " (" . ($activePeriode ? $activePeriode->nama : '-') . ")\n";

$findings = Temuan::with('audit')->get();
foreach($findings as $f) {
    $auditPeriode = $f->audit ? $f->audit->periode_id : 'NULL';
    echo "ID: " . $f->id . " | Kategori: " . $f->kategori . " | Status: " . $f->status . " | Audit Periode: " . $auditPeriode . "\n";
}

$rtmCount = \App\Models\RTM::where('periode_id', $id)->count();
echo "RTM COUNT FOR ACTIVE: " . $rtmCount . "\n";
