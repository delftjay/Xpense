<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Authentication;


class CreateAuthenticationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authentications', function (Blueprint $table) {
            $table->comment = '身份验证信息';
			$table->increments('id');
			$table->enum('type', [
				Authentication::TYPE_IDENTITY_CARD,
				Authentication::TYPE_PASSPORT,
				Authentication::TYPE_DRIVER_LICENSE
			])->comment('验证类型');
			$table->string('realname', 100)->default('')->comment('真实姓名');
			$table->string('id_number', 100)->default('')->comment('身份证号码');
			$table->text('extra')->nullable()->comment('拓展信息');
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
        Schema::dropIfExists('authentications');
    }
}
