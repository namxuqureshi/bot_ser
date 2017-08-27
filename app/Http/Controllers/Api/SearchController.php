<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\FriendList;
use App\Models\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;


// BUTT:  Why need a Search Controller ?

class SearchController extends Controller
{
   function search(Request $request){

// Butt : Is it Safe ?  you are directly  using the request Name into it
        $users = User::where('name', 'LIKE', '%'.$request->name.'%')
        ->whereNotIn('id', [$request->user_id])
        ->get();

// BUTT: Find or Fail
        foreach ($users as $user){
            $user->friends = false;
            $temp = FriendList::whereIn('user1_id',[$request->user_id,$user->id])
                        ->whereIn('user2_id',[$request->user_id,$user->id])->get();
            if(count($temp)){
                $user->friends = true;
            }
            // BUTT: Whats this ?
            unset($temp);
            $user->request = false;
            
            // BUTT:  ye kia Fish Market banai hoi hai Code ki
            unset($user->password);
            unset($user->remember_token);
            $temp = \App\Models\Request::whereIn('fromUser_id',[$request->user_id,$user->id])
                        ->whereIn('toUser_id',[$request->user_id,$user->id])
                        ->whereIn('status',['accept','pending'])->get();
            if(count($temp)){
                $user->request = true;
            }
        }

        return response()->json([
            "users" => $users
        ]);
    }
}
