<?php

namespace App\Http\Controllers\Api;

use App\Models\Reply;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MessageReplyController extends Controller
{
    function add_file($mid,Request $request){

        if($request->hasFile('audio')){
            $id = str_random(15);
            $file_name = $id . '.' . $request->file('audio')->getClientOriginalExtension();
            Storage::disk('local')->put(
                $file_name,
                File::get($request->file('audio'))
            );
            $m = \App\Models\Reply::where("id",$mid)->first();
            $m->audio_src=$file_name;
            $m->save();
        }

        if($request->hasFile('data')){
            $id = str_random(15);
            $file_name = $id . '.' . $request->file('data')->getClientOriginalExtension();
            Storage::disk('local')->put(
                $file_name,
                File::get($request->file('data'))
            );
            $m = \App\Models\Reply::where("id",$mid)->first();
            $m->data_src=$file_name;
            $m->save();
        }
        return response()->json(["message" => "add"]);

    }


    function reply(Request $request)
    {
        if($temp=Reply::create([
           "user_id" => $request->user_id,
            "message_id" => $request->message_id,
            "reply" => $request->content
        ])){
            return response()->json(["reply"=>true,"reply_id"=>$temp->id]);
        }
        return response()->json(["reply"=>false]);
    }

    function get_reply(Request $request)
    {

        $reply = Reply::where('message_id',$request->message_id)->get();
        return response()->json($reply);
    }
}
