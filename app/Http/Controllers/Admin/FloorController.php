<?php

namespace App\Http\Controllers\Admin;

use App\Models\Floor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FloorController extends Controller
{
 public function index()
    {
        return view('admin.floors.index', [
            'floors' => Floor::withCount('apartments')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50'
        ]);

        Floor::create($request->only('name'));

        return back()->with('success', 'Floor added successfully');
    }

    public function update(Request $request, Floor $floor)
    {
        $request->validate([
            'name' => 'required|string|max:50'
        ]);

        $floor->update($request->only('name'));

        return back()->with('success', 'Floor updated successfully');
    }
}
