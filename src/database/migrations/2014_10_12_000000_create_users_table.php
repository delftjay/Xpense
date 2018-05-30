<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $this->comment = '用户';
            $table->increments('id')->comment('编号');
            $table->string('name', 100)->unique()->comment('用户名');
            $table->string('mobile', 100)->unique()->comment('手机');
            $table->string('email', 100)->default('')->comment('邮箱');            
            $table->string('password');
            $table->unsignedInteger('authentication_id')->default(0)->comment('身份验证');
            $table->boolean('email_verified')->default(false)->comment('邮件验证');
            $table->boolean('mobile_verified')->default(false)->comment('手机验证');
            $table->string('google2fa_secret', 16)->default('')->comment('谷歌二次验证');
            $table->text('invite_code', 100)->nullable()->comment('邀请码');
            $table->text('avatar')->nullable()->comment('头像');
            $table->text('introduction')->nullable()->comment('简介');
            $table->rememberToken()->comment('记住密码的Token');
            $table->string('register_ips')->default('')->comment('注册IP');
            $table->timestamps();                     
        });

        // 打乱初始ID。
        DB::unprepared('ALTER TABLE `users` AUTO_INCREMENT = ' . mt_rand(100000, 999999) . ';');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
