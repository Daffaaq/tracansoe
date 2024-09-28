<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Members extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'nama_membership',
        'email_membership',
        'phone_membership',
        'alamat_membership',
        'kode'
    ];

    public function tracks()
    {
        return $this->hasMany(MembersTrack::class, 'membership_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
