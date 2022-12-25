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

        $message =    auth()->user()->messages()->create(['message'=>$request->message]);
        event(new MessageSent(auth()->user(), $message->load('user')));
        return response(['status'=>'message sent successfully']);


    }

    public function specificationMessage($id){

        $fetchMessages = Message::with('user')
        ->where(['user_id'=>auth()->user()->id,'receiver_id'=>$id])
        ->orWhere(function($query) use($id){
            $query->where(['user_id'=>$id,'receiver_id'=>auth()->user()->id]);
        })->get();
        return response(['status'=>true,'fetchMessages'=>$fetchMessages]);

    }

    public function sendPrivateMessage(Request $request,$id){
        $message =    auth()->user()->messages()->create($request->except('_token'));
        broadcast(new MessageSent(auth()->user(), $message->load('user')));
        return response(['status'=>'message sent successfully']);


    }




}
