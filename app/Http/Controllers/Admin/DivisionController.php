<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    /**
     * Tampilkan daftar divisi.
     */
    public function index()
    {
        $this->authorize('viewAny', Division::class);

        // Ambil data divisi beserta info Ketua dan jumlah anggota
        $divisions = Division::with('head')->withCount('members')->paginate(10);
        
        return view('admin.divisions.index', compact('divisions'));
    }

    /**
     * Form buat divisi baru.
     */
    public function create()
    {
        $this->authorize('create', Division::class);

        // Cari user dengan role 'division_head' yang belum memimpin divisi manapun
        // Atau kita bisa izinkan semua division_head tampil di dropdown
        $availableHeads = User::where('role', 'division_head')
            ->whereDoesntHave('ledDivision') // Pastikan belum jadi ketua di tempat lain
            ->get();

        return view('admin.divisions.create', compact('availableHeads'));
    }

    /**
     * Simpan divisi baru.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Division::class);

        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name',
            'description' => 'nullable|string',
            'head_user_id' => [
                'nullable', 
                'exists:users,id',
                // Validasi manual: Pastikan user yang dipilih role-nya division_head
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if ($user && !$user->isDivisionHead()) {
                        $fail('User yang dipilih harus memiliki role Ketua Divisi.');
                    }
                },
            ],
        ]);

        $division = Division::create([
            'name' => $request->name,
            'description' => $request->description,
            'head_user_id' => $request->head_user_id,
            'established_date' => now(),
        ]);

        // Jika ada ketua yang dipilih, update user tersebut agar masuk ke divisi ini juga
        if ($request->head_user_id) {
            $head = User::find($request->head_user_id);
            $head->division_id = $division->id;
            $head->save();
        }

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi berhasil dibuat.');
    }

    /**
     * Tampilkan detail divisi (Manajemen Anggota).
     */
    public function show(Division $division)
    {
        $this->authorize('view', $division);

        // Muat relasi ketua dan anggota
        $division->load(['head', 'members']);

        // Cari karyawan (employee) yang belum punya divisi (free agents) untuk ditambahkan
        $availableEmployees = User::where('role', 'employee')
            ->whereNull('division_id')
            ->orderBy('name')
            ->get();

        return view('admin.divisions.show', compact('division', 'availableEmployees'));
    }

    /**
     * Form edit divisi.
     */
    public function edit(Division $division)
    {
        $this->authorize('update', $division);

        // Cari calon ketua: Semua division_head yang (belum punya tim OR adalah ketua divisi ini sekarang)
        $availableHeads = User::where('role', 'division_head')
            ->where(function($query) use ($division) {
                $query->whereDoesntHave('ledDivision')
                      ->orWhere('id', $division->head_user_id);
            })
            ->get();

        return view('admin.divisions.edit', compact('division', 'availableHeads'));
    }

    /**
     * Update data divisi.
     */
    public function update(Request $request, Division $division)
    {
        $this->authorize('update', $division);

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('divisions')->ignore($division->id)],
            'description' => 'nullable|string',
            'head_user_id' => 'nullable|exists:users,id',
        ]);

        // Logika Ganti Ketua:
        // 1. Jika ketua berubah, ketua lama harus tetap di divisi atau dikeluarkan? 
        //    Sederhananya: Ketua lama tetap di divisi sebagai anggota biasa (atau null jika diinginkan).
        // 2. Ketua baru harus dimasukkan ke divisi ini.

        $oldHeadId = $division->head_user_id;
        $newHeadId = $request->head_user_id;

        $division->update([
            'name' => $request->name,
            'description' => $request->description,
            'head_user_id' => $newHeadId,
        ]);

        if ($newHeadId && $newHeadId != $oldHeadId) {
            // Update User Ketua Baru: Assign ke divisi ini
            $newHead = User::find($newHeadId);
            $newHead->division_id = $division->id;
            $newHead->save();
        }

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Hapus divisi.
     */
    public function destroy(Division $division)
    {
        $this->authorize('delete', $division);

        // Set semua anggota menjadi NULL divisinya sebelum hapus divisi
        // Agar user tidak ikut terhapus (karena on delete set null di database), tapi kita pastikan di sini.
        User::where('division_id', $division->id)->update(['division_id' => null]);

        $division->delete();

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi berhasil dihapus.');
    }

    // --- FITUR TAMBAHAN: MANAJEMEN ANGGOTA ---

    /**
     * Tambah anggota ke divisi.
     */
    public function addMember(Request $request, Division $division)
    {
        $this->authorize('update', $division);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);
        
        // Validasi: Pastikan user belum punya divisi (opsional, tapi disarankan)
        if ($user->division_id) {
            return back()->withErrors(['user_id' => 'User tersebut sudah tergabung di divisi lain.']);
        }

        $user->division_id = $division->id;
        $user->save();

        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Hapus anggota dari divisi.
     */
    public function removeMember(Division $division, User $user)
    {
        $this->authorize('update', $division);

        // Pastikan user memang anggota divisi ini
        if ($user->division_id !== $division->id) {
            return back()->withErrors(['msg' => 'User bukan anggota divisi ini.']);
        }

        // Jangan izinkan menghapus Ketua Divisi lewat tombol hapus anggota biasa
        if ($division->head_user_id === $user->id) {
            return back()->withErrors(['msg' => 'Tidak dapat mengeluarkan Ketua Divisi. Silakan ganti ketua divisi terlebih dahulu melalui menu Edit.']);
        }

        $user->division_id = null;
        $user->save();

        return back()->with('success', 'Anggota berhasil dikeluarkan dari divisi.');
    }
}