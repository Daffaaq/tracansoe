<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class category_harga extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'qty','category_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    // Relasi Many-to-One dengan category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transaksis()
    {
        return $this->belongsToMany(transaksi::class, 'transaksi_category_harga');
    }
}
