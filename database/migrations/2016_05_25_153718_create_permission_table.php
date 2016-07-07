<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Model\Permission;
use App\Model\Role;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->integer('create_by')->unsigned()->nullable();
            $table->integer('update_by')->unsigned()->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('role_id')->references('id')->on('role');
            $table->foreign('employee_id')->references('id')->on('employee');
            $table->foreign('create_by')->references('id')->on('employee');
            $table->foreign('update_by')->references('id')->on('employee');
        });

        $roles = Role::whereIn('name', Role::developer())->get();
        foreach($roles as $index => $role)
        {
            Permission::create([
                'role_id'     => $role->id,
                'employee_id' => 1
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
        Schema::table('permission', function(Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['create_by']);
            $table->dropForeign(['update_by']);
        });

        Schema::drop('permission');
    }
}
