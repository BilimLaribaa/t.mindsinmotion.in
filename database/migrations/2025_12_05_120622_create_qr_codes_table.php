<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('qrs', function (Blueprint $table) {
            $table->id();
            $table->text('data');
            $table->integer('size');
            $table->integer('margin');
            $table->string('type');
            $table->string('file');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qrs');
    }
};
