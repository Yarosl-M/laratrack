<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagApiController extends Controller
{
    public function update(Request $request, Tag $tag) {
        $formFields = $request->validate([
            'name' => ['required', Rule::unique('tags')->ignore($tag->id)]
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
    public function store(Request $request) {
        $formFields = $request->validate([
            'name' => ['required', 'unique:tags', 'max:100']
        ]);
        $tag = new Tag;
        $tag->name = $formFields['name'];
        $tag->save();
        $component = view('components.tag-dashboard-card', [
            'tag' => $tag,
            'usages' => $tag->tickets->count()
        ]);
        $html = $component->render();
        return response()->json([
            'message' => 'Тег успешно создан',
            'id' => $tag->id,
            'html' => $html,
        ]);
    }
}
