<?php

namespace App\Notifications;

use App\Models\Temuan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class DeadlineReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $temuan;
    protected $daysRemaining;

    public function __construct(Temuan $temuan, $daysRemaining)
    {
        $this->temuan = $temuan;
        $this->daysRemaining = $daysRemaining;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('audit.temuan.show', [$this->temuan->audit_id, $this->temuan->id]);
        $prefix = $this->daysRemaining < 0 ? 'SUDAH MELEWATI' : 'MENDEKATI';

        return (new MailMessage)
            ->subject("PENGINGAT DEADLINE: {$this->temuan->kode_temuan} ({$prefix})")
            ->greeting('Halo, ' . $notifiable->name)
            ->line("Temuan audit {$this->temuan->kode_temuan} pada unit Anda " . ($this->daysRemaining < 0 ? "telah melewati deadline!" : "akan mencapai deadline dalam {$this->daysRemaining} hari."))
            ->line('Audit: ' . $this->temuan->audit->nama_audit)
            ->line('Batas Waktu: ' . $this->temuan->batas_tindak_lanjut->format('d M Y'))
            ->action('Lihat Detail Temuan', $url)
            ->line('Mohon segera menyelesaikan tindak lanjut untuk menghindari penurunan performa mutu.')
            ->salutation('Terima kasih, Tim SPMI');
    }

    public function toArray($notifiable): array
    {
        return [
            'temuan_id'      => $this->temuan->id,
            'kode_temuan'    => $this->temuan->kode_temuan,
            'days_remaining' => $this->daysRemaining,
            'url'            => route('audit.temuan.show', [$this->temuan->audit_id, $this->temuan->id]),
            'message'        => "Reminder: Deadline temuan {$this->temuan->kode_temuan} tinggal {$this->daysRemaining} hari.",
        ];
    }
}
