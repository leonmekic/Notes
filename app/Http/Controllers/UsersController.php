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
        $users = User::all();

        return Users::collection($users);
    }

    public function showCurrentUser()
    {
        $user = User::where('id', Auth::user()->id)->get();

        return Users::collection($user);
    }

    public function showUserById($id)
    {
        $user = User::where('id', $id)->get();

        return Users::collection($user);
    }

    public function showUserNotes($id)
    {
        $notes = Note::where('owner_id', '=', $id)->where('status', '=', 'public')->with('tag')->paginate(5)->withPath(
            url()->full()
        );

        return Notes::collection($notes);
    }
}

