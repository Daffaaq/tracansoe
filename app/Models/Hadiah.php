<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Hadiah extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'nama_hadiah',
        'deskripsi',
        'jumlah',
        'tanggal_awal',
        'tanggal_akhir',
    ];

    protected static function boot()
    {
        parent::boot();


        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
