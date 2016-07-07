<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Model\Employee;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 10)->unique()->comment('e.g. emp00001');
            $table->integer('department_id')->unsigned();
            $table->integer('position_id')->unsigned();
            $table->integer('create_by')->unsigned()->nullable();
            $table->integer('update_by')->unsigned()->nullable();

            $table->string('username', 100)->unique();
            $table->string('password');
            $table->string('firstname', 100);
            $table->string('lastname', 100)->nullable();
            $table->boolean('can_login');
            $table->boolean('active');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('department_id')->references('id')->on('department');
            $table->foreign('position_id')->references('id')->on('position');
            $table->foreign('create_by')->references('id')->on('employee');
            $table->foreign('update_by')->references('id')->on('employee');
        });

        $faker = Faker\Factory::create();
        foreach(['admin'] as $index => $username)
        {
            // foreach(range(1, 2) as $index)
            // {
                Employee::create([
                    'username'      => $username . '@gmt.com',
                    'password'      => bcrypt('qweasd'),
                    'firstname'     => $faker->firstName(),
                    'lastname'      => $faker->lastName(),
                    'can_login'     => true,
                    'active'        => true,
                    'department_id' => 1,
                    'position_id'   => 1
                ]);
            // }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee', function(Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['position_id']);
            $table->dropForeign(['create_by']);
            $table->dropForeign(['update_by']);
        });

        Schema::drop('employee');
    }
}
