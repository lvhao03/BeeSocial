<?php

namespace App\Http\Controllers;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function create(Request $request){
        Group::create([
            'group_name' => $request->input('group_name')
        ]);
        return response()->json(Group::all());
    }

    public function add_member_to_group(){
        
    }
}
