<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\User;


class  UserController extends Controller
{
    use \App\Http\Traits\UsesUuid;


    public function login(Request $request)
    {
        return view('login');
    }



    public function userCreate(Request $request)
    {


        // account, password required
        $request->validateWithBag('post', [
            'account' => ['required', 'max:20'],
            'password' => ['required', 'max:20'],
            'mail' => ['required']
        ]);



        //check User Exist and reutnr profile
        $check = $this->userProfileExist('user_account', $request->input('account'));

        // no User in db
        if (count($check) == 0) {

            // create token
            // $uuid = Str::uuid()->toString();

            $user = new User;
            $user->user_account = $request->input('account');
            $user->user_password = $request->input('password');
            $user->user_mail = $request->input('mail');
            // $user->token = $uuid;

            $user->save();

            return response()->json(['status' => true]);
        } else {
            return ($this->error(400, $message = "User's account Exist"));
        }
    }


    public function userUpdate(Request $request)
    {

        $request->validateWithBag('post', [
            'token' => ['required'],
            'password',
            'mail',
            'expiry',
        ]);


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

                //update token
                $update = $user->where('token', $request->input('token'))->update($payload);

                // update token failed
                // if ($update == false) {
                //     return $this->error(400, $message = 'Token not found');
                // }
            } catch (QueryException $e) {
                return $this->error(400, $message = 'Update error');
            }


            // Update Success and return new Token
            return response()->json([
                'status' => true,
                'payload' => [
                    'token' => $payload['token']
                ]

            ], 200);
        }
    }

    public function userLogin(Request $request)
    {


        /**
         * parames:{
         *      account:"",
         *      password:""
         * }
         */
        $request->validateWithBag('post', [
            'account' => ['required', 'max:20'],
            'password' => ['required', 'max:20']
        ]);


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
                return response()->json(
                    [
                        'status' => true,
                        'payload' => [
                            'token' =>  $newToken
                        ]

                    ],
                    200
                );
            }
        } else {
            return $this->error(400, $message = "No match user's profile");
        }
    }

    public function userProfile(Request $request)
    {

        request()->validate([
            'token'

        ]);

        // user;s verify check
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
            return response()->json(['status' => true, 'payload' => $profile], 200);
        } else {
            return $this->error(400, "User have not login or no account.");
        }
    }


    public function userLogout(Request $request)
    {

        request()->validate([
            'account',
            'token'

        ]);

        $user = new User();

        $removeToken = $user->where('user_account', $request->input('account'))->orWhere('token',  $request->input('token'))->update(['token' => null]);

        if ($removeToken) {
            return response()->json(['staus' => true], 200);
        } else {
            return $this->error(400, "User not logout");
        }
        // dd($removeToken);
    }


    // return User profile
    // @@ must input account : password 
    private function checkUserExist($res)
    {
        $user = new User();
        $users = $user->where('user_account', $res['account'])->where('user_password', $res['password'])->get()->toArray();

        return $users;
    }

    public static  function error($code = 400, $message = 'error_occured')
    {

        $data = array(
            'status' => false,
            'code' => $code,
            'message' => $message
        );

        return response()->json($data, $code);
    }


    public static function userProfileExist($key, $data)
    {


        $user = new User();
        $users = $user->where($key, $data)->get()->toArray();

        return  $users;
    }
}
