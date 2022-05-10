<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\ContestService;


class MaxController extends Controller
{

    protected $contestSerive;


    public function __construct(ContestService $contestSerive)
    {
        $this->contestSerive = $contestSerive;
    }

    public function view(Request $request)
    {

        return view('about');
    }

    public function dbRead(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'team' => 'nullable|string',
            'limit',
            'token' => ['required'],
        ]);

        $result = $this->contestSerive->readDbContest($request);

        return response($result);
    }

    public function dbList(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'limit' => 'required',
        ]);

        $result = $this->contestSerive->listContest($request);

        return response($result);
    }


    public function FetchContest(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
        ]);


        $result = $this->contestSerive->fetchContest($request->date);

        return $result;
    }
}
