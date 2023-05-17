<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagApiController extends Controller
{
    public function update(Request $request, Tag $tag) {
        $formFields = $request->validate([
            'name' => ['bail', 'required', Rule::unique('tags')->ignore($tag->id)]
        ]);
        $tag->name = $formFields['name'];
        $tag->save();
        return response()->json([
            'message' => 'Название тега успешно изменено.'
        ]);
    }
    public function destroy(Tag $tag) {
        $tag->delete();
        return response()->json([
            'message' => 'Тег успешно удалён.'
        ]);
    }
}
