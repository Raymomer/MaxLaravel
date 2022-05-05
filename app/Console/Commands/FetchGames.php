<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseService;

class FetchGames extends Command

{

    protected $fetchService;


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
    public function __construct(DatabaseService $fetchService)
    {
        parent::__construct();
        $this->fetchService = $fetchService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $date = $this->argument('arg_date');
        $result = $this->fetchService->Fetch(null, $date = $date);


        return $result;
    }
}
