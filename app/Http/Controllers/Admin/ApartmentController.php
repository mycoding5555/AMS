<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Floor;

class ApartmentController extends Controller
{
        public function index()
    {
        return view('admin.apartments', [
            'apartments' => Apartment::with('floor')->get(),
            'floors' => Floor::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'apartment_number' => 'required',
            'monthly_rent' => 'required|numeric'
        ]);

        Apartment::create($request->all());

        return back()->with('success','Room created');
    }

    public function update(Request $request, Apartment $apartment)
    {
        $apartment->update($request->only([
            'apartment_number','monthly_rent','status'
        ]));

        return back()->with('success','Room updated');
    }
}
