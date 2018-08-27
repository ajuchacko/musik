<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_track', function (Blueprint $table) {
            $table->unsignedInteger('playlist_id')->index();
            $table->foreign('playlist_id')->references('id')->on('playlists')->onDelete('cascade');
            $table->unsignedInteger('track_id')->index();
            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('cascade');

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
        Schema::dropIfExists('playlist_track');
    }
}
