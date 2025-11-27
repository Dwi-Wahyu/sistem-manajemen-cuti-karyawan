<?php

// app/Models/Division.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'head_user_id',
        'established_date',
    ];

    protected $casts = [
        'established_date' => 'date',
    ];

    // Relasi ke Ketua Divisi
    public function head()
    {
        return $this->belongsTo(User::class, 'head_user_id');
    }

    // Relasi ke Anggota Divisi (termasuk ketua)
    public function members()
    {
        return $this->hasMany(User::class, 'division_id');
    }
}
