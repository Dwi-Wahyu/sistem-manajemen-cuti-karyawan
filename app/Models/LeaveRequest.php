<?php

namespace App\Models;

use App\Enums\LeaveRequestStatus;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'leader_approved_at' => 'datetime',
        'hrd_approved_at' => 'datetime',
        'total_days' => 'float',
        'status' => LeaveRequestStatus::class,
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
