<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Comment::select('id', 'title', 'description', 'image')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image',
        ]);

        $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('comments/images', $request->image, $imageName);

        Comment::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json([
            'message' => 'Comment added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return response()->json([
            'comment' => $comment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        if ($request->has('title')) {
            $comment->title = $request->title;
        }

        if ($request->has('description')) {
            $comment->description = $request->description;
        }

        if ($request->hasFile('image')) {
            if ($comment->image) {
                $exist = Storage::disk('public')->exists("comments/images/{$comment->image}");
                if ($exist) {
                    Storage::disk('public')->delete("comments/images/{$comment->image}");
                }
            }

            $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('comments/images', $request->image, $imageName);
            $comment->image = $imageName;
        }

        $comment->save();

        return response()->json([
            'message' => 'Comment updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if ($comment->image) {
            $exist = Storage::disk('public')->exists("comments/images/{$comment->image}");
            if ($exist) {
                Storage::disk('public')->delete("comments/images/{$comment->image}");
            }
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
        ]);
    }
}
