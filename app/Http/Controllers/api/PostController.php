<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store (PostRequest $request ) {

        try {
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();

            Post::create([
                'image'=>$imageName,
                'title'=>$request->title,
                'description'=>$request->description,
            ]);

            Storage::disk('public')->put($imageName, file_get_contents($request->image));

            return response()->json([
                'status' => true,
                'message' => 'Post Created In Successfully',
                
            ], 201);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
