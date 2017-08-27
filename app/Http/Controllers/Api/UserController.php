<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_device_id($id,Request $request)
    {
        $u = User::find($id);
        $u->device_id = $request->device_id;
        if($u->save())
            return response()->json(['device_id' => true]);
        return response()->json(['device_id' => false]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::findOrFail($id);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $temp=User::where("id",$id)->first();
        // dd($request->phone);
        if($request->phone!=null){
            // dd($temp->phone);
            $temp->phone=$request->phone;
            // dd($temp->phone);
        }
        if($request->name!=null)
            $temp->name=$request->name;
        // dd($request->phone);
        // $temp->phone=$request->phone;
        // dd($request->all());
        if($temp->save())
            return response()->json(['message' => true]);
        
        else return response()->json(['message' => false]);
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
        //
    }
    
    function add_image($mid,Request $request){
        
        // dd($request->hasFile('image'));
        $temp_name;
        if($request->hasFile('image')){
            $id = str_random(15);
            $file_name = $id . '.' . $request->file('image')->getClientOriginalExtension();
            Storage::disk('local')->put(
                $file_name,
                File::get($request->file('image'))
            );
            $m = User::where("id",$mid)->first();
            $m->image_src=$file_name;
            $temp_name=$file_name;
            $m->save();
        }else return response()->json(["message" => "false"]); 
        
        return response()->json(["message" => $temp_name]);

    }
}
