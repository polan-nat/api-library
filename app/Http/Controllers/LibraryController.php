<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Dotenv\Validator;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index()
    {
        $library = Library::get()->toJson(JSON_PRETTY_PRINT);
        return response($library, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'title' => 'required|max:100',
            'editor' => 'required|max:100',
            'author' => 'required|max:100',
            'image' => 'required|mimes:jpg,png'
        ]);

        $upload = $request->image->store('image');

        $library = new Library($request->all());
        $library->save();

        return response()->json([
            'message' => 'Saved successfully!'
        ], 201);
       
    }

   
    public function show($id)
    {
        if (Library::where('id', $id)->exists()) {
            $library = Library::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($library, 200);
        } else {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Library::where('id', $id)->exists()) {
            $library = Library::find($id);
            $library->title = is_null($request->title) ? $library->title : $request->title;
            $library->editor = is_null($request->editor) ? $library->editor : $request->editor;
            $library->author = is_null($request->author) ? $library->author : $request->author;
            $library->image = is_null($request->image) ? $library->image : $request->image;
            $library->save();

            return response()->json([
                'message' => 'Updated data!'
            ], 202);
        } else {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }
    }

  
    public function destroy($id)
    {
        if (Library::where('id', $id)->exists()) {
            $library = Library::find($id);
            $library->delete();

            return response()->json([
                'message' => 'Data deleted!'
            ], 202);
        } else {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }
    }
}
