<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MessagesCases;
use App\Helpers\Permissions;
use App\Models\FriendList;
use App\Models\GroupMember;
use App\Models\Message;
use App\Models\MessageGroupPermission;
use App\Models\MessageUserPermission;
use App\Models\Reply;
use App\Models\Tag;
use App\Models\View;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Helpers\NotificationTrait;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    use NotificationTrait;

    function add_file($mid,Request $request){
        
        if($request->hasFile('audio')){
            $id = str_random(15);
            $file_name = $id . '.' . $request->file('audio')->getClientOriginalExtension();
            Storage::disk('local')->put(
                $file_name,
                File::get($request->file('audio'))
            );
            $m = \App\Models\Message::where("id",$mid)->first();

//            dd($m);
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
            $m = \App\Models\Message::where("id",$mid)->first();
            $m->data_src=$file_name;
            $m->save();
        }
        return response()->json(["message" => "add"]);

    }
    private function create_message($request)
    {

        $m = Message::create($request->all());
        if($request->hasFile('audio')){
            $id = str_random(15);
            $file_name = $id . '.' . $request->file('audio')->getClientOriginalExtension();
            Storage::disk('local')->put(
                $file_name,
                File::get($request->file('audio'))
            );
            $m->audio_src=$file_name;
            $m->save();
        }
        $message = array("PushType" => "message", "tag_id" => $request->tag_id, "name" => User::find($request->user_id)->name); // reply {message_id} , message {tag_id} !
        $tokens = array();
        // foreach ($d as $dr) {
        array_push($tokens, User::find(Tag::find($request->tag_id)->user_id)->device_id);
        // }
        $this->send_notification($tokens, $message);

        return $m;

    }

    function new_message(Request $request)
    {
        // dd($request->all());
        $owner_id = Tag::find($request->tag_id)->user_id;
        if ($owner_id != $request->user_id) {
            $m = $this->create_message($request);
            return response()->json(["message_id"=>$m->id]);
        }
        switch ($request->case_type) {

            case MessagesCases::Public_All:
                $message = $this->create_message($request);
                break;

            case MessagesCases::Only_Friends:
                $message = $this->create_message($request);
                break;

            case MessagesCases::User_Share:

                $message = $this->create_message($request);
                foreach ($request->share_users as $user_id) {
                    MessageUserPermission::create([
                        "message_id" => $message->id,
                        "user_id" => $user_id,
                        "permission_id" => Permissions::READ
                    ]);
                }
                break;

            case MessagesCases::User_Dont_Share:

                $message = $this->create_message($request);
                foreach ($request->dont_share_users as $user_id) {
                    MessageUserPermission::create([
                        "message_id" => $message->id,
                        "user_id" => $user_id,
                        "permission_id" => Permissions::NONE
                    ]);
                }
                break;

            case MessagesCases::Group_Share:

                $message = $this->create_message($request);
                foreach ($request->share_groups as $group_id) {
                    MessageGroupPermission::create([
                        "message_id" => $message->id,
                        "group_id" => $group_id,
                        "permission_id" => Permissions::READ
                    ]);
                }
                break;

            case MessagesCases::Group_Dont_Share:

                $message = $this->create_message($request);
                foreach ($request->dont_share_groups as $group_id) {
                    MessageGroupPermission::create([
                        "message_id" => $message->id,
                        "group_id" => $group_id,
                        "permission_id" => Permissions::NONE
                    ]);
                }
                break;

            case MessagesCases::PUBLIC_USER:

                $message = $this->create_message($request);
                foreach ($request->dont_share_users as $user_id) {
                    MessageUserPermission::create([
                        "message_id" => $message->id,
                        "user_id" => $user_id,
                        "permission_id" => Permissions::NONE
                    ]);
                }
                break;

            case MessagesCases::PUBLIC_GROUP:

                $message = $this->create_message($request);
                foreach ($request->dont_share_groups as $group_id) {
                    MessageGroupPermission::create([
                        "message_id" => $message->id,
                        "group_id" => $group_id,
                        "permission_id" => Permissions::NONE
                    ]);
                }
                break;

            case MessagesCases::USER_USER:

                $message = $this->create_message($request);
                foreach ($request->share_users as $user_id) {
                    MessageUserPermission::create([
                        "message_id" => $message->id,
                        "user_id" => $user_id,
                        "permission_id" => Permissions::READ
                    ]);
                }
                foreach ($request->dont_share_users as $user_id) {
                    MessageUserPermission::create([
                        "message_id" => $message->id,
                        "user_id" => $user_id,
                        "permission_id" => Permissions::NONE
                    ]);
                }
                break;

            case MessagesCases::USER_GROUP:

                $message = $this->create_message($request);
                foreach ($request->share_users as $user_id) {

                    MessageUserPermission::create([
                        "message_id" => $message->id,
                        "user_id" => $user_id,
                        "permission_id" => Permissions::READ
                    ]);
                }
                foreach ($request->dont_share_groups as $group_id) {

                    MessageGroupPermission::create([
                        "message_id" => $message->id,
                        "group_id" => $group_id,
                        "permission_id" => Permissions::NONE
                    ]);
                }
                break;

            case MessagesCases::GROUP_USER:
                // dd($request->all());
                $message = $this->create_message($request);
                // dd($request->share_groups);
                foreach ($request->share_groups as $group_id) {

                    MessageGroupPermission::create([
                        "message_id" => $message->id,
                        "group_id" => $group_id,
                        "permission_id" => Permissions::READ
                    ]);
                }
                foreach ($request->dont_share_users as $user_id) {

                    MessageUserPermission::create([
                        "message_id" => $message->id,
                        "user_id" => $user_id,
                        "permission_id" => Permissions::NONE
                    ]);
                }
                // dd($message);
                break;

            case MessagesCases::GROUP_GROUP:
// dd($request->all());
                $message = $this->create_message($request);

                foreach ($request->share_groups as $group_id) {

                    MessageGroupPermission::create([
                        "message_id" => $message->id,
                        "group_id" => $group_id,
                        "permission_id" => Permissions::READ
                    ]);
                }
                foreach ($request->dont_share_groups as $group_id) {

                    MessageGroupPermission::create([
                        "message_id" => $message->id,
                        "group_id" => $group_id,
                        "permission_id" => Permissions::NONE
                    ]);
                }
                break;

            case MessagesCases::FRIENDS_GROUP:

                $message = $this->create_message($request);
                foreach ($request->dont_share_groups as $group_id) {

                    MessageGroupPermission::create([
                        "message_id" => $message->id,
                        "group_id" => $group_id,
                        "permission_id" => Permissions::NONE
                    ]);
                }
                break;

            case MessagesCases::FRIENDS_USER:

                $message = $this->create_message($request);
                foreach ($request->dont_share_users as $user_id) {
                    MessageUserPermission::create([
                        "message_id" => $message->id,
                        "user_id" => $user_id,
                        "permission_id" => Permissions::NONE
                    ]);
                }
                break;

            default:
                $message = $this->create_message($request);
                if ($request->friends_only != null) {
                    $message->friends_only = 1;
                    $message->save();
                }
                break;
        }
        return response()->json(["message_id"=>$message->id]);
    }

    function get_messages(Request $request)
    {
        $owner_id = Tag::find($request->tag_id)->user_id;
        $messages = Message::where("tag_id", $request->tag_id)->where("user_id", $owner_id)->get();
//        dd($messages);
        // $messages = Message::all()->where("tag_id", $request->tag_id);

        if ($owner_id != $request->user_id) {

            foreach ($messages as $i => $message) {

                if ($message->timeout > Carbon::now() || $message->timeout == null) {
                    switch ($message->case_type) {


                        case MessagesCases::Public_All:
                            break;

                        case MessagesCases::Only_Friends:

                            $friends = FriendList::whereIn('user1_id', [$request->user_id, $owner_id])
                                ->whereIn('user2_id', [$request->user_id, $owner_id])->first();
                            if ($friends == null) {
                                $messages->forget($i);
                                //                        dd($messages->all());
                            }
                            break;

                        case MessagesCases::User_Share:

                            $groups = MessageUserPermission::where("message_id", $message->id)
                                ->where("user_id", $request->user_id)
                                ->where("permission_id", Permissions::READ)
                                ->get();
                            if (!count($groups)) {
                                $messages->forget($i);
                            }
                            //                   dd($messages->all());
                            break;

                        case MessagesCases::User_Dont_Share:

                            $users = MessageUserPermission::where("message_id", $message->id)
                                ->where("user_id", $request->user_id)
                                ->where("permission_id", Permissions::NONE)
                                ->get();
                            if (count($users)) {
                                $messages->forget($i);
                            }
                            //                    dd($messages->all());
                            break;

                        case MessagesCases::Group_Share:

                            $user_groups = GroupMember::where("user_id", $request->user_id)->pluck("group_id");
                            $groups = MessageGroupPermission::where("message_id", $message->id)
                                ->whereIn("group_id", $user_groups->all())
                                ->where("permission_id", Permissions::READ)
                                ->get();
                            if (!count($groups)) {
                                $messages->forget($i);
                            }
                            break;

                        case MessagesCases::Group_Dont_Share:

                            $user_groups = GroupMember::where("user_id", $request->user_id)->pluck("group_id");
                            $groups = MessageGroupPermission::where("message_id", $message->id)
                                ->whereIn("group_id", $user_groups->all())
                                ->where("permission_id", Permissions::NONE)
                                ->get();
                            if (count($groups)) {
                                $messages->forget($i);
                            }
                            break;

                        case MessagesCases::PUBLIC_USER:

                            $users = MessageUserPermission::where("message_id", $message->id)
                                ->where("user_id", $request->user_id)
                                ->where("permission_id", Permissions::NONE)
                                ->get();
                            if (count($users)) {
                                $messages->forget($i);
                            }
                            //                    dd($messages->all());
                            break;

                        case MessagesCases::PUBLIC_GROUP:

                            $user_groups = GroupMember::where("user_id", $request->user_id)->pluck("group_id");
                            $groups = MessageGroupPermission::where("message_id", $message->id)
                                ->whereIn("group_id", $user_groups->all())
                                ->where("permission_id", Permissions::NONE)
                                ->get();
                            if (count($groups)) {
                                $messages->forget($i);
                            }
                            break;

                        case MessagesCases::USER_USER:

                            $users = MessageUserPermission::where("message_id", $message->id)
                                ->where("user_id", $request->user_id)
                                ->where("permission_id", Permissions::READ)
                                ->get();
                            if (!count($users)) {
                                $messages->forget($i);
                            }
                            //                   dd($messages->all());
                            break;

                        case MessagesCases::USER_GROUP:

                            $users = MessageUserPermission::where("message_id", $message->id)
                                ->where("user_id", $request->user_id)
                                ->where("permission_id", Permissions::READ)
                                ->get();
                            if (!count($users)) {
                                $messages->forget($i);
                            }
                            //                   dd($messages->all());
                            break;

                        case MessagesCases::GROUP_USER:

                            $user_groups = GroupMember::where("user_id", $request->user_id)->pluck("group_id");
                            $groups = MessageGroupPermission::where("message_id", $message->id)
                                ->whereIn("group_id", $user_groups->all())
                                ->where("permission_id", Permissions::READ)
                                ->get();
                            if (!count($groups)) {
                                $messages->forget($i);
                            } else {
                                $users = MessageUserPermission::where("message_id", $message->id)
                                    ->where("user_id", $request->user_id)
                                    ->where("permission_id", Permissions::NONE)
                                    ->get();
                                if (count($users)) {
                                    $messages->forget($i);
                                }
                            }
                            // dd($messages);
                            break;

                        case MessagesCases::GROUP_GROUP:

                            $user_groups = GroupMember::where("user_id", $request->user_id)->pluck("group_id");
                            $groups = MessageGroupPermission::where("message_id", $message->id)
                                ->whereIn("group_id", $user_groups->all())
                                ->where("permission_id", Permissions::READ)
                                ->get();
                            if (!count($groups)) {
                                $messages->forget($i);
                            }
                            break;

                        case MessagesCases::FRIENDS_GROUP:

                            $friends = FriendList::whereIn('user1_id', [$request->user_id, $owner_id])
                                ->whereIn('user2_id', [$request->user_id, $owner_id])->first();
                            if ($friends == null) {

                                $messages->forget($i);
                                //                        dd($messages->all());
                            } else {
                                $user_groups = GroupMember::where("user_id", $request->user_id)->pluck("group_id");
                                $groups = MessageGroupPermission::where("message_id", $message->id)
                                    ->whereIn("group_id", $user_groups->all())
                                    ->where("permission_id", Permissions::NONE)
                                    ->get();
                                if (count($groups)) {
                                    $messages->forget($i);
                                }
                            }
                            //                    dd($messages);
                            break;

                        case MessagesCases::FRIENDS_USER:

                            $friends = FriendList::whereIn('user1_id', [$request->user_id, $owner_id])
                                ->whereIn('user2_id', [$request->user_id, $owner_id])->first();
                            if ($friends == null) {
                                $messages->forget($i);
                                //                        dd($messages->all());
                            } else {
                                $users = MessageUserPermission::where("message_id", $message->id)
                                    ->where("user_id", $request->user_id)
                                    ->where("permission_id", Permissions::NONE)
                                    ->get();
                                if (count($users)) {
                                    $messages->forget($i);
                                }
                            }
                            break;

                        default:
                            break;
                    }
                } else {
                    $message->delete();
                }
            }
        }
        // dd($messages->ToArray());
        $msg = collect();
        foreach ($messages as $m) {
            if ($m->timeout > Carbon::now() || $m->timeout == null) {
                $m->time = $m->created_at->diffForHumans();
                $m->reply_count =  Reply::where('message_id',$m->id)->count();
                $msg->push($m);
            } else {
                $m->delete();
            }
        }
        return response()->json($msg);
    }

    function received_message(Request $request)
    {

        $owner_id = Tag::find($request->tag_id)->user_id;
        $messages = Message::where("tag_id", $request->tag_id)->where("user_id", "<>", $owner_id)->get();
        $msg = collect();
        foreach ($messages as $m) {
            if ($m->timeout > Carbon::now() || $m->timeout == null) 
            {
                $m->time = $m->created_at->diffForHumans();
                $m->reply_count =  Reply::where('message_id',$m->id)->count();
                $msg->push($m);
            } else {
                $m->delete();
            }
        }
        return response()->json($msg);
    }

    function message_detail(Request $request)
    {

        $message = Message::find($request->message_id);
        $owner_id = Tag::find($message->tag_id)->user_id;

        if ($owner_id == $request->user_id) {
            $message->seen_by = View::where("message_id", $message->id)->count();
        } else {
            $v = View::where("message_id", $message->id)->where("user_id", $request->user_id)->count();
            if (!$v) {
                View::create([
                    "message_id" => $message->id,
                    "user_id" => $request->user_id
                ]);
            }
        }
        $message->replies = Reply::where('message_id', $request->message_id)->get();
        $msg=collect();
        foreach ($message->replies as $m) {
            // if ($m->timeout > Carbon::now() || $m->timeout == null) 
            // {
                $m->time = $m->created_at->diffForHumans();
                // $m->reply_count =  Reply::where('message_id',$m->id)->count();
                $msg->push($m);
            // } else {
            //     $m->delete();
            // }
        }
        $message->replies=$msg;
        
        return response()->json($message);
    }

    function delete(Request $request)
    {
        $message = Message::where("id", $request->message_id)->first();
        $message->delete();
        return response()->json(["message" => "deleted"]);
    }
}
