<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\Temuan;
use App\Models\User;
use App\Notifications\DeadlineReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDeadlineReminders extends Command
{
    protected $signature = 'spmi:send-reminders';
    protected $description = 'Kirim notifikasi pengingat untuk temuan yang mendekati deadline';

    public function handle()
    {
        $this->info('Memulai pengecekan deadline temuan...');

        // Ambil temuan yang masih open/in_progress dan punya deadline
        $temuans = Temuan::with(['audit'])
            ->whereIn('status', ['open', 'in_progress'])
            ->whereNotNull('batas_tindak_lanjut')
            ->get();

        $count = 0;

        foreach ($temuans as $temuan) {
            $deadline = Carbon::parse($temuan->batas_tindak_lanjut);
            $now = Carbon::now()->startOfDay();
            $daysRemaining = $now->diffInDays($deadline, false);

            // Kirim pengingat pada H-7, H-3, H-1, dan H-0, atau jika sudah lewat
            if (in_array($daysRemaining, [7, 3, 1, 0]) || $daysRemaining < 0) {
                
                // Cari auditee untuk unit ini
                $auditees = User::where('unit_kerja', $temuan->audit->unit_yang_diaudit)
                    ->whereHas('role', fn($q) => $q->where('name', Role::AUDITEE))
                    ->get();

                foreach ($auditees as $auditee) {
                    $auditee->notify(new DeadlineReminderNotification($temuan, $daysRemaining));
                    $count++;
                }
            }
        }

        $this->info("Pelesai! {$count} notifikasi telah dikirim.");
    }
}
