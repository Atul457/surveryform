<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_form_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_ref');
            $table->unsignedBigInteger('survey_form_ref');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('user_ref')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('survey_form_ref')->references('id')->on('survey_forms')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_form_links');
    }
};
