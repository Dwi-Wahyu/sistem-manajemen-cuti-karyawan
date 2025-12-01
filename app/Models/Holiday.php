<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'date',
        'name',
        'is_joint_leave'
    ];

    protected $casts = [
        'date' => 'date',
        'is_joint_leave' => 'boolean',
    ];

    // Untuk format hari menjadi locale id
    public function getHariAttribute(): string
    {
        if ($this->date) {
            return $this->date->translatedFormat('l');
        }
        return '-';
    }
}
