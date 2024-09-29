<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlacklistedTokensTable extends Migration
{
    public function up()
    {
        Schema::create('blacklisted_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 512)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blacklisted_tokens');
    }
}


