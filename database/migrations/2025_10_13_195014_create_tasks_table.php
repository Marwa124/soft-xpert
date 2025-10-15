<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->tinyInteger('status')->default(0);
            $table->date('due_date');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('SET NULL');

            $table->unsignedBigInteger('assignee_id')->index()->nullable();
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('SET NULL');

            $table->unsignedBigInteger('related_task_id')->nullable();
            $table->foreign('related_task_id')->references('id')->on('tasks')->onDelete('SET NULL');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
