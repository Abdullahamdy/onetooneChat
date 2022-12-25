<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function index()
    {
        $user = User::find(Auth::id());
        $users = User::all();

    return view('chat',compact('users'));
    }


    public function sendMessage(Request $request){

        $message =    auth()->user()->messages()->create(['message'=>$request->message,'receiver_id'=>1]);
        event(new MessageSent(auth()->user(), $message->load('user')));
        return response(['status'=>'message sent successfully']);


    }



}
