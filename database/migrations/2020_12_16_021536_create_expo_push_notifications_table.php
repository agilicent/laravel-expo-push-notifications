<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateExpoPushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expo_push_notifications', function (Blueprint $table) {
            $table->id();
            $table->json('notification');
            $table->string('ticket')->nullable();
//            Uncomment the next line, and comment out the following line if you use UUIDs as your primary key
//            $table->string('notifiable_id');
            $table->foreignId('notifiable_id');
            $table->string('notifiable_type');
            $table->string('token');
            $table->enum('status', ['error','ok','unknown'])->default('unknown');
            $table->string('error')->nullable();
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
        Schema::dropIfExists('expo_push_tokens');
    }
}
