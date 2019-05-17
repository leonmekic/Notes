<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteAndTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'notes',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('owner_id');
                $table->string('note');
                $table->string('description');
                $table->string('status');
                $table->timestamps();

                $table->foreign('owner_id')->references('id')->on('users');
            }
        );

        Schema::create(
            'tags',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('tag');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note');
    }
}
