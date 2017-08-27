<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Permissions;
use App\Helpers\TagCases;
use App\Models\FriendList;
use App\Models\GroupMember;
use App\Models\Tag;
use App\Models\Message;
use Carbon\Carbon;
use App\Models\TagGroupPermission;
use App\Models\TagPublicPermission;
use App\Models\TagUserPermission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    function user_tags($id)
    {
        $t = Tag::where('user_id', $id)->get();
        $tag_list = collect();
        foreach ($t as $m) {
            // if ( $m->timeout == null) 
            // {
                $m->time = Carbon::parse($m->created_at)->diffForHumans();
                $m->message_count =  Message::where('tag_id',$m->id)->count();
                $tag_list->push($m);
            // } else {
            //     $m->delete();
            // }
        }
        return response()->json(["tags" => $tag_list]);
    }

    function verify(Request $request)
    {
        $tag = Tag::where('ssn', $request->ssn)->first();
        if ($tag == null) {
            return response()->json([
                "exist" => false,
            ]);
        }
        if ($request->user_id == $tag->user_id) {
            return response()->json([
                "exist" => true,
                "owner" => true,
                "tag_id"=> $tag->id
            ]);
        } else {
            switch ($tag->permission_case) {

                case TagCases::_Public:
                    $per = TagPublicPermission::where("tag_id", $tag->id)->orderBy("id","desc")->first();
                    switch ($per->permission_id) {
                        case Permissions::NONE:
                            if ($this->friends($request->user_id, $tag->user_id)) {
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "rw",
                                    "tag_id"=> $tag->id
                                ]);
                            } else {
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "n",
                                    "tag_id"=> $tag->id,
                                    "user2_id"=> $tag->user_id
                                ]);
                            }
                            break;

                        case Permissions::READ:
                            if ($this->friends($request->user_id, $tag->user_id)) {
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "rw",
                                    "tag_id"=> $tag->id
                                ]);
                            } else {
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "r",
                                    "tag_id"=> $tag->id
                                ]);
                            }
                            break;

                        case Permissions::READ_WRTIE:
                            return response()->json([
                                "exist" => true,
                                "owner" => false,
                                "permission" => "rw",
                                "tag_id"=> $tag->id
                            ]);
                            break;
                    }
                    break;

                case TagCases::Users:

                    $per = TagUserPermission::where("tag_id", $tag->id)->where("user_id", $request->user_id)->orderBy("id","desc")->first();
                    if ($per != null) {
                        switch ($per->permission_id) {
                            case Permissions::NONE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "n",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                            case Permissions::READ:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "r",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                            case Permissions::READ_WRTIE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "rw",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                        }
                    } else {
                        return response()->json([
                            "exist" => true,
                            "owner" => false,
                            "permission" => "rw",
                            "tag_id"=> $tag->id
                        ]);
                    }
                    break;

                case TagCases::Groups:
                    // Todo
                    $user_groups = GroupMember::where("user_id", $request->user_id)->pluck("group_id");
                    $per = TagGroupPermission::whereIn("group_id", $user_groups->all())->orderBy("id", "DESC")->first(); // Could be more than one !
                    if ($per != null) {
                        switch ($per->permission_id) {
                            case Permissions::NONE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "n",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                            case Permissions::READ:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "r",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                            case Permissions::READ_WRTIE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "rw",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                        }
                    } else {
                        return response()->json([
                            "exist" => true,
                            "owner" => false,
                            "permission" => "rw",
                            "tag_id"=> $tag->id
                        ]);
                    }
                    break;

                case TagCases::Public_User:

                    if ($this->friends($request->user_id, $tag->user_id)) {
                        $per = TagUserPermission::where("tag_id", $tag->id)->where("user_id", $request->user_id)->orderBy("id","desc")->first();
                        if ($per != null) {
                            switch ($per->permission_id) {
                                case Permissions::NONE:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "n",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                                case Permissions::READ:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "r",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                                case Permissions::READ_WRTIE:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "rw",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                            }
                        } else {
                            return response()->json([
                                "exist" => true,
                                "owner" => false,
                                "permission" => "rw",
                                "tag_id"=> $tag->id
                            ]);
                        }
                    } else {
                        $per = TagPublicPermission::where("tag_id", $tag->id)->orderBy("id","desc")->first();
                        switch ($per->permission_id) {
                            case Permissions::NONE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "n",
                                    "tag_id"=> $tag->id,
                                    "user2_id"=> $tag->user_id
                                ]);
                                break;

                            case Permissions::READ:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "r",
                                    "tag_id"=> $tag->id
                                ]);
                                break;

                            case Permissions::READ_WRTIE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "rw",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                        }
                    }
                    break;

                case TagCases::Public_Group:
                    if ($this->friends($request->user_id, $tag->user_id)) {
                        $user_groups = GroupMember::where("user_id", $request->user_id)->pluck("group_id");
                        $per = TagGroupPermission::whereIn("group_id", $user_groups->all())->orderBy("id", "DESC")->first(); // Could be more than one !
                        if ($per != null) {
                            switch ($per->permission_id) {
                                case Permissions::NONE:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "n",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                                case Permissions::READ:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "r",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                                case Permissions::READ_WRTIE:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "rw",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                            }
                        } else {
                            return response()->json([
                                "exist" => true,
                                "owner" => false,
                                "permission" => "rw",
                                "tag_id"=> $tag->id
                            ]);
                        }
                    } else {
                        $per = TagPublicPermission::where("tag_id", $tag->id)->orderBy("id","desc")->first();
                        switch ($per->permission_id) {
                            case Permissions::NONE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "n",
                                    "tag_id"=> $tag->id,
                                    "user2_id"=> $tag->user_id
                                ]);
                                break;

                            case Permissions::READ:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "r",
                                    "tag_id"=> $tag->id
                                ]);
                                break;

                            case Permissions::READ_WRTIE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "rw",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                        }
                    }
                    break;

                case TagCases::Public_User_Group:

                    if ($this->friends($request->user_id, $tag->user_id)) {
                        $per = TagUserPermission::where("tag_id", $tag->id)->where("user_id", $request->user_id)->orderBy("id","desc")->first();
                        if ($per != null) {
                            switch ($per->permission_id) {
                                case Permissions::NONE:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "n",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                                case Permissions::READ:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "r",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                                case Permissions::READ_WRTIE:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "rw",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                            }
                        } else {
                            $user_groups = GroupMember::where("user_id", $request->user_id)->pluck("group_id");
                            $per = TagGroupPermission::whereIn("group_id", $user_groups->all())->orderBy("id", "DESC")->first(); // Could be more than one !
                            if ($per != null) {
                                switch ($per->permission_id) {
                                    case Permissions::NONE:
                                        return response()->json([
                                            "exist" => true,
                                            "owner" => false,
                                            "permission" => "n",
                                            "tag_id"=> $tag->id
                                        ]);
                                        break;
                                    case Permissions::READ:
                                        return response()->json([
                                            "exist" => true,
                                            "owner" => false,
                                            "permission" => "r",
                                            "tag_id"=> $tag->id
                                        ]);
                                        break;
                                    case Permissions::READ_WRTIE:
                                        return response()->json([
                                            "exist" => true,
                                            "owner" => false,
                                            "permission" => "rw",
                                            "tag_id"=> $tag->id
                                        ]);
                                        break;
                                }
                            } else {
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "rw",
                                    "tag_id"=> $tag->id
                                ]);
                            }
                        }
                        break;
                    } else {
                        $per = TagPublicPermission::where("tag_id", $tag->id)->orderBy("id","desc")->first();
                        switch ($per->permission_id) {
                            case Permissions::NONE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "n",
                                    "tag_id"=> $tag->id,
                                    "user2_id"=> $tag->user_id
                                ]);
                                break;

                            case Permissions::READ:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "r",
                                    "tag_id"=> $tag->id
                                ]);
                                break;

                            case Permissions::READ_WRTIE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "rw",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                        }
                    }
                    break;

                case TagCases::User_Group:
                    $per = TagUserPermission::where("tag_id", $tag->id)->where("user_id", $request->user_id)->orderBy("id","desc")->first();
                    if ($per != null) {
                        switch ($per->permission_id) {
                            case Permissions::NONE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "n",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                            case Permissions::READ:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "r",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                            case Permissions::READ_WRTIE:
                                return response()->json([
                                    "exist" => true,
                                    "owner" => false,
                                    "permission" => "rw",
                                    "tag_id"=> $tag->id
                                ]);
                                break;
                        }
                    } else {
                        $user_groups = GroupMember::where("user_id", $request->user_id)->pluck("group_id");
                        $per = TagGroupPermission::whereIn("group_id", $user_groups->all())->orderBy("id", "DESC")->first(); // Could be more than one !
                        if ($per != null) {
                            switch ($per->permission_id) {
                                case Permissions::NONE:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "n",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                                case Permissions::READ:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "r",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                                case Permissions::READ_WRTIE:
                                    return response()->json([
                                        "exist" => true,
                                        "owner" => false,
                                        "permission" => "rw",
                                        "tag_id"=> $tag->id
                                    ]);
                                    break;
                            }
                        } else {
                            return response()->json([
                                "exist" => true,
                                "owner" => false,
                                "permission" => "rw",
                                "tag_id"=> $tag->id
                            ]);
                        }
                    }
                    break;

                default:
                    break;
            }

        }

    }


    function add(Request $request)
    {

        if (Tag::where('ssn', $request->ssn)->first() == null) {
            Tag::create($request->all());
        
            return response()->json([
                "add" => true,
                "status" => "Tag Added"
            ]);
        }
        return response()->json([
            "add" => false,
            "status" => "Tag belongs to anyone else"
        ]);
    }

    function set_permissions(Request $request)
    {

        $t = Tag::find($request->tag_id);
        $t->permission_case = $request->permission_case;
        $t->save();

        switch ($request->permission_case) {

            case TagCases::_Public:
                TagPublicPermission::create($request->all());
                break;

            case TagCases::Users:
                foreach ($request->users as $user) {
                    TagUserPermission::create([
                        "tag_id" => $request->tag_id,
                        "permission_id" => $user[1],
                        "user_id" => $user[0],
                    ]);
                }
                break;

            case TagCases::Groups:
                foreach ($request->groups as $group) {
                    TagGroupPermission::create([
                        "tag_id" => $request->tag_id,
                        "group_id" => $group[0],
                        "permission_id" => $group[1],
                    ]);
                }
                break;

            case TagCases::Public_User:
                TagPublicPermission::create($request->all());
                foreach ($request->users as $user) {
                    TagUserPermission::create([
                        "tag_id" => $request->tag_id,
                        "permission_id" => $user[1],
                        "user_id" => $user[0],
                    ]);
                }
                break;

            case TagCases::Public_Group:
                TagPublicPermission::create($request->all());
                foreach ($request->groups as $group) {
                    TagGroupPermission::create([
                        "tag_id" => $request->tag_id,
                        "group_id" => $group[0],
                        "permission_id" => $group[1],
                    ]);
                }
                break;

            case TagCases::Public_User_Group:
                TagPublicPermission::create($request->all());
                foreach ($request->groups as $group) {
                    TagGroupPermission::create([
                        "tag_id" => $request->tag_id,
                        "group_id" => $group[0],
                        "permission_id" => $group[1],
                    ]);
                }

                foreach ($request->users as $user) {
                    TagUserPermission::create([
                        "tag_id" => $request->tag_id,
                        "permission_id" => $user[1],
                        "user_id" => $user[0],
                    ]);
                }
                break;

            case TagCases::User_Group:
                foreach ($request->groups as $group) {
                    TagGroupPermission::create([
                        "tag_id" => $request->tag_id,
                        "group_id" => $group[0],
                        "permission_id" => $group[1],
                    ]);
                }
                foreach ($request->users as $user) {
                    TagUserPermission::create([
                        "tag_id" => $request->tag_id,
                        "permission_id" => $user[1],
                        "user_id" => $user[0],
                    ]);
                }
                break;

            default:
                break;
        }
        return response()->json([
            'permission_set' => true
        ]);
    }

    private function friends($user_id, $owner_id)
    {
        $friends = FriendList::whereIn('user1_id', [$user_id, $owner_id])
            ->whereIn('user2_id', [$user_id, $owner_id])->first();
        return $friends != null;
    }
    
    public function update(Request $request, $id)
    {
        // dd($request->name);
        $temp=Tag::findOrFail($id);
        // dd($request->phone);
        // $temp->phone=$request->phone;
        // dd($request->all());
        if($temp->update($request->all()))
            return $temp;
        else return response()->json([
            'delete' => true
        ]);
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd(Tag::findOrFail($id));
        $message = Tag::where("id", $id)->first();
        if($message->delete())
            return response()->json([
            'delete' => true
        ]);
        else return response()->json([
            'delete' => false
        ]);
        //
    }
    function delete(Request $request)
    {
        $message = Tag::where("id", $request->tag_id)->first();
        $message->delete();
        return response()->json(["Tag" => "deleted"]);
    }
}

