<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class transaksi_category_harga extends Model
{
    use HasFactory;
    protected $fillable = ['uuid', 'transaksi_id', 'category_harga_id'];

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

    public function category_harga()
    {
        return $this->belongsTo(category_harga::class);
    }
}
