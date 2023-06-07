<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialiteRequest;
use App\Models\Specialite;
use Illuminate\Http\Request;

class SpecialiteController extends Controller
{
    public function index()
    {
        // return response()->json("function index works");
        return Specialite::all();
    }

    public function show(Specialite $specialite)
    {
        return $specialite;
    }

    public function store(SpecialiteRequest $request)
    {

        Specialite::create($request->validated());
        return response()->json("RDV was created successfuly!!");
    }

    public function update(SpecialiteRequest $request, Specialite $specialite)
    {

        $specialite->update($request->validated());
        return response()->json("RDV was updated successfuly!!");
    }

    public function destroy(Specialite $specialite)
    {
        $specialite->delete();
        return response()->json("RDV was deleted successfuly!!");
    }
}
