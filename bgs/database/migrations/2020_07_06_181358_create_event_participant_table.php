<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventParticipantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_participant', function (Blueprint $table) {
            $table->foreignId('event_id')
                ->constrained('events');
            $table->foreignId('participant_id')
                ->constrained('participants');

            $table->unique('participant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_participant', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropForeign(['participant_id']);
            $table->dropIfExists();
        });
    }
}
