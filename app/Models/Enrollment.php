<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use BelongsToTenant;

    public const MENUNGGU = 'menunggu';
    public const AKTIF = 'aktif';
    public const KELUAR = 'keluar';

    protected $fillable = ['school_id', 'class_id', 'user_id', 'status'];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
