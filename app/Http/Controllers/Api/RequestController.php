<?php

namespace App\Http\Controllers\Api;

use App\Helpers\NotificationTrait;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RequestController extends Controller
{
    use NotificationTrait;
    
    function add(Request $request){
        $temp = \App\Models\Request::whereIn('fromUser_id',[$request->user_id,$request->user2_id])
            ->whereIn('toUser_id',[$request->user_id,$request->user2_id])->get();

        if(count($temp)){
            return response()->json([
                'request' => false
            ]);
        }
        \App\Models\Request::create([
            "fromUser_id" => $request->user_id,
            "toUser_id" => $request->user2_id,
            "status" => 'pending'
        ]);
        $message = array('PushType' => 'request', 'name' => User::find($request->user_id)->name); // reply {message_id} , message {tag_id} !  
        $tokens = array();
        $tc=json_encode($message);
        // foreach ($d as $dr) {
        array_push($tokens, User::find($request->user2_id)->device_id);
        // }
                $this->send_notification($tokens, $message);
        return response()->json([
            'request' => true
        ]);
    }
    
    function all(Request $request){
        $r = \App\Models\Request::where('toUser_id',$request->user_id)
        ->where('status','pending')->get();
        foreach ($r as $member){
            $member->name = User::where('id', $member->fromUser_id)->first()->name;
            // unset($member->created_at);
            // unset($member->updated_at);
            // unset($member->fromUser_id);
            unset($member->toUser_id);
            unset($member->status);
        }
        return response()->json([
           'requests' => $r
        ]);
    }
    
    function accept(Request $request){
        $r = \App\Models\Request::find($request->request_id);
        $r->status = 'accept';
        $r->save();

        \App\Models\FriendList::create([
            'user1_id' => $r->fromUser_id,
            'user2_id' => $r->toUser_id
        ]);
        return response()->json([
            'friends' => true
        ]);
    }
    
    function block_user(Request $request)
    {

        $r = \App\Models\Request::find($request->request_id);
        $r->status = 'block';
        $r->save();

        \App\Models\BlockUser::create([
            'owner_id' => $r->toUser_id,
            'victim_id' => $r->fromUser_id
        ]);

        return response()->json([
            'blocked' => true
        ]);
    }
}