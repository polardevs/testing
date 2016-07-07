<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Model\Position;

class CreatePositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_id')->unsigned()->nullable();
            $table->integer('create_by')->unsigned()->nullable();
            $table->integer('update_by')->unsigned()->nullable();

            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('department_id')->references('id')->on('department');
            // $table->foreign('create_by')->references('id')->on('employee');
            // $table->foreign('update_by')->references('id')->on('employee');
        });

        foreach(['system admin'] as $name)
        {
            Position::create([
                'name'          => $name,
                'department_id' => 1,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('position', function(Blueprint $table) {
            // $table->dropForeign(['department_id']);
            // $table->dropForeign(['create_by']);
            // $table->dropForeign(['update_by']);
        // });

        Schema::drop('position');
    }
}
