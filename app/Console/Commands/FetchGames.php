<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contest;

class FetchGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:contest-add {arg_date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Enter a date with Online-Contest and then add contest's data to DB";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $date = $this->argument('arg_date');
        $addCount = $this->fetch($date);
        $this->info("以新增了 {$addCount} 筆資料");

        return 0;
    }



    private function fetch($date = null)
    {
        if ($date != null) {

            $searchDate = $date;


            $url = "https://cp.zgzcw.com/lottery/jcplayvsForJsp.action?lotteryId=26&issue=$searchDate";

            $str = file_get_contents($url);
            $need =  $this->sub_table($str);

            $re = '/(\d{4}\-\d{2}\-\d{2})星期./u';
            preg_match($re, $need, $date);

            if (count($date) == 0) {
                echo "No data";
            }

            $re = "/<tr ?.*[\n \w\W]+?<\/tr>/";
            preg_match_all($re, $need, $tr);
            for ($i = 0; $i < count($tr[0]); $i++) {
                $err = $this->catchDetial($tr[0][$i], $date);

                if ($err != null) {
                    break;
                }
            }


            return count($tr[0]);
        } else {
            echo "Todat is bad day.";
        }
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

        Contest::query()->insert([
            'no' => $number[1],
            'date' => $date[1],
            'type' => $competition[1],
            'time' => $time[1],
            'away_team' => $awayTeam[1],
            'home_team' => $homeTeam[1],
            'lose' => $count[1],
            'win' => $count[2],

        ]);
    }
}
