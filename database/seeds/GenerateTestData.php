<?php

use Illuminate\Database\Seeder;
use App\Model\Department;
use App\Model\Role;
use App\Model\Employee;
use App\Model\Permission;
use App\Model\Position;

class GenerateTestData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = Faker\Factory::create();

      // Role
      foreach(['adminHR', 'manager', 'employee', 'general'] as $role)
      {
          Role::create([
              'name'   => $role,
              'active' => true,
          ]);
      }

      // Department
      foreach(['Human Resource', 'Manager', 'Employee General'] as $name)
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
          if($department->name == 'Human Resource')
          {
            if(rand(0, 1))
            {
              Permission::create([
                'role_id'     => Role::where('name', 'adminHR')->first()->id,
                'employee_id' => $employee->id
              ]);
            }
          }
          elseif($department->name == 'Manager')
          {
            if(rand(0, 1))
            {
              Permission::create([
                'role_id'     => Role::where('name', 'manager')->first()->id,
                'employee_id' => $employee->id
              ]);
            }
          }
          elseif($department->name == 'Employee General')
          {
            if(rand(0, 1))
            {
              Permission::create([
                'role_id'     => Role::where('name', 'employee')->first()->id,
                'employee_id' => $employee->id
              ]);
            }
          }
        }
      }

    }
}
