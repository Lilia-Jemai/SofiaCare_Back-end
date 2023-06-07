<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\RendvousRequest;
use App\Models\Rendvous;
use Illuminate\Http\Request;

class RendvousController extends Controller
{
    public function index()
    {
        // return response()->json("function index works");
        return Rendvous::with('medic', 'patient')->get();
    }

    public function show(Rendvous $rendvous)
    {
        $rendvous->load('medic', 'patient');
        return $rendvous;
    }

    public function store(RendvousRequest $request)
    {

        // $date = Carbon::now();
        // $formattedDate = $date->format('Y-m-d');
        Rendvous::create($request->validated());
        return response()->json("Rendvous was created successfuly!!");
    }

    public function update(RendvousRequest $request, Rendvous $rendvous)
    {

        $rendvous->update($request->validated());
        return response()->json("Rendvous was updated successfuly!!");
    }

    public function destroy(Rendvous $rendvous)
    {
        $rendvous->delete();
        return response()->json("Rendvous was deleted successfuly!!");
    }
}
