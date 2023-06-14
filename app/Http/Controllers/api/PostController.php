<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function Index()
    {
        return Post::with('user')->get();
    }

    public function show(Post $post)
    {
        $post->load('user');
        return $post;
    }

    public function store(PostRequest $request)
    {

        try {
            $imageName=null;
            if ($request->image)
            { $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();}
           

            Post::create([
              
                'image' => $imageName,
                
               
                'description' => $request->description,
                'user_id' => $request->user_id,
            ]);
            if ($request->image)
            {Storage::disk('public')->put($imageName, file_get_contents($request->image));}
            

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

    public function update(PostRequest $request, Post $post)
    {

        
        if ($request->hasFile('image')) {
            
            // Delete the old image if exists
            if ($post->image && Storage::exists($post->image)) {
                Storage::delete($post->image);
            }
            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
            Storage::disk('public')->put($imageName, file_get_contents($request->image));
        }
        $post->update($request->validated());
        return response()->json('updated successfully');
       
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json('ticketdeleted succefully');
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        $results = Post::where(function ($query) use ($searchTerm) {
            $columns = Schema::getColumnListing('posts');
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
            }
        })->get();

        return response()->json($results);
    }
}
