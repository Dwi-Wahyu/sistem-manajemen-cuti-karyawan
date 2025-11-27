<?php

// app/Models/LeaveRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Gunakan guarded agar lebih mudah

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'leader_approved_at' => 'datetime',
        'hrd_approved_at' => 'datetime',
        'total_days' => 'float',
    ];

    // Karyawan yang mengajukan
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Jenis Cuti
    public function type()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    // Approver Pertama (Ketua Divisi)
    public function leaderApprover()
    {
        return $this->belongsTo(User::class, 'leader_approver_id');
    }

    // Approver Final (HRD)
    public function hrdApprover()
    {
        return $this->belongsTo(User::class, 'hrd_approver_id');
    }
}
