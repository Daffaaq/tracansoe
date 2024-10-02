<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DoorprizeWinner extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'transaksi_id',
        'hadiah_id',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function hadiah()
    {
        return $this->belongsTo(Hadiah::class);
    }
    protected static function boot()
    {
        parent::boot();


        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
