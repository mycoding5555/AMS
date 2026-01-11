<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Floor;
use App\Models\User;

class ApartmentController extends Controller
{
    public function index()
    {
        return view('admin.apartments', [
            'apartments' => Apartment::with('floor', 'supervisor')->get(),
            'floors' => Floor::all(),
            'supervisors' => User::role('supervisor')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'room_number' => 'required',
            'apartment_number' => 'required',
            'monthly_rent' => 'required|numeric',
            'supervisor_id' => 'nullable|exists:users,id'
        ]);

        Apartment::create($request->all());

        return back()->with('success','Room created successfully');
    }

    public function update(Request $request, Apartment $apartment)
    {
        $request->validate([
            'room_number' => 'required',
            'apartment_number' => 'required',
            'monthly_rent' => 'required|numeric',
            'supervisor_id' => 'nullable|exists:users,id'
        ]);

        $apartment->update($request->only([
            'room_number','apartment_number','monthly_rent','status','supervisor_id'
        ]));

        return back()->with('success','Room updated successfully');
    }

    public function destroy(Apartment $apartment)
    {
        $apartment->delete();
        return back()->with('success','Room deleted successfully');
    }

}