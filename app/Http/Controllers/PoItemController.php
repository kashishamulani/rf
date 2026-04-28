<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Po;
use App\Models\PoItem;

class PoItemController extends Controller
{
// public function create(Po $po)
// {
//     $existingItems = $po->items;   // fetch existing PO items
//     return view('po_items.create', compact('po', 'existingItems'));
// }


public function create($poId)
{
    $po = Po::findOrFail($poId);

    // Fetch existing items for this PO
    $existingItems = PoItem::where('po_id', $po->id)->get();

    return view('po_items.create', compact('po', 'existingItems'));
}

public function store(Request $request, Po $po)
{
    $request->validate([
        'items.*.item' => 'required|string',
        'items.*.value' => 'required|numeric',
        'items.*.quantity' => 'required|integer',
    ]);

    foreach ($request->items as $item) {
        $po->items()->create([
            'item' => $item['item'],
            'value' => $item['value'],
            'quantity' => $item['quantity'],
        ]);
    }

    return redirect()->route('po.show', $po->id)
                     ->with('success', 'Items added successfully!');
}


public function edit($poId, $poItemId)
{
    $po = PO::findOrFail($poId);
    $item = POItem::findOrFail($poItemId);

    return view('po_items.edit', compact('po', 'item'));
}

public function update(Request $request, PO $po, POItem $po_item)
{
    $po_item->update($request->all());

    return redirect()->route('po.show', $po->id)
                     ->with('success','Item updated successfully');
}

public function destroy($poId, $poItemId)
{
    $item = PoItem::findOrFail($poItemId);
    $item->delete();

    return redirect()->route('po.show', $poId)
                     ->with('success', 'Item deleted successfully!');
}

}