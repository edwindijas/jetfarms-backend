<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;


function status ($statusCode, $message, $error, $errorCode) {

}

class Users extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    function signup (Request $request) {
        //Get content from request
        //Convert JSON to array
        $data = json_decode($request->getContent(), true);
        
        

        $user =  new User();
        $user->email = $data["email"];

        /** Let's validate email */
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            //Todo throw error invalid email
        }

        //validate name

        //validate Password

        $user->password = password_hash($data['password'], PASSWORD_BCRYPT);
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
       
        if ($user->exists()) {
            return ["status" => false, "emailExist" => true];
        }
        $user->save();
        return ["status" => true]; 
    }


    function signin(Request $request) {
        $data = json_decode($request->getContent(), true);
        $users = User::where('email', $data['email'])->get();
        //If Users count is 0: User does not exists
        if (count($users) === 0) {
            return ["error" => ["message" => "User not found"]];
        }

        $user = $users[0];
        //User aunthenticated
        if (password_verify($data['password'], $user->password)) {
            return ["status" => "true"];
        }

        //Password authentication failed
        return ["error" => ["message" => "Invallid Password"]];
    }

}
