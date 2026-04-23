<?php
foreach(App\Models\Periode::all() as $p) {
    echo $p->nama . ' => Tahun: ' . $p->tahun . ', Semester: ' . $p->semester . PHP_EOL;
}
