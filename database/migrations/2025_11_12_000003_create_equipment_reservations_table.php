<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipment_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->unsigned()->default(1);
            $table->datetime('reserved_from');
            $table->datetime('reserved_to');
            $table->datetime('borrowed_at')->nullable();
            $table->datetime('due_at')->nullable();
            $table->datetime('returned_at')->nullable();
            $table->enum('status', ['reserved', 'borrowed', 'returned', 'cancelled', 'overdue'])->default('reserved');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipment_reservations');
    }
};