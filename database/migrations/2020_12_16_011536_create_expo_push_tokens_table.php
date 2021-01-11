<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateExpoPushTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expo_push_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('last_used_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('expired_at')->nullable();
//            Uncomment the next line, and comment out the following line if you use UUIDs as your primary key
//            $table->string('notifiable_id');
            $table->foreignId('notifiable_id');
            $table->string('notifiable_type');
            $table->unique(['token']);
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
