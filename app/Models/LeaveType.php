<?php

// app/Models/LeaveType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_quota_deductible',
    ];

    protected $casts = [
        'is_quota_deductible' => 'boolean',
    ];
}