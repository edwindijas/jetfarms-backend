<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


function status ($statusCode, $message, $error, $errorCode) {

}

class Users extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function emailExists($email) {
        $user = User::where('email', $email)->get();
        if (count($user) === 0) {
            return false;
        }
        return true;
    }

    function signup (Request $request) {
        //Get content from request
        //Convert JSON to array
        $data = json_decode($request->getContent(), true);
        
        

        $user =  new User();
        $user->email = $data['email'];

        /** Let's validate email */
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            //Todo throw error invalid email
        }

        //validate name

        //validate Password

        $user->password = password_hash($data['password'], PASSWORD_BCRYPT);
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
       
        if ($this->emailExists($user->email)) {
            return response()->json(
                ['status' => false, 
                'errors' => [
                    'userMessage' => 'Failed to create an account. The form contains one or more errors.',
                    'email' => ["message" => 'Email is registered to another account.']
                ]
            ], 400);
        }

        $user->save();
        Auth::login($user, true);
        return ['status' => true,
            'user' => $user
        ]; 
    }


    function signin(Request $request) {
        $data = json_decode($request->getContent(), true);
        $users = User::where('email', $data['email'])->get();
        //If Users count is 0: User does not exists
        if (count($users) === 0 || !password_verify($data['password'], $users[0]->password) ) {
            return response()->json([
                'message' => 'Failed to login, invalid email or password',
                'status' => false,
                'errors' => [
                    'userMessage' => 'Failed to login, invalid email or password',
                ]
                ], 403);
        }

        Auth::login($users[0], true);
        
        return response()->json([
            'status' => true,
            'user' => Auth::user(),
        ], 200);

        //Password authentication failed
        
    }

    function getUser(Request $request) {
        return response()->json([
            'status' => true,
            'user' => Auth::user(),
        ], 200);
    }

    function signout(Request $request) {
        Auth::logout();
        return response()->json([
            'status' => true
        ], 200);
    }

    function recoveryInfo() {
        $user = Auth::user();
        if ($user === null) {
            return $user;
        }

        if (empty($user->verification_token)) {
            $user->verification_token = $this->generateNumericalToken();
            $user->verification_token_timestamp = now();
            $user->save();
        }

        return response()->json(
            [
                "email" => $user->email,
                "status" => true,
                "token" => $user->verification_token
            ]
        );
        
    }

    function recoveryVerify(Request $request) {
        $user = Auth::user();
        $data = json_decode($request->getContent(), true);
        $token = $data['code'];

        if ($token === $user->verification_token) {
            $user->email_verified_at = now();
            $user->save();
            return response()->json(
                [
                    "status" => true,
                    "user" => $user
                ]
            );
        }


        return response()->json(
            [
                "check" => $token === $user->verification_token,
                "token" => $user->verification_token,
                "status" => false,
                "message" => 'Code mismatch'
            ], 403
        );
        
    }


    function generateNumericalToken ($prefix = '') {
        $token = $prefix . (rand(0, 99999));
        if (strlen($token) > 5) {
            return substr($token, 0, 5);
        }
        if (strlen($token) < 5) {
            return generateNumericalToken($token);
        }
        return $token;
    }


    function changePassword (Request $request) {
        $user = Auth::user();
        $data = json_decode($request->getContent(), true);
        $oldPassword = $data['oldPassword'];
        $newPassword = $data['password'];
        $confirmPassword = $data['confirm'];

        if ($newPassword !== $confirmPassword) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to change password',
                'errors' => [
                    'confirm' => 'Couldn\' confirm password'
                ]
                ], 403);
        }

        if (!password_verify($oldPassword, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to change password',
                'errors' => [
                    'oldPassword' => ['message' =>'Password is incorrect']
                ]
            ], 403);
        }

        if ($newPassword === $oldPassword) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to change password',
                'errors' => [
                    'password' => ['message' => 'Cannot use old password as a new password']
                ]
                ], 403);
        }


        $user->password = password_hash($newPassword, PASSWORD_DEFAULT);


        $user->save();

        return response()->json([
            'status' => true
        ]);

    }

}
