<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class category extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'nama_kategori', 'price', 'description', 'estimation'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function transaksis()
    {
        return $this->belongsToMany(transaksi::class, 'transaksi_category_harga');
    }
}
