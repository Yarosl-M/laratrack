<?php
namespace App\Services;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

class TagService {
    public function getAll(): Collection|null {
        return Tag::get();
    }
    public function get(string $id): Tag|null {
        $t = Tag::find($id);
        return $t;
    }
    public function create(string $name): Tag {
        $t = new Tag();
        $t->name = $name;
        $t->save();
        return $t;
    }
    public function update(string $id, string $newName): Tag|null {
        $t = Tag::find($id);
        $t->name = $newName;
        $t->save();
        return $t;
    }
    public function delete(string $id) {
        $t = Tag::find($id);
        $t->delete();
    }
}