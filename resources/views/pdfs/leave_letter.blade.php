<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Cuti {{ $leaveRequest->user->name }}</title>
    <style>
        /* Gaya dasar untuk print (Dompdf kompatibel) */
        body {
            font-family: 'sans-serif';
            margin: 50px;
            font-size: 11pt;
        }

        h1 {
            font-size: 16pt;
            text-align: center;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 14pt;
            text-align: center;
            margin-bottom: 20px;
        }

        .data-table,
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .signature-table td {
            width: 33%;
            padding: 20px 0;
            text-align: center;
            vertical-align: top;
        }

        .reason-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 15px;
            background-color: #f9f9f9;
        }

        .line {
            border-bottom: 1px solid #000;
            margin-top: 50px;
            width: 60%;
            margin-left: 20%;
        }

        .logo-text {
            font-size: 18pt;
            font-weight: bold;
            color: #1E40AF;
        }

        /* Menggunakan warna primary */
    </style>
</head>

<body>

    <div style="text-align: center; margin-bottom: 40px;">
        <span class="logo-text">SURAT KETERANGAN CUTI</span>
        <p style="font-size: 10pt; color: #555;">No. {{ $leaveRequest->id }}/HRD/{{ $leaveRequest->created_at->format('Y') }}</p>
    </div>

    <p style="margin-bottom: 20px;">Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

    <table class="data-table">
        <tr>
            <th style="width: 30%;">Nama Karyawan</th>
            <td>{{ $leaveRequest->user->name }}</td>
        </tr>
        <tr>
            <th>Jabatan / Role</th>
            <td>{{ $leaveRequest->user->role->title() }}</td>
        </tr>
        <tr>
            <th>Divisi</th>
            <td>{{ $leaveRequest->user->division->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jenis Cuti</th>
            <td>{{ $leaveRequest->type->name }}</td>
        </tr>
        <tr>
            <th>Periode Cuti</th>
            <td>{{ $leaveRequest->start_date->format('d F Y') }} s/d {{ $leaveRequest->end_date->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Durasi</th>
            <td>{{ $leaveRequest->total_days }} Hari Kerja</td>
        </tr>
    </table>

    <div class="reason-box">
        <p style="font-weight: bold; margin-bottom: 5px;">Alasan Pengajuan:</p>
        <p>{{ $leaveRequest->reason }}</p>
    </div>

    <p style="margin-top: 30px;">Surat cuti ini disetujui pada tanggal {{ $leaveRequest->hrd_approved_at->format('d F Y') }} dan berlaku sesuai periode di atas.</p>

    {{-- Tanda Tangan --}}
    <table class="signature-table">
        <tr>
            <td>
                Disetujui Oleh,<br>
                <span>Ketua Divisi</span><br>
                <div style="height: 50px;"></div>
                <div class="line"></div>
                ({{ $leaveRequest->leaderApprover->name ?? 'N/A' }})
            </td>
            <td>
                Disetujui Final Oleh,<br>
                <span>HRD Manager</span><br>
                <div style="height: 50px;"></div>
                <div class="line"></div>
                ({{ $leaveRequest->hrdApprover->name ?? 'N/A' }})
            </td>
            <td>
                Pemohon,<br>
                <span>Karyawan</span><br>
                <div style="height: 50px;"></div>
                <div class="line"></div>
                ({{ $leaveRequest->user->name }})
            </td>
        </tr>
    </table>

</body>

</html>