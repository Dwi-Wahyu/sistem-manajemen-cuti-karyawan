<?php

namespace Database\Factories;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveRequestFactory extends Factory
{
    protected $model = LeaveRequest::class;

    public function definition(): array
    {
        // Random tanggal (tahun ini)
        $startDate = $this->faker->dateTimeBetween('-1 year', '+1 month');
        $days = $this->faker->numberBetween(1, 5);
        $endDate = (clone $startDate)->modify("+" . ($days - 1) . " days");

        return [
            // User & Type akan di-override oleh Seeder agar lebih cepat
            'user_id' => User::factory(),
            'leave_type_id' => LeaveType::factory(),

            'reason' => $this->faker->sentence(6),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $days,

            // Default status: Pending
            'status' => 'pending',
            'leader_approved_at' => null,
            'leader_approver_id' => null,
            'hrd_approved_at' => null,
            'hrd_approver_id' => null,
            'rejection_reason' => null,

            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    //  Disetujui Leader (tapi belum HRD)
    public function approvedByLeader(int $leaderId)
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'leader_approved',
            'leader_approver_id' => $leaderId,
            'leader_approved_at' => Carbon::parse($attributes['created_at'])->addHours(rand(1, 24)),
        ]);
    }

    //  Disetujui Full (Leader + HRD)
    public function approved(int $leaderId, int $hrdId)
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'hrd_approved', // Atau 'approved'
            // Leader Data
            'leader_approver_id' => $leaderId,
            'leader_approved_at' => Carbon::parse($attributes['created_at'])->addHours(rand(1, 24)),
            // HRD Data
            'hrd_approver_id' => $hrdId,
            'hrd_approved_at' => Carbon::parse($attributes['created_at'])->addHours(rand(25, 48)),
        ]);
    }

    // Ditolak
    public function rejected(int $approverId, string $role = 'leader')
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'rejected',
            'rejection_reason' => 'Maaf, kuota sedang penuh atau jadwal padat.',
            // Jika ditolak leader
            'leader_approver_id' => $role === 'leader' ? $approverId : null,
            // Jika ditolak HRD (berarti leader sudah acc)
            'hrd_approver_id' => $role === 'hrd' ? $approverId : null,
        ]);
    }
}
