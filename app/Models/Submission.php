<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use BelongsToTenant;

    public const TERKUMPUL = 'terkumpul';
    public const TERLAMBAT = 'terlambat';
    public const DINILAI = 'dinilai';

    protected $fillable = [
        'school_id', 'assignment_id', 'user_id', 'jawaban_teks',
        'file_path', 'file_nama', 'status', 'versi', 'submitted_at',
        'nilai', 'umpan_balik',
    ];

    protected $casts = ['submitted_at' => 'datetime'];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(SubmissionVersion::class)->orderByDesc('versi');
    }
}
