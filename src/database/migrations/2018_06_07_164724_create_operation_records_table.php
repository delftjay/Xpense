<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_records', function (Blueprint $table) {
            $table->comment = '操作记录';

            $table->increments('id');

            // 所属用户。
            // $table->morphs('user');
            $table->unsignedInteger('user_id')->comment('所属用户ID');
            $table->string('user_type', 100)->comment('所属用户类型');
            $table->index([
                'user_id',
                'user_type'
            ]);

            $table->string('method', 8)->comment('请求方法');

            $table->string('route_name', 100)->comment('路由名');

            $table->string('route_action', 100)->comment('路由地址');

            $table->longText('input')->comment('请求参数');

            $table->text('user_agent')->comment('UserAgent');

            $table->string('ips')->comment('来源IP');

            $table->integer('status_code')->default(0)->comment('响应状态码');

            $table->longText('response')->nullable()->comment('响应内容，包含header与content');

            $table->timestamps();

            $table->index('method');
            $table->index('route_name');
            $table->index('route_action');
            $table->index('status_code');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_records');
    }
}
