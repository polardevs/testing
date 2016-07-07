<?php

Route::group(['middleware' => 'web'], function () {

    Route::auth();
    Route::resource('profile'   , 'ProfileController');
    Route::post('forgetPassword', 'ProfileController@resetPassword');

    Route::group(['middleware' => 'auth'], function () {\
      Route::resource(''          , 'HomeController');
      Route::resource('timesheet' , 'TimesheetController');
      Route::get('listTimesheet'  , 'HomeController@listTimesheet');

      Route::group(['middleware' => 'projectManager'], function () {
        Route::resource('project'               , 'ProjectController');
        Route::resource('employee'              , 'EmployeeController');
        Route::resource('project-employee'      , 'EmployeeProjectController');
        Route::resource('project-activity'      , 'ProjectActivityController');
        Route::resource('report'                , 'ReportController');
        Route::get('export_report'  , 'ReportController@toExcel');

        Route::get('approve/timesheet/{id}'                 , 'HomeController@approveTimesheet');
        Route::get('project/{projectId}/employee'           , 'ProjectController@employee');
        Route::get('project/{projectId}/activity'           , 'ProjectController@activity');
        Route::post('project/{projectId}/allEmployee'       , 'ProjectController@addAll');
        Route::post('project/{projectId}/deleteAllEmployee' , 'ProjectController@deleteAll');

        Route::group(['middleware' => 'adminHR'], function () {
          Route::resource('department' , 'DepartmentController');
          Route::resource('position'   , 'PositionController');
          Route::get('backup', 'HomeController@backupToCSV');

          Route::group(['middleware' => 'super_admin'], function () {
            Route::resource('permission' , 'PermissionController');
            Route::resource('role'       , 'RoleController');
          });

        });

      });

    });

});
