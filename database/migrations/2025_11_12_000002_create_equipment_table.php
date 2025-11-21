<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category', 100)->nullable();
            $table->integer('total_quantity')->unsigned()->default(1);
            $table->integer('available_quantity')->unsigned()->default(1);
            $table->string('condition', 100)->nullable();
            $table->string('asset_tag', 100)->unique()->nullable();
            $table->timestamps();
            
            $table->index('category');
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipment');
    }
};