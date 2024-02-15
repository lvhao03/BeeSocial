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
        $groupList = Group::whereHas('group_members', function ($query) {
            $query->where('member_id', \Auth::user()->id);
        })->get();
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

    public function get_friend_by_id(Request $request, $friendID){
        $friend = User::where('id', $friendID)->first();
        return response()->json($friend);
    }

    public function get_friend_name(Request $request, $friend_name){
        $user_list = User::where('name',  'LIKE', '%'.$friend_name.'%')->get();
        return response()->json($user_list);
    }

    public function send(Request $request){
        try {
            $request->validate([
                'message' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Tin nhắn không được để trống'], 422);
        }

        $message = Message::create([
            'message_text' => $request->message,
            'sender_id' => \Auth::user()->id,
            'receiver_id' => $request->receiver_id,
        ]);
        event(new SendChat(
            $request->message, 
            \Auth::user()->id,
            $request->receiver_id, 
            $request->room_id,
            $request->sent_date
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
            'receiver_id' => $request->receiver_id,
        ]);

        event(new SendChat(
            $imagePath, 
            $request->receiver_id, 
            $request->sent_date
        ));
        return response()->json(['message' => 'File uploaded successfully']);
    }
}
