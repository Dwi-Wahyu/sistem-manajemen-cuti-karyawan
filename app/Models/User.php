<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Uncomment jika ingin verifikasi email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Pastikan package Sanctum terinstall

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',                         // Role: admin, hrd, division_head, employee
        'division_id',                  // ID Divisi tempat user bernaung
        'initial_annual_leave_quota',   // Kuota awal (default 12)
        'current_annual_leave_quota',   // Sisa kuota saat ini
        'join_date',                    // Tanggal bergabung
        'phone_number',
        'address',
        'is_active',                    // Status aktif/non-aktif
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'join_date' => 'date',          // Cast ke Carbon date object
        'is_active' => 'boolean',       // Cast ke boolean
    ];

    // --- RELASI DATABASE ---

    /**
     * Relasi ke Divisi (User sebagai Anggota).
     * User belongs to a Division.
     */
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    /**
     * Relasi ke Divisi (User sebagai Ketua).
     * User might lead a Division.
     */
    public function ledDivision()
    {
        return $this->hasOne(Division::class, 'head_user_id');
    }

    /**
     * Relasi ke Pengajuan Cuti (User sebagai Pemohon).
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'user_id');
    }

    // --- ROLE CHECKER METHODS (HELPER) ---
    // Metode ini digunakan di Policy, Controller, dan View untuk pengecekan hak akses.

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isHrd()
    {
        return $this->role === 'hrd';
    }

    public function isDivisionHead()
    {
        return $this->role === 'division_head';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }
}