<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\User;


class  UserController extends Controller
{
    use \App\Http\Traits\UsesUuid;

    protected $userTest;

    public function __construct(UserService $userService)
    {

        $this->userTest = $userService;
    }



    public function UserCreate(Request $request)
    {


        // account, password required
        $request->validateWithBag('post', [
            'account' => ['required', 'max:20'],
            'password' => ['required', 'max:20'],
            'mail' => ['required']
        ]);


        $result = $this->userTest->Create($request);
        return response($result);
    }


    public function UserUpdate(Request $request)
    {

        $request->validateWithBag('post', [
            'token' => ['required'],
            'password',
            'mail',
            'expiry',
        ]);


        $result =  $this->userTest->Update($request);
        return response($result);
    }

    public function UserLogin(Request $request)
    {

        $request->validateWithBag('post', [
            'account' => ['required', 'max:20'],
            'password' => ['required', 'max:20']
        ]);

        $result = $this->userTest->Login($request);
        return response($result);
    }

    public function UserProfile(Request $request)
    {

        request()->validate([
            'token'
        ]);

        $result = $this->userTest->Profile($request);
        return response($result);
    }


    public function UserLogout(Request $request)
    {

        request()->validate([
            'account',
            'token'

        ]);

        $result = $this->userTest->Logout($request);
        return response($result);
    }
}
