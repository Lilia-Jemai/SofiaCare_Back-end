<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicRequest;
use App\Models\Medic;
use Illuminate\Http\Request;

class MedicController extends Controller
{
    public function index() {
        // return response()->json("function index works");
        return Medic::with('user', 'spec')->get();
    }

    public function show(Medic $medic) {
        return $medic;
    }

    public function store(MedicRequest $request) {

        Medic::create($request->validated());
        return response()->json("Medic was created successfuly!!");
    }

    public function update(MedicRequest $request, Medic $medic) {

        $medic->update($request->validated());
        return response()->json("Medic was updated successfuly!!");
    }

    public function destroy(Medic $medic) {
        $medic->delete();
        return response()->json("Medic was deleted successfuly!!");
    }
}
