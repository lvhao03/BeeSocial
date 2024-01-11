<?php

namespace App\Http\Controllers;
use App\Events\SendChat;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function index(Request $request){
        $friendList = User::where('id', '!=', \Auth::user()->id)->get();
        return view("index", ['friendList' => $friendList ]);
    }

    public function privateChat($receiver_id){
        $result = Message::where(function ($query) use ($receiver_id) {
            $query->where('receiver_id', \Auth::user()->id)
                    ->where('sender_id', $receiver_id);
        })
        ->orWhere(function ($query) use ($receiver_id) {
            $query->where('receiver_id', $receiver_id)
                    ->where('sender_id', \Auth::user()->id);
        })
        ->get();
        $receiverUser = User::where('id' , $receiver_id)->first();
        $result->push($receiverUser);;
        return response()->json($result);
    }

    public function saveReceiver_id(Request $request){
        $image_url = User::where('id',$request->input('receiver_id'))->value('image_url');
        session()->put('receiver_id', $request->input('receiver_id') );
        session()->put('receiver_image', $image_url );
        \Log::info(session('receiver_image'));
    }

    public function send(Request $request){
        Message::create([
            'message_text' => $request->input('message'),
            'sender_id' => \Auth::user()->id,
            'receiver_id' => $request->input('receiver_id'),
        ]);
        event(new SendChat($request->input('message'), $request->input('receiver_id'), $request->input('room_id')));
    }
}
