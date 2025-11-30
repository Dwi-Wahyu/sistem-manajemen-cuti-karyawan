<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\Division;
use Illuminate\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        // Cek Ketersediaan Data
        $countLeaveType = LeaveType::count();
        $countEmployee = User::where('role', 'employee')->count();

        if ($countLeaveType == 0 || $countEmployee == 0) {
            $this->command->error("STOP: Data LeaveType atau Employee kosong.");
            return;
        }

        // Persiapan Data Referensi
        $leaveTypeIds = LeaveType::pluck('id')->toArray();
        $hrdIds = User::where('role', 'hrd')->pluck('id')->toArray();

        // Data Divisi
        $allDivisionIds = Division::pluck('id')->toArray();
        $divisionHeads = Division::pluck('head_user_id', 'id')->toArray();

        // Data Employee
        $employees = User::where('role', 'employee')->pluck('division_id', 'id')->toArray();

        $userIds = array_keys($employees);
        $totalRequests = 1000;
        $chunkSize = 250;

        $this->command->info("Mulai generate $totalRequests pengajuan cuti ...");

        // Loop Batch
        for ($i = 0; $i < $totalRequests; $i += $chunkSize) {
            $batchData = [];

            for ($j = 0; $j < $chunkSize; $j++) {
                $userId = $userIds[array_rand($userIds)];

                // --- Logika Leader (Fallback System) ---
                $userDivId = $employees[$userId];
                if ($userDivId && isset($divisionHeads[$userDivId])) {
                    $leaderId = $divisionHeads[$userDivId];
                } else {
                    if (empty($allDivisionIds)) continue;
                    $randomDivId = $allDivisionIds[array_rand($allDivisionIds)];
                    $leaderId = $divisionHeads[$randomDivId] ?? null;
                }

                if (!$leaderId) $leaderId = $hrdIds[0] ?? $userId;
                $hrdId = !empty($hrdIds) ? $hrdIds[array_rand($hrdIds)] : $leaderId;
                $leaveTypeId = $leaveTypeIds[array_rand($leaveTypeIds)];

                // --- Logika Status ---

                $rand = rand(1, 100);
                $createdAt = now()->subDays(rand(10, 365));

                // Variabel default (Wajib null dulu)
                $status = 'pending';
                $leaderApprovedAt = null;
                $leaderApproverId = null;
                $leaderRejectionNote = null;

                $hrdApprovedAt = null;
                $hrdApproverId = null;
                $hrdRejectionNote = null;

                if ($rand <= 30) {
                    // PENDING
                    $status = 'pending';
                } elseif ($rand <= 50) {
                    // APPROVED BY LEADER (Belum sampai HRD)
                    $status = 'approved_by_leader';
                    $leaderApproverId = $leaderId;
                    $leaderApprovedAt = (clone $createdAt)->addHours(rand(1, 24));
                } elseif ($rand <= 85) {
                    // APPROVED (Final / HRD Approved)
                    $status = 'approved';

                    // Leader harus acc dulu
                    $leaderApproverId = $leaderId;
                    $leaderApprovedAt = (clone $createdAt)->addHours(rand(1, 24));

                    // Baru HRD acc
                    $hrdApproverId = $hrdId;
                    $hrdApprovedAt = (clone $leaderApprovedAt)->addHours(rand(1, 24));
                } else {
                    // REJECTED
                    $status = 'rejected';

                    // Kita acak, ditolak Leader atau HRD?
                    if (rand(0, 1) == 0) {
                        // Ditolak Leader
                        $leaderApproverId = $leaderId; // Yang nolak tetap dicatat ID-nya
                        $leaderRejectionNote = 'Maaf, jadwal tim sedang padat.';
                    } else {
                        // Ditolak HRD (Berarti Leader sudah acc)
                        $leaderApproverId = $leaderId;
                        $leaderApprovedAt = (clone $createdAt)->addHours(rand(1, 24));

                        $hrdApproverId = $hrdId; // HRD yang nolak
                        $hrdRejectionNote = 'Sisa cuti tidak mencukupi menurut sistem.';
                    }
                }

                $startDate = (clone $createdAt)->addDays(rand(1, 30));
                $days = rand(1, 3);
                $endDate = (clone $startDate)->addDays($days - 1);

                // --- KONSTRUKSI DATA ---
                $batchData[] = [
                    'user_id' => $userId,
                    'leave_type_id' => $leaveTypeId,
                    'reason' => 'Keperluan Pribadi / Refreshing', // Kolom wajib
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'total_days' => $days,

                    'status' => $status,

                    // Data Leader
                    'leader_approver_id' => $leaderApproverId,
                    'leader_approved_at' => $leaderApprovedAt ? $leaderApprovedAt->toDateTimeString() : null,
                    'leader_rejection_note' => $leaderRejectionNote, // KOLOM BARU YANG BENAR

                    // Data HRD
                    'hrd_approver_id' => $hrdApproverId,
                    'hrd_approved_at' => $hrdApprovedAt ? $hrdApprovedAt->toDateTimeString() : null,
                    'hrd_rejection_note' => $hrdRejectionNote,       // KOLOM BARU YANG BENAR

                    // Timestamps
                    'created_at' => $createdAt->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),

                    // Data tambahan (boleh null, tapi kita isi dummy biar rapi)
                    'emergency_number' => '08123456789',
                    'contact_address_during_leave' => 'Di Rumah',
                ];
            }

            if (!empty($batchData)) {
                try {
                    LeaveRequest::insert($batchData);
                } catch (\Illuminate\Database\QueryException $e) {
                    $this->command->error("Gagal pada batch " . ($i + $chunkSize));
                    $this->command->error("Pesan Error: " . $e->getMessage());
                    return;
                }
            }

            $this->command->info("Batch " . ($i + $chunkSize) . " selesai...");
        }

        $this->command->info("SELESAI! $totalRequests Data Cuti berhasil dibuat.");
    }
}
