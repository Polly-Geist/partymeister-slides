<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlideIdToPlaylistItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlist_items', function (Blueprint $table) {
            $table->integer('slide_id')->after('slide_type')->unsigned()->nullable();

            $table->foreign('slide_id')->references('id')->on('slides')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playlist_items', function (Blueprint $table) {
            $table->dropColumn('slide_id');
        });
    }
}
