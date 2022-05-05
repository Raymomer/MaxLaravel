<?php


namespace App\Services;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;



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
            return  ['staus' => false, 'message' => "User have not login or no account."];
        }
    }

    public function Create(Request $request)
    {
        //check User Exist and reutnr profile
        $check = $this->userProfileExist('user_account', $request->input('account'));

        // no User in db
        if (count($check) == 0) {

            try {
                $user = new User;
                $user->user_account = $request->input('account');
                $user->user_password = $request->input('password');
                $user->user_mail = $request->input('mail');

                $user->save();

                return ['status' => true];
            } catch (\Exception $e) {
                return ['status' => false, 'message'  => "User's mail Exist"];
            }
        } else {
            return ['status' => false, 'message'  => "User's account Exist"];
        }
    }


    public function Update(Request $request)
    {

        $payload = [];

        foreach ($request->all() as $key => $value) {
            switch ($key) {
                case 'password':
                    $payload['user_password'] = $value;
                    break;
                case 'mail':
                    $payload['user_mail'] = $value;
                    break;
                case 'expiry':
                    $payload['expiry'] = $value;
                    break;
            };
        };

        // Get new token
        $payload['token'] = Str::uuid()->toString();
        $user = new User;

        // Check post parameter exist
        if (count($payload) > 0) {
            try {
                $user->where('token', $request->input('token'))->update($payload);
            } catch (QueryException $e) {
                return ['status' => false, 'message'  => "Update error"];
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

    public static  function error($code = 400, $message = 'error_occured')
    {

        $data = array(
            'status' => false,
            'code' => $code,
            'message' => $message
        );

        return [$data];
    }
}
