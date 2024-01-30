<?php

namespace App\Http\Controllers;
use App\Events\SendChat;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Group;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function index(Request $request){
        $friendList = User::where('id', '!=', \Auth::user()->id)->get();
        $friendList = $this->addNewestMessageToFriendList($friendList);
        $groupList = Group::all();
        return view("index", ['friendList' => $friendList, 'groupList' => $groupList]);
    }

    public function addNewestMessageToFriendList($friendList){
        foreach($friendList as $friend) {
            $message = Message::where(function ($query) use ($friend) {
                $query->where('receiver_id', \Auth::user()->id)
                        ->where('sender_id', $friend->id);
            })
            ->orWhere(function ($query) use ($friend) {
                $query->where('receiver_id', $friend->id)
                        ->where('sender_id', \Auth::user()->id);
            })
            ->orderBy('sent_date', 'desc')
            ->value('message_text');
            $friend->newest_message = $message;
            \Log::info($friend->newest_message);
        };
        return $friendList;
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
    }

    public function send(Request $request){
        $message = Message::create([
            'message_text' => $request->input('message'),
            'sender_id' => \Auth::user()->id,
            'receiver_id' => $request->input('receiver_id'),
        ]);
        event(new SendChat(
            $request->input('message'), 
            \Auth::user()->id,
            $request->input('receiver_id'), 
            $request->input('room_id'),
            $request->input('sent_date')
        ));
        return response()->json($message);
    }

    public function upload(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $imagePath = $request->file('image')->store('uploads','public');

        Message::create([
            'message_text' =>  $imagePath,
            'sender_id' => \Auth::user()->id,
            'receiver_id' => $request->input('receiver_id'),
        ]);

        event(new SendChat(
            $imagePath, 
            $request->input('receiver_id'), 
            $request->input('room_id'),
            $request->input('sent_date')
        ));
        return response()->json(['message' => 'File uploaded successfully']);
    }
}
