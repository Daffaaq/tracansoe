<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CategorySepatu extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'category_sepatu'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function transaksis()
    {
        return $this->belongsToMany(Transaksi::class, 'transaksi_category_hargas', 'category_sepatus_id', 'transaksi_id');
    }
}
