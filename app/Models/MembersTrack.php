<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MembersTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'membership_id',
        'buktiPembayaran',
        'totalPembayaran',
        'tanggalPembayaran',
        'jamPembayaran',
        'start_membership',
        'end_membership',
        'status',
        'kelas_membership',
        'discount'
    ];

    public function member()
    {
        return $this->belongsTo(Members::class, 'membership_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
