<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirdropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airdrops', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('所属用户ID');
            $table->string('token', 100)->unique()->comment('imtoken钱包地址');
            $table->string('code', 32)->unique()->comment('验证码');
            $table->string('from', 32)->nullable()->comment('邀请人的验证码');
            $table->unsignedInteger('count')->default(0)->comment('应得币数');
            $table->unsignedInteger('invite')->default(0)->comment('已邀请人数');
            $table->unsignedInteger('reg')->default(0)->comment('暂未用');
            $table->boolean('tg')->default(false)->comment('为1代表加入了电报群');
            $table->unsignedInteger('tg_id')->default(0)->comment('电报id');
            $table->string('tg_name', 100)->nullable()->comment('电报用户名');            
            $table->string('ip', 100)->comment('IP地址');
            $table->dateTime('verify_at')->nullable()->comment('电报验证日期');
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
        Schema::dropIfExists('airdrops');
    }
}
