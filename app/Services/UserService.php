<?php


namespace App\Services;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


use App\Exceptions\CommonException;


class UserService
{

    public function Login(Request $request)
    {


        // Check user's account exist
        $users = $this->checkUserExist($request->all());


        // Check user's password match
        if ($users != null) {

            $user = new User;

            //Create new token and update 
            $newToken =  Str::uuid()->toString();
            $update = $user->query()->where('user_account',  $request->input('account'))->update(['token' => $newToken]);

            // update token success
            if ($update) {
                return
                    [
                        'status' => true,
                        'payload' => [
                            'token' =>  $newToken
                        ]

                    ];
            }
        } else {
            return  ['staus' => false, 'message' => "No match user's profile"];
        }
    }

    public function Logout(Request $request)
    {
        $user = new User();

        $removeToken = $user->where('user_account', $request->input('account'))->orWhere('token',  $request->input('token'))->update(['token' => null]);

        if ($removeToken) {
            return ['staus' => true];
        } else {
            return  ['staus' => false, 'message' => "User not logout"];
        }
    }

    public function Profile(Request $request)
    {
        $verifyUser = false;
        $profile = [];

        // User hold token
        if ($request->input('token') != null) {
            $profile =  $this->userProfileExist('token', $request->input('token'));

            if ($profile != null) {
                $verifyUser = true;
            }
        }

        if ($verifyUser) {
            return ['status' => true, 'payload' => $profile];
        } else {
            throw new CommonException(400, "User have not login or no accountrrr.");
            // return  ['staus' => false, 'message' => "User have not login or no account."];
        }
    }

    public function Create(Request $request)
    {
        //check User Exist and reutnr profile
        $accountCheck = $this->userProfileExist('user_account', $request->input('account'));

        $mailCheck = $this->userProfileExist('user_mail', $request->input('mail'));

        if (count($accountCheck) > 0 || count($mailCheck) > 0) {
            throw new CommonException(400, 'User\'s account or mail is  Exist');
        }



        try {
            $user = new User;
            $user->user_account = $request->input('account');
            $user->user_password = $request->input('password');
            $user->user_mail = $request->input('mail');

            $user->save();

            return ['status' => true];
        } catch (\Exception $e) {
            throw new CommonException(400, $e->getMessage());
        }
    }

    public function Update(Request $request)
    {

        $payload = [];

        if ($request->password) {
            $payload['user_password'] = $request->password;
        }

        if ($request->mail) {
            $payload['user_mail'] = $request->mail;
        }

        if ($request->expiry) {
            $payload['expiry'] = $request->expiry;
        }

        if (!count($payload)) {
            return;
        }

        // Get new token
        $payload['token'] = Str::uuid()->toString();
        $user = new User;

        // Check post parameter exist
        if (count($payload) > 0) {
            try {
                $user->where('token', $request->input('token'))->update($payload);

                // dd($udpate);
            } catch (\Exception $e) {
                throw new CommonException(400, $e->getMessage());
            }


            // Update Success and return new Token
            return [
                'status' => true,
                'payload' => [
                    'token' => $payload['token']
                ]

            ];
        }
    }

    public function userProfileExist($key, $data)
    {
        $user = new User();
        $users = $user->where($key, $data)->get()->toArray();

        return  $users;
    }

    public function checkUserExist($response)
    {
        $user = new User();
        $users = $user->where('user_account', $response['account'])->where('user_password', $response['password'])->get()->toArray();

        return $users;
    }
}
