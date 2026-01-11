<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Floor;
use App\Models\User;

class RoomController extends Controller
{
    /**
     * Display a listing of the rooms.
     */
    public function index()
    {
        return view('admin.rooms.index', [
            'rooms' => Apartment::with('floor', 'supervisor')->paginate(10),
            'floors' => Floor::all(),
            'supervisors' => User::role('supervisor')->get()
        ]);
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        return view('admin.rooms.create', [
            'floors' => Floor::all(),
            'supervisors' => User::role('supervisor')->get()
        ]);
    }

    /**
     * Store a newly created room in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'room_number' => 'required|string|max:50',
            'apartment_number' => 'required|string|max:50|unique:apartments',
            'monthly_rent' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied',
            'supervisor_id' => 'nullable|exists:users,id'
        ]);

        Apartment::create($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Room created successfully!');
    }

    /**
     * Display the specified room.
     */
    public function show(Apartment $room)
    {
        return view('admin.rooms.show', [
            'room' => $room->load('floor', 'supervisor')
        ]);
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Apartment $room)
    {
        return view('admin.rooms.edit', [
            'room' => $room,
            'floors' => Floor::all(),
            'supervisors' => User::role('supervisor')->get()
        ]);
    }

    /**
     * Update the specified room in database.
     */
    public function update(Request $request, Apartment $room)
    {
        $validated = $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'room_number' => 'required|string|max:50',
            'apartment_number' => 'required|string|max:50|unique:apartments,apartment_number,' . $room->id,
            'monthly_rent' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied',
            'supervisor_id' => 'nullable|exists:users,id'
        ]);

        $room->update($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified room from database.
     */
    public function destroy(Apartment $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully!');
    }
}
