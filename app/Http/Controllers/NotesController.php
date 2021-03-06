<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNote;
use App\Http\Resources\Notes;
use App\Note;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $note = Note::query();
        $term = $request->get('term');

        if ($term) {
            $note->where('note', 'like', $term . '%')->where(
                function ($query) {
                    $query->where('status', '=', 'public')->orWhere('owner_id', auth()->id());
                }
            );

        } else {
            $note->where('owner_id', auth()->id())->orWhere('status', '=', 'public');

        }

        $notes = $note->oldest()->with('tag')->get();

        $userId = auth()->id();
        $sortedNotes = $notes->sortBy(
            function ($model) use ($userId) {
                if ($model->owner_id === $userId) {
                    return -1;
                } else {
                    return 1;
                }
            }
        );

        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($sortedNotes, count($sortedNotes), $perPage);
        $paginatedItems->withPath(url()->full());

        $collection = Notes::collection($paginatedItems);

        return $collection;

    }

    public function create(Tag $tag)
    {
        $tags = $tag->pluck('tag');

        return view('notes.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'note'        => 'required|max:255',
                'description' => 'required|max:255',
                'status'      => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $note = new Note();
        $note->note = $request->get('note');
        $note->description = request('description');

        $note->owner_id = auth()->id();
        $note->status = request('status');

        $note->save();

        if ($request->get('tag')) {
            $tagIDs = Tag::whereIn('tag', $request->get('tag'))->pluck('id');
            $note->tag()->attach($tagIDs);
        }

        return redirect('/notes');
    }

    public function edit(Note $note)
    {
        if ($note->owner_id !== auth()->id()) {
            return response()->json('You can only edit your notes');
        };
        $note = Note::find($note->id);
        $tags = Tag::pluck('tag');

        return view('notes.edit', compact('note'), compact('tags'));
    }

    public function update(Note $note, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'note'        => 'required|max:255',
                'description' => 'required|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        if ($note->owner_id !== auth()->id()) {
            return response()->json('You cannot update a private note');
        };

        $note = Note::find($note->id);

        $note->note = $request->get('note');
        $note->description = $request->get('description');
        if ($request->get('tag')) {
            $tagIDs = Tag::whereIn('tag', $request->get('tag'))->pluck('id');
            $note->tag()->attach($tagIDs);
        }
        $note->status = request('status');
        $note->save();

        return redirect('/notes');
    }

    public function show(Note $note)
    {
        $notes = Note::query();

        if ($note->owner_id == auth()->id() || $note->status == 'Public') {
            $allNotes = Notes::collection($notes->where('id', $note->id)->paginate(5)->withPath(url()->full()));

            return $allNotes;
        } else {
            return response()->json('You cannot view a private note');
        }
    }
}
