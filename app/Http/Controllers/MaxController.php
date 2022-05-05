<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\DatabaseService;


class MaxController extends Controller
{

    protected $dbRead;


    public function __construct(DatabaseService $dbRead)
    {
        $this->dbRead = $dbRead;
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
            'token' => ['required'],
        ]);

        $result = $this->dbRead->DbRead($request);

        return response($result);
    }


    // Catch data from  https://cp.zgzcw.com/lottery/jcplayvsForJsp.action?lotteryId=26&issue=   
    public function FetchContest(Request $request = null, $date = null)
    {

        $result = $this->dbRead->Fetch($request, $date);

        return $result;
    }
}
