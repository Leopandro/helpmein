<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_task', function (Blueprint $table) {
            $table->foreignId('answer_id')->nullable()->references('id')->on('user_answer')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_task', function (Blueprint $table) {
            $table->dropForeign('user_task_answer_id_foreign');
            $table->dropColumn('answer_id');
        });
    }
};
