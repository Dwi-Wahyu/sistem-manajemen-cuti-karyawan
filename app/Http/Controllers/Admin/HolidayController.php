<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $this->authorize('view', Holiday::class);

        $holidays = Holiday::latest()->paginate(15);
        return view('holidays.index', compact('holidays'));
    }

    public function create()
    {
        $this->authorize('create', Holiday::class);

        return view('holidays.create');
    }


    public function store(Request $request)
    {
        $this->authorize('create', Holiday::class);

        $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'name' => 'required|string|max:255',
            'is_joint_leave' => 'nullable|boolean',
        ]);

        Holiday::create([
            'date' => $request->date,
            'name' => $request->name,
            'is_joint_leave' => $request->boolean('is_joint_leave'),
        ]);

        return redirect()->route('holidays.index')
            ->with('success', 'Hari libur baru berhasil ditambahkan.');
    }

    public function edit(Holiday $holiday)
    {
        $this->authorize('update', $holiday);

        return view('holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $this->authorize('update', $holiday);

        $validated = $request->validate([
            'date' => [
                'required',
                'date',
                'unique:holidays,date,' . $holiday->id
            ],
            'name' => 'required|string|max:255',
            'is_joint_leave' => 'nullable|boolean',
        ]);

        $holiday->update([
            'date' => $validated['date'],
            'name' => $validated['name'],
            'is_joint_leave' => $request->boolean('is_joint_leave'),
        ]);

        return redirect()->route('holidays.index')
            ->with('success', 'Hari libur "' . $holiday->name . '" berhasil diperbarui.');
    }

    public function destroy(Holiday $holiday)
    {
        $this->authorize('delete', $holiday);
        $holiday->delete();
        return back()->with('success', 'Hari libur berhasil dihapus.');
    }
}
