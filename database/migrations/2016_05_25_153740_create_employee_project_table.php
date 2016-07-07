<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_project', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->integer('create_by')->unsigned()->nullable();
            $table->integer('update_by')->unsigned()->nullable();
            $table->boolean('can_approve')->nullable();
            $table->boolean('active')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('project');
            $table->foreign('employee_id')->references('id')->on('employee');
            $table->foreign('create_by')->references('id')->on('employee');
            $table->foreign('update_by')->references('id')->on('employee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_project', function(Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['create_by']);
            $table->dropForeign(['update_by']);
        });

        Schema::drop('employee_project');
    }
}
