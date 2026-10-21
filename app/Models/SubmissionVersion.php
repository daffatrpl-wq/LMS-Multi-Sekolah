<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class SubmissionVersion extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id', 'submission_id', 'versi', 'jawaban_teks',
        'file_path', 'file_nama', 'submitted_at',
    ];

    protected $casts = ['submitted_at' => 'datetime'];
}
