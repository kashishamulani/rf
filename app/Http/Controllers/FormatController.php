<?php

namespace App\Http\Controllers;

use App\Models\Format;
use Illuminate\Http\Request;

class FormatController extends Controller
{
    // Show all formats
    public function index()
    {
        $formats = Format::latest()->get();
        return view('formats.index', compact('formats'));
    }

    // Show create form
    public function create()
    {
        return view('formats.create');
    }

    // Store new format
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:50|unique:formats,type',
        ], [
            'type.unique' => 'This format name already exists!',
        ]);

        Format::create([
            'type' => $request->type,
        ]);

        return redirect()->route('formats.index')->with('success', 'Format added successfully!');
    }

    // Show edit form
    public function edit(Format $format)
    {
        return view('formats.edit', compact('format'));
    }

    // Update format
    public function update(Request $request, Format $format)
    {
        $request->validate([
            'type' => 'required|string|max:50|unique:formats,type,' . $format->id,
        ], [
            'type.unique' => 'This format name already exists!',
        ]);

        $format->update([
            'type' => $request->type,
        ]);

        return redirect()->route('formats.index')->with('success', 'Format updated successfully!');
    }

    // Delete format
    public function destroy(Format $format)
    {
        $format->delete();
        return redirect()->route('formats.index')->with('success', 'Format deleted successfully!');
    }
}
