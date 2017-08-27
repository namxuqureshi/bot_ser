<?php

namespace App\Http\Controllers\Api;

use App\Models\FriendList;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    
    function friend_list(Request $request){

        $friends = FriendList::where('user1_id',$request->user_id)
                    ->orWhere('user2_id',$request->user_id)->get();

        foreach ($friends as $key => $friend){

            if($friend->user1_id == $request->user_id){
                $temp = GroupMember::where('group_id',$request->group_id)->where('user_id',$friend->user2_id)->get();
                if(count($temp)){
                    unset($friends[$key]);
                }
            }else{
                $temp = GroupMember::where('group_id',$request->group_id)->where('user_id',$friend->user1_id)->get();
                if(count($temp)){
                    unset($friends[$key]);
                    
                }
            }
        }
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
        $temp_array = array();
        foreach ($friends as $friend ){
            array_push($temp_array,$friend);
        }
        return response()->json(["friends" => $temp_array]);
    }
    function add(Request $request)
    {

        Group::create([
            'name' => $request->name,
            'owner_id' => $request->user_id
        ]);
        return response()->json([
            "group" => true
        ]);
    }

    function all(Request $request)
    {

        $groups = Group::where('owner_id', $request->user_id)->get();
        foreach ($groups as $group) {
            unset($group->created_at);
            unset($group->updated_at);
            unset($group->owner_id);
            $members = GroupMember::where('group_id', $group->id)->get();
            foreach ($members as $member){
                $member->name = User::where('id', $member->user_id)->first()->name;
                unset($member->created_at);
                unset($member->updated_at);
                // unset($member->group_id);
                // unset($member->user_id);
            }
            $group->member = $members;
        }
        return response()->json([
            "groups" => $groups
        ]);
    }
    
        
    function add_group_members(Request $request)
    {

        foreach ($request->id as $id) {
            GroupMember::create([
                "group_id" => $request->group_id,
                "user_id" => $id
            ]);
        }
        
        /*
        BUTT:
        
        Group Added  ,   Tag Added  , etc
        
        dont you think it would be better if there is a standard response 
        
        in case of any type of resource created
        
        */
        
        
        return response()->json([
            "members" => true
        ]);
    }
    
      function delete(Request $request){

        /*
        *
        Butt:
        Find or Fail   
        
        What if that record is not found !
        
        */


        $group = Group::findOrFail($request->group_id);
        $group->delete();
        
        ///  BUTT :  Some Standard Response !!!
        return response()->json([
            "deleted" => true
        ]);
    }
}
