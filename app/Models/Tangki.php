<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tangki extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','name', 'type','price', 'scedule', 'description', 'status','request_status','photo_path'
    ];

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class, 'tangki_id', 'id');
    }
    public function scopeActive($query)
    {
        return $query->where('request_status', '=', '1');
    }

    public function getPhotoPathAttribute($url)
    {
        return config('app.url') .'/'. $url;
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'tangki_id', 'id')
            ->whereNotIn('status', ['Pesanan Dibatalkan', 'Pesanan Gagal', 'Pesanan Selesai']);
    }




}


