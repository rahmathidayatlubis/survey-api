<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    // Otomatis pakai UUID saat binding
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    // Otomatis men-generate uuid ketika ada trigger create data
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('urutan');
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}