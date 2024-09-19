<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class promosi extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'nama_promosi',
        'start_date',
        'end_date',
        'status',
        'kode',
        'discount'
    ];

    // Jika Anda menggunakan tipe data tanggal, sebaiknya casting ke tipe date
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Definisikan relasi Many-to-One dengan Transaksi (Satu promosi dapat digunakan di banyak transaksi)
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    // Fungsi untuk memeriksa apakah promosi masih aktif berdasarkan tanggal
    public function isActive()
    {
        $today = now();
        return $this->status === 'active' && $this->start_date <= $today && $this->end_date >= $today;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
