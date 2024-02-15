<?php

namespace App\Http\Controllers;
use App\Models\Group;
use App\Models\User;
use App\Models\Message;
use App\Models\GroupMember;
use App\Events\SendGroupMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request, $group_id){
        $result = Message::where('group_id', $group_id)->get();
        return response()->json($result);
    }

    public function get_detail(Request $request, $group_id){
        $result = DB::table('group')
                ->select('group.group_name', 'users.*')
                ->join('group_members', 'group.id', '=', 'group_members.group_id')
                ->join('users', 'users.id', '=', 'group_members.member_id')
                ->where('group.id', '=', $group_id)
                ->get();
        return response()->json($result);
    }

    public function create(Request $request){
        $group = Group::create([
            'group_name' => $request->group_name
        ]);
        $this->add_member_to_group($group, $request->member_id);
        return response()->json(Group::all());
    }

    public function add_member_to_group($group, $member_id){
        GroupMember::create([
            'group_id' =>  $group->id,
            'member_id' => $member_id
        ]);
    }

    public function send_group_chat_message(Request $request){
        try {
            $request->validate([
                'message' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Tin nhắn không được để trống'], 422);
        }

        $message = Message::create([
            'message_text' =>  $request->message,
            'sender_id' => \Auth::user()->id,
            'group_id' =>  $request->group_id,
        ]);
        event(new SendGroupMessage(
            $request->message, 
            \Auth::user()->id,
            $request->group_id,
            $request->sent_date
        ));
        return response()->json($message);
    }

    public function get_user_url(Request $request){
        $user = User::find($request->userID);
        return response()->json($user);
    }

    public function leave_group(Request $request){
        GroupMember::where('member_id' , $request->userID)
                    ->where('group_id', $request->groupID)
                    ->delete();
        return response()->json(['sucess', 'Xóa thành công']);
    }
}
