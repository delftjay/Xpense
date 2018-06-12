<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_logs', function (Blueprint $table) {
            $table->comment = '登陆日志';
            $table->increments('id');            
            $table->unsignedInteger('user_id')->comment('所属用户ID');
            $table->string('user_type', 100)->comment('所属用户类型');
            $table->index([
                'user_id',
                'user_type',
            ]);
            $table->text('user_agent')->comment('UserAgent');
            $table->string('ips')->comment('来源IP');
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
        Schema::dropIfExists('user_login_logs');
    }
}
