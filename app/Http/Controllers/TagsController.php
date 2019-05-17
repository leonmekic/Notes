<?php

namespace App\Http\Controllers;

use App\Http\Resources\Notes;
use App\Http\Resources\Tags;
use App\Note;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function notesByTag($id)
    {
        $note = Note::query();
        $notes = $note->whereHas(
            'tag',
            function ($query) use ($id) {
                $query->where('owner_id', '=', auth()->id())
                      ->where('tag_id', $id)
                      ->orWhere('status', '=', 'public')
                      ->where(
                          'tag_id',
                          $id
                      );
            }
        )->get();

        return Notes::collection($notes);
    }

    public function showTags(Request $request)
    {
        $term = $request->get('term');
        if ($term) {
            return Tags::collection(
                Tag::query()->where('tag', 'like', '%' . $term . '%')->orderBy('tag')->paginate(5)->withPath(
                    url()->full()
                )
            );
        } else {
            return Tags::collection(Tag::paginate(5)->withPath(url()->full()));
        }

    }
}
