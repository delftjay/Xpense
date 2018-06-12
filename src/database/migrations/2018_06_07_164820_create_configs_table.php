<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Config;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->comment = '系统配置';
            $table->string('key', 100)->primary();
            $table->enum('type', [
                Config::TYPE_TEXT,
                Config::TYPE_SINGLE_OPTION,
                Config::TYPE_MULTIPLE_OPTION,
            ])->default(Config::TYPE_TEXT);
            $table->text('value');
            $table->string('name');
            $table->string('description');
            $table->string('rules');
            $table->string('weight')->default(100);
            $table->string('unit')->default('');
            $table->text('options')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
}
