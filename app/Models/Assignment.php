<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id', 'class_id', 'judul', 'deskripsi',
        'deadline', 'izinkan_terlambat', 'nilai_maks',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'izinkan_terlambat' => 'boolean',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function sudahLewatDeadline(): bool
    {
        return now()->greaterThan($this->deadline);
    }

    // Siswa masih boleh mengumpulkan jika belum lewat deadline, atau guru mengizinkan terlambat
    public function masihTerbuka(): bool
    {
        return ! $this->sudahLewatDeadline() || $this->izinkan_terlambat;
    }
}
