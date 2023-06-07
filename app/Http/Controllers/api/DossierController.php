<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DossierRequest;
use App\Models\Dossier;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    public function index() {
        // return response()->json("function index works");
        return Dossier::with('medic')->get();
    }

    public function show(Dossier $dossier) {
        $dossier->load('medic');
        return $dossier;
    }

    public function store(DossierRequest $request) {

        Dossier::create($request->validated());
        return response()->json("Dossier was created successfuly!!");
    }

    public function update(DossierRequest $request, Dossier $dossier) {

        $dossier->update($request->validated());
        return response()->json("Dossier was updated successfuly!!");
    }

    public function destroy(Dossier $dossier) {
        $dossier->delete();
        return response()->json("Dossier was deleted successfuly!!");
    }
}
