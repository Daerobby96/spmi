<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'display_name', 'description'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    const SUPER_ADMIN   = 'super_admin';
    const AUDITOR       = 'auditor';
    const AUDITEE       = 'auditee';
    const PIMPINAN      = 'pimpinan';
    const STAF_DOKUMEN  = 'staf_dokumen';
}