<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{

    public function login(Request $request)
    {

        response("cookie")->cookie('token', '九天玄女');
        dd("500英尺");
    }


    public function userCreate(Request $request)
    {

        /**
         *  fix user_account primary key
         *  fix db_password have to 隱碼
         *  add upgrade models
         *  set token 
         */

        $user = new User;

        $user->user_account = 'ray';
        $user->user_password = 'aa1234';
        $user->user_id = date(time());
        // $user->update_at = date(time());


        $user->save();

        echo 'Save successfully';
    }


    public function userUpdate(Request $request)
    {

        $user = new User;
        // Contest::query()

        $user->where('user_account', 'ray')->update(['user_password' => 'aa1234']);
        echo 'Updatesuccess';
    }



    public function index(Request $request)
    {
        $response = response("cookie")->cookie('token', '九天玄女');


        dd($request->cookie('token'));
        // dd(response()->cookie('token'));
    }
}
