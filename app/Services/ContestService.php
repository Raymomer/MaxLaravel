<?php

namespace App\Services;

use App\Contest;
use Illuminate\Http\Request;
use GuzzleHttp;


class ContestService
{

    public function listContest(Request $request)
    {
        // $getDate = $request->input('date');




        $dbData = Contest::paginate($request->input('limit'))->toArray();

        $response = [
            'current_page' => $dbData['current_page'],
            'data' => $dbData['data'],
            'per_page' => $dbData['per_page'],
            'total' => $dbData['total']
        ];

        return $response;
    }
    public function readDbContest(Request $request)
    {
        $getDate = $request->input('date');

        $defaultLimit = $request->input('limit') == null ? 15 : $request->input('limit');



        if ($request->input('team') !== null) {

            $this->getTeam = $request->input('team');
            $getTeam = $request->input('team');

            //select db with date value and team

            $dbData = Contest::query()->where('date', 'like',  $getDate  . '%')->where(
                function ($query) use ($getTeam) {

                    $query->where('away_team', 'like', '%' . $getTeam . '%')->orwhere('home_team', 'like', '%' .  $getTeam  . '%');
                }
            )->paginate($defaultLimit)->toArray();
        } else {

            //select db with date value
            $dbData = Contest::query()->whereDate('date', 'like',  $getDate  . '%')->paginate($defaultLimit)->toArray();
        }


        $response = [
            'current_page' => $dbData['current_page'],
            'data' => $dbData['data'],
            'per_page' => $dbData['per_page'],
            'total' => $dbData['total']
        ];

        return $response;
    }

    public function fetchContest($date)
    {


        $url = "https://cp.zgzcw.com/lottery/jcplayvsForJsp.action?lotteryId=26&issue=$date";
        $client = new GuzzleHttp\Client();

        $response = $client->request('get', $url, []);

        $str = (string) $response->getBody()->getContents();
        $need =  $this->sub_table($str);

        $re = "/<tr ?.*[\n \w\W]+?<\/tr>/";
        preg_match_all($re, $need, $tr);

        $contestDetial = [];

        for ($i = 0; $i < count($tr[0]); $i++) {
            $data = $this->catchDetial($tr[0][$i], $date);

            array_push($contestDetial, $data);
        }

        if (count($contestDetial) > 0) {

            Contest::query()->insert($contestDetial);
            return ("?????????" . count($tr[0]) . "?????????");
        }
        return ("Not found any data");
    }
    private function sub_table($str)
    {
        $start = '<div class="lqsf-body" id="dcc">';
        $end = '<div class="footer-fix" id="ggArea">';

        $s = strpos($str, $start) + strlen($start);
        $e = strpos($str, $end);

        return substr($str, $s, $e - $s);
    }

    private function catchDetial($element, $date)
    {

        // ????????????
        $re = '/<\/code><i>(\d+)/';
        preg_match($re, $element, $number);


        // ????????????
        $re = '/<span class="g_qt">(.*?)<\/span>/';
        preg_match($re, $element, $competition);

        //????????????
        $re = '/<td ?class="wh-4 t-r"[\w\W]+?>(.+)<\/a>/';
        preg_match($re, $element, $turn);
        $re = '/<a ?.+?>(.+?)<\/a>/';
        preg_match($re, $turn[0], $awayTeam);
        $awayTeam[0] = strip_tags($awayTeam[0], '<br>');

        //????????????
        $re = '/<td ?class="wh-6 t-l"[\w\W]+?>(.+?)<\/a>/';
        preg_match($re, $element, $turn);
        $re = '/<a ?.+?>(.+?)<\/a>/';
        preg_match($re, $turn[0], $homeTeam);
        $homeTeam[0] = strip_tags($homeTeam[0], '<br>');

        //??????????????????
        $re = '/<td ?class="wh-5 bf">[\w\W]+?(\d+:\d+)/';
        preg_match($re, $element, $time);
        $time[0] = preg_replace('/[\n\s\t]/', "", $time[0]);

        // print_r($time[0] . PHP_EOL);

        // ????????????
        $re = '/<td ?class="wh-7 b-l">[\w\W]+?(\d+\.\d+)[\w\W]+?(\d+\.\d+)<\/a>/';
        preg_match($re, $element, $count);


        return [
            'no' => $number[1],
            'date' => $date,
            'type' => $competition[1],
            'time' => $time[1],
            'away_team' => $awayTeam[1],
            'home_team' => $homeTeam[1],
            'lose' => $count[1],
            'win' => $count[2],

        ];
    }
}
