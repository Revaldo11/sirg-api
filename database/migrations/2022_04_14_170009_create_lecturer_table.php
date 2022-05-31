<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nip');
            $table->string('name');
            $table->bigInteger('phone');
            $table->integer('year_lecturer');
            $table->string('community_service');
            $table->string('achievement_lecturer');
            $table->string('password');
            $table->string('path_photo')->nullable();
            $table->foreignId('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('lecturer');
    }
}
