<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Po;
use App\Models\PoItem;
use App\Models\Batch;
use Carbon\Carbon;
use DB;

class PoController extends Controller
{
    public function index()
{
    $pos = Po::withCount('batches')->latest()->get();
    return view('po.index', compact('pos'));
}

    public function create()
    {
        return view('po.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_no' => 'required|string|unique:pos,po_no',
            'po_date' => 'required|date',
            'period_from' => 'required|date',
            'period_to' => 'required|date|after_or_equal:period_from',
            'gst' => 'nullable|numeric|min:0',
        ]);

        Po::create($request->only('po_no', 'po_date', 'period_from', 'period_to', 'gst'));

        return redirect()->route('po.index')->with('success', 'PO created successfully!');
    }

    public function edit($id)
    {
        $po = Po::findOrFail($id);
        return view('po.edit', compact('po'));
    }

    public function update(Request $request, $id)
    {
        $po = Po::findOrFail($id);

        $request->validate([
            'po_no' => 'required|string|unique:pos,po_no,' . $po->id,
            'po_date' => 'required|date',
            'period_from' => 'required|date',
            'period_to' => 'required|date|after_or_equal:period_from',
            'gst' => 'nullable|numeric|min:0',
        ]);

        $po->update($request->only('po_no', 'po_date', 'period_from', 'period_to', 'gst'));

        return redirect()->route('po.index')->with('success', 'PO updated successfully!');
    }

    public function destroy($id)
    {
        $po = Po::findOrFail($id);

        // ✅ Check if PO is used in any batch
        $isUsed = Batch::where('po_id', $po->id)->exists();

        if ($isUsed) {
            return back()->with('error', 'Cannot delete PO because it is linked to batches.');
        }

        $po->delete();

        return redirect()->route('po.index')
            ->with('success', 'PO deleted successfully!');
    }

    public function show($id)
    {
        $po = Po::with('items')->findOrFail($id);
        return view('po.show', compact('po'));
    }

    /**
     * ✅ API for Batch Page - Return PO Items with Remaining Qty
     */
    public function getPoItems($id)
    {
        $items = PoItem::where('po_id', $id)->get();

        $data = $items->map(function ($item) {
            $usedQty = $item->used_quantity ?? 0;
            $remainingQty = $item->quantity - $usedQty;

            return [
                'id' => $item->id,
                'item' => $item->item,
                'quantity' => $item->quantity,
                'used_quantity' => $usedQty,
                'remaining_qty' => $remainingQty, // ✅ IMPORTANT
                'value' => $item->value,
            ];
        });

        return response()->json($data);
    }
}
