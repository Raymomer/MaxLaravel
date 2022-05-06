<?php

namespace App\Services;

use App\Contest;
use Illuminate\Http\Request;



class DatabaseService
{
    public function DbRead(Request $request)
    {
        $getDate = $request->input('date');


        if ($request->input('team') !== null) {

            $this->getTeam = $request->input('team');
            $getTeam = $request->input('team');

            //select db with date value and team

            $dbData = Contest::query()->where('date', 'like',  $getDate  . '%')->where(
                function ($query) use ($getTeam) {

                    $query->where('away_team', 'like', '%' . $getTeam . '%')->orwhere('home_team', 'like', '%' .  $getTeam  . '%');
                }
            )->get()->toArray();
        } else {
            //select db with date value

            $dbData = Contest::query()->whereDate('date', 'like',  $getDate  . '%')->get()->toArray();
        }



        // header X-Requested-With -> XMLHttpRequest
        return $dbData;
    }

    public function Fetch($date)
    {


        $url = "https://cp.zgzcw.com/lottery/jcplayvsForJsp.action?lotteryId=26&issue=$date";


        $str = file_get_contents($url);
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
            return ("新增了" . count($tr[0]) . "筆資料");
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

        // 取得編號
        $re = '/<\/code><i>(\d+)/';
        preg_match($re, $element, $number);


        // 取得賽事
        $re = '/<span class="g_qt">(.*?)<\/span>/';
        preg_match($re, $element, $competition);

        //取得客隊
        $re = '/<td ?class="wh-4 t-r"[\w\W]+?>(.+)<\/a>/';
        preg_match($re, $element, $turn);
        $re = '/<a ?.+?>(.+?)<\/a>/';
        preg_match($re, $turn[0], $awayTeam);
        $awayTeam[0] = strip_tags($awayTeam[0], '<br>');

        //取得主隊
        $re = '/<td ?class="wh-6 t-l"[\w\W]+?>(.+?)<\/a>/';
        preg_match($re, $element, $turn);
        $re = '/<a ?.+?>(.+?)<\/a>/';
        preg_match($re, $turn[0], $homeTeam);
        $homeTeam[0] = strip_tags($homeTeam[0], '<br>');

        //取得比賽時間
        $re = '/<td ?class="wh-5 bf">[\w\W]+?(\d+:\d+)/';
        preg_match($re, $element, $time);
        $time[0] = preg_replace('/[\n\s\t]/', "", $time[0]);

        // print_r($time[0] . PHP_EOL);

        // 取得積分
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
