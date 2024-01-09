<?php

namespace App\Http\Controllers;
use App\Events\SendChat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request){
        return view("index");
    }
    public function send(Request $request){
        event(new SendChat($request->input('message')));
    }
}
