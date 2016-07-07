<?php

use Illuminate\Database\Seeder;

class GenerateAlotEmployee extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Department
      foreach(['Generate Emp'] as $name)
      {
        $department = Department::create([
            'name'   => $name,
            'active' => true,
        ]);

        foreach(range(1, rand(10, 35)) as $index)
        {
          // Employee
          $employee = Employee::create([
            'username'      => $faker->companyEmail(),
            'password'      => bcrypt('qweasd'),
            'firstname'     => $faker->firstName(),
            'lastname'      => $faker->lastName(),
            'can_login'     => true,
            'active'        => true,
            'department_id' => $department->id,
            'position_id'   => Position::orderByRaw("RAND()")->first()->id
          ]);

          // Permission
          Permission::create([
            'role_id'     => Role::where('name', 'general')->first()->id,
            'employee_id' => $employee->id
          ]);
        }
      }
    }
}
