<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\User;


class UserController extends Controller
{
    use \App\Http\Traits\UsesUuid;

    public function login(Request $request)
    {
        // response("cookie")->cookie('token', '九天玄女');
        // dd("500英尺");
    }


    public function userCreate(Request $request)
    {

        /**
         *  fix user_account primary key
         *  fix db_password have to 隱碼
         *  add upgrade models
         *  set token 
         */
        $uuid = Str::uuid()->toString();

        $user = new User;

        $user->user_account = 'ray';
        $user->user_password = 'aa1234';
        $user->token = $uuid;

        $user->save();

        echo 'Save successfully';
    }


    public function userUpdate(Request $request)
    {

        $user = new User;
        // Contest::query()

        $user->where('user_account', 'ray')->update(['user_password' => '123123']);
        echo 'Updatesuccess';
    }

    public function userLogin(Request $request)
    {
        $user = new User;
        // Contest::query()

        dd($user->where('user_account', 'ray')->where('user_password', '123123')->get()->toArray());
    }

}
