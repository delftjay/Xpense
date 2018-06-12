<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->comment = '管理员特别拥有的权限';

            $table->increments('id');

            $table->unsignedBigInteger('admin_id')->comment('所属管理员');
            $table->string('permission_key', 100)->comment('特别拥有的权限');
            $table->unique([
                'admin_id',
                'permission_key'
            ]);

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
        Schema::dropIfExists('admin_permissions');
    }
}
