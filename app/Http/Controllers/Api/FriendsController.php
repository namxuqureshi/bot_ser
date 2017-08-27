<?php

namespace App\Http\Controllers\Api;

use App\Models\FriendList;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FriendsController extends Controller
{
    function all(Request $request){

        $friends = FriendList::where('user1_id',$request->user_id)
                    ->orWhere('user2_id',$request->user_id)->get();
        foreach ($friends as $friend){
            unset($friend->created_at);
            unset($friend->updated_at);
            unset($friend->id);
            if($friend->user1_id == $request->user_id){
                $friend->name = User::where('id',$friend->user2_id)->first()->name;
                unset($friend->user1_id);
                $friend->id = $friend->user2_id;
                unset($friend->user2_id);
            }else{
                $friend->name = User::where('id',$friend->user1_id)->first()->name;
                unset($friend->user2_id);
                $friend->id = $friend->user1_id;
                unset($friend->user1_id);
            }
        }
        return response()->json(["friends" => $friends]);
    }
}
