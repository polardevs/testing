<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Model\Role;

class CreateRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('create_by')->unsigned()->nullable();
            $table->integer('update_by')->unsigned()->nullable();

            $table->string('name', 100)->unique();
            $table->boolean('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('create_by')->references('id')->on('employee');
            $table->foreign('update_by')->references('id')->on('employee');
        });

        foreach(config('app.roles') as $role)
        {
            Role::create([
                'name'   => $role,
                'active' => true,
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
        Schema::table('role', function(Blueprint $table) {
            $table->dropForeign(['create_by']);
            $table->dropForeign(['update_by']);
        });

        Schema::drop('role');
    }
}
