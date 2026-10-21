<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        // Global scope: semua query otomatis dibatasi ke tenant user yang login (NFR-DAT-02)
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check() && auth()->user()->school_id) {
                $query->where($query->getModel()->getTable() . '.school_id', auth()->user()->school_id);
            }
        });

        // Isi school_id otomatis saat data dibuat
        static::creating(function ($model) {
            if (empty($model->school_id) && auth()->check()) {
                $model->school_id = auth()->user()->school_id;
            }
        });
    }
}
