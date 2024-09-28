<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class transaksi_plus_service extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'transaksi_id',
        'plus_service_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function transaksi()
    {
        return $this->belongsTo(transaksi::class);
    }
    public function plusService()
    {
        return $this->belongsTo(plus_service::class);
    }
}
