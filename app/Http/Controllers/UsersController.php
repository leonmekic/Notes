<?php

namespace App\Http\Controllers;

use App\Http\Resources\Notes;
use App\Http\Resources\Users;
use App\Note;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showUsers()
    {
        return Users::collection(User::all());
    }

    public function showCurrentUser()
    {
        return Users::collection(User::query()->where('id', Auth::user()->id)->get());
    }

    public function showUserById($id)
    {
        return Users::collection(User::query()->where('id', $id)->get());
    }

    public function showUserNotes($id)
    {
        $notes['public notes'] = Notes::collection(
            Note::where('owner_id', '=', $id)->where('status', '=', 'public')->with('tag')->get()
        );

        return response()->json($notes);
    }
}
