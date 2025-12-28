<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShiftType;
use Illuminate\Http\Request;

class ShiftTypeController extends Controller
{
    public function index(Request $request)
    {
        $shiftTypes = ShiftType::with('parentShift')->get();

        if ($request->wantsJson()) {
            return response()->json(['data' => $shiftTypes]);
        }

        return view('pos.shift_types.index', compact('shiftTypes'));
    }

    /**
     * API endpoint: /api/pos/shift-types
     * Returns all shift types in JSON format regardless of Accept header.
     */
    public function apiIndex()
    {
        try {
            $shiftTypes = ShiftType::select('id', 'name', 'description', 'parent_shift_id')
                ->with(['parentShift' => function($query) {
                    $query->select('id', 'name');
                }])
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $shiftTypes,
                'message' => 'Shift types retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve shift types.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $parentShifts = ShiftType::all();
        return view('pos.shift_types.create', compact('parentShifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_shift_id' => 'nullable|exists:shift_types,id',
            'image' => 'nullable|string',
        ]);

        $shiftType = ShiftType::create($request->all());

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Shift Type created successfully.', 'data' => $shiftType], 201);
        }

        return redirect()->route('shift-types.index')->with('success', 'Shift Type created successfully.');
    }

    public function edit(ShiftType $shiftType)
    {
        $parentShifts = ShiftType::where('id', '!=', $shiftType->id)->get();
        return view('pos.shift_types.edit', compact('shiftType', 'parentShifts'));
    }

    public function update(Request $request, ShiftType $shiftType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_shift_id' => 'nullable|exists:shift_types,id',
            'image' => 'nullable|string',
        ]);

        $shiftType->update($request->all());

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Shift Type updated successfully.', 'data' => $shiftType]);
        }
        return redirect()->route('shift-types.index')->with('success', 'Shift Type updated successfully.');
    }

    public function show(ShiftType $shiftType)
    {
        if (request()->wantsJson()) {
            return response()->json(['data' => $shiftType->load('parentShift')]);
        }
        return view('pos.shift_types.show', compact('shiftType'));
    }

    public function destroy(ShiftType $shiftType, Request $request)
    {
        $shiftType->delete();
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Shift Type deleted']);
        }
        return redirect()->route('shift-types.index')->with('success', 'Shift Type deleted successfully.');
    }
}
