<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_activity_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->integer('approve_by')->unsigned()->nullable();
            $table->integer('create_by')->unsigned()->nullable();
            $table->integer('update_by')->unsigned()->nullable();

            $table->float('work_hour')->nullable();
            $table->dateTime('work_date')->nullable();
            $table->dateTime('approve_date')->nullable();
            $table->text('remark');
            // $table->boolean('can_approve');
            // $table->boolean('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_activity_id')->references('id')->on('project_activity');
            $table->foreign('employee_id')->references('id')->on('employee');
            $table->foreign('approve_by')->references('id')->on('employee');
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
        Schema::table('timesheet', function(Blueprint $table) {
            $table->dropForeign(['project_activity_id']);
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['approve_by']);
            $table->dropForeign(['create_by']);
            $table->dropForeign(['update_by']);
        });

        Schema::drop('timesheet');
    }
}
