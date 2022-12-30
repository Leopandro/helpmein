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
            $table->string('status')->default('assigned');
            $table->unique(['user_id','task_id']);
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
            $table->dropColumn('status');
            $table->dropIndex('user_task_user_id_task_id_unique');
        });
    }
};
