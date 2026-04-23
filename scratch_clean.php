<?php

$kuesioners = App\Models\Kuesioner::where('judul', 'like', '%Import dari Siakad%')
    ->orWhere('deskripsi', 'like', '%Import dari Siakad%')
    ->get()
    ->groupBy(function($k) { 
        return $k->judul . '|' . $k->periode_id . '|' . $k->deskripsi; 
    }); 
    
foreach($kuesioners as $group) { 
    if($group->count() > 1) { 
        $keep = $group->pop(); 
        foreach($group as $duplicate) { 
            $duplicate->delete(); 
        } 
        echo 'Deleted duplicates for: ' . $keep->judul . PHP_EOL; 
    } 
}
echo 'Cleanup complete.';
