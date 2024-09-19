<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class tracking_status extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'transaksi_id', 'status_id','description'];

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

    // Relasi ke status (Many-to-One)
    public function status()
    {
        return $this->belongsTo(status::class);
    }
}
