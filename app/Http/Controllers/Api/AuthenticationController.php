<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthenticationController extends Controller
{
    function user_tags($id){
        $t = Tag::where('user_id',$id)->get();
        return response()->json(["tags" => $t]);
    }

    function facebook_login(Request $request){

        $user = $this->loginOrCreateUser($request);
        return response()->json([
           "login" => "successful",
           "user" => $user
        ]);
    }
    

    function login(Request $request){

        $email = $request->input("email");
        $password = $request->input("password");
        $a = User::where("email",$email)->first();
        if($a != null){
            if(password_verify($password,$a->password)){
                return response()->json([
                    "login" => true,
                    "user" => $a
                ]);
            }
        }

        return response()->json(["login" =>  false,
            "user" => null
        ]);
    }

    public function register(Request $request){

        $data = $request->all();
        $temp = User::where("email",$data["email"])->get();
        if(count($temp)) {
            return response()->json([
                "error" => ["if" => true,"status" => "Email belong to anyone else"]
            ]);
        }

        if($this->create($data)){
            $email=$data["email"];
            $a = User::where("email",$email)->first();
            return response()->json(["error" => ["if" => false],"status" => "Created","user" => $a]);
        }else{
            return response()->json(["error" => ["if" => true,"status" => "Something went Wrong"]]);

        }
    }
    protected function loginOrCreateUser($user){


        if ($authUser = User::where("email", $user->email)->first()) {
            return $authUser;
        }
        return User::create([
            "name" => $user->name,
            "email" => $user->email,
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "phone" => $data["phone"],
            "password" => bcrypt($data["password"]),
        ]);
    }

    function forgot(Request $request){
        $u = User::where("email", $request->email)->first();
        if ($u == null) {
            // AW: Response code 404
            return response()->json(['error' => true, 'status' => "Email not Found"]);
        }
        PasswordReset::where("email",$request->email)->delete();
        $token = random_int(111111,999999);
        PasswordReset::create([
            "email" => $request->email,
            "token" => $token,
        ]);
        $e = $request->email;
        Mail::send('emails.forgot', ['token' => $token], function ($m) use ($e) {
            $m->from('bahtasham@gmail.com', 'Virtual Bot');
            $m->to($e)->subject('Forgot Password ! ');
        });
        return response()->json(['error' => $e]);
    }

    function auth_token(Request $request){
        $p = PasswordReset::where("email",$request->email)->where("token",$request->token)->first();
        if($p == null){
            return response()->json(['error' => true]);
        }
        return response()->json(['error' => false]);
    }

    function reset_password(Request $request)
    {
        $data = $request->all();
        $user = User::where("email",$data['email'])->first();
        $user->password = bcrypt($data['password']);
        $user->save();
        
        // AW:  Code ?????
        
        return response()->json([
            'error' => false,
            'status' => 'Password Chagned',
        ]);
    }

}












