<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_id')->unsigned()->nullable();
            $table->integer('owner_id')->unsigned()->nullable();
            $table->integer('create_by')->unsigned()->nullable();
            $table->integer('update_by')->unsigned()->nullable();

            $table->string('code', 10);
            $table->text('description');
            $table->string('name');
            $table->date('start_date')->nullable();
            $table->date('success_date')->nullable();
            $table->boolean('active');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('department_id')->references('id')->on('department');
            // $table->foreign('owner_id')->references('id')->on('employee');
            // $table->foreign('create_by')->references('id')->on('employee');
            // $table->foreign('update_by')->references('id')->on('employee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('project', function(Blueprint $table) {
        //     $table->dropForeign(['department_id']);
        //     $table->dropForeign(['owner_id']);
        //     $table->dropForeign(['create_by']);
        //     $table->dropForeign(['update_by']);
        // });

        Schema::drop('project');

    }
}
