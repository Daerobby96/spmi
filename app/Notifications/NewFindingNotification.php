<?php

namespace App\Notifications;

use App\Models\Temuan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewFindingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $temuan;

    public function __construct(Temuan $temuan)
    {
        $this->temuan = $temuan;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('audit.temuan.show', [$this->temuan->audit_id, $this->temuan->id]);

        return (new MailMessage)
            ->subject('Temuan Audit Baru: ' . $this->temuan->kode_temuan)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Ada temuan baru dari audit "' . $this->temuan->audit->nama_audit . '" pada unit Anda.')
            ->line('Kategori: ' . $this->temuan->kategori)
            ->line('Uraian: ' . $this->temuan->uraian_temuan)
            ->action('Lihat Detail Temuan', $url)
            ->line('Mohon segera ditindaklanjuti sebelum batas waktu yang ditentukan.')
            ->salutation('Terima kasih, Tim SPMI');
    }

    public function toArray($notifiable): array
    {
        return [
            'temuan_id'     => $this->temuan->id,
            'kode_temuan'   => $this->temuan->kode_temuan,
            'nama_audit'    => $this->temuan->audit->nama_audit,
            'kategori'      => $this->temuan->kategori,
            'uraian'        => $this->temuan->uraian_temuan,
            'url'           => route('audit.temuan.show', [$this->temuan->audit_id, $this->temuan->id]),
            'message'       => 'Temuan baru ditambahkan: ' . $this->temuan->kode_temuan,
        ];
    }

    public function toWhatsApp($notifiable)
    {
        // Placeholder untuk integrasi WhatsApp Gateway
        $message = "🔔 *TEMUAN BARU SPMI*\n\n"
                 . "Kode: {$this->temuan->kode_temuan}\n"
                 . "Audit: {$this->temuan->audit->nama_audit}\n"
                 . "Kategori: {$this->temuan->kategori}\n"
                 . "Uraian: {$this->temuan->uraian_temuan}\n\n"
                 . "Silakan login untuk menindaklanjuti.";
        
        Log::info("WhatsApp Sent to {$notifiable->no_hp}: " . $message);
        
        return $message;
    }
}
