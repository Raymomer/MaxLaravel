<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest', function (Blueprint $table) {


            $table->integer('no');
            $table->date('date');
            $table->string('type');
            $table->string('time');
            $table->string('away_team');
            $table->string('home_team');
            $table->string('lose');
            $table->string('win');
            $table->primary(['no', 'date']);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contest');
    }
}
