<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->call('App\Http\Controllers\XMLController_new@create_xml_transgaz',['hours_xml'=>1])->cron('1 0,2,4,6,8,10,12,14,16,18,20,22 * * *');
//        $schedule->call('App\Http\Controllers\XMLController_new@create_xml_transgaz',['hours_xml'=>24])->cron('1 12 * * *');
//        $schedule->call('App\Http\Controllers\XMLController_new@create_xml_ius',['hours_xml'=>1])->cron('1 0,2,4,6,8,10,12,14,16,18,20,22 * * *');
//        $schedule->call('App\Http\Controllers\XMLController_new@create_xml_ius',['hours_xml'=>24])->cron('1 12 * * *');
        $schedule->call('App\Http\Controllers\TestController@test',['params_type'=>1])->cron('40 0-23 * * *');
        $schedule->call('App\Http\Controllers\TestController@test',['params_type'=>5])->cron('*/5 0-23 * * *');
        $schedule->call('App\Http\Controllers\TestController@test',['params_type'=>24])->cron('0 10 * * *');
//      $schedule->call('App\Http\Controllers\TestController@check_status_gpa')->cron('1,6,11,16,21,26,31,36,41,46,51,56 0-23 * * *');
//      $schedule->call('App\Http\Controllers\TestController@check_hour_param')->cron('35 0-23 * * *');
//      $schedule->call('App\Http\Controllers\TestController@check_sut_param')->cron('30 10 * * *');
      $schedule->call('App\Http\Controllers\XMLController@create_xml',['hours_xml'=>5])->cron('1,6,11,16,21,26,31,36,41,46,51,56 0-23 * * *');
        $schedule->call('App\Http\Controllers\XMLController@create_xml',['hours_xml'=>1])->cron('1 0,2,4,6,8,10,12,14,16,18,20,22 * * *');
        $schedule->call('App\Http\Controllers\XMLController@create_xml',['hours_xml'=>24])->cron('1 12 * * *');
        $schedule->call('App\Http\Controllers\TestController@create_record_rezhim_dks')->cron('1 0,4,8,12,16,20 * * *');
        $schedule->call('App\Http\Controllers\BalansController@create_record_svodniy')->cron('1 * * * *');
        $schedule->call('App\Http\Controllers\BalansController@create_record_valoviy')->cron('59 * * * *');
        $schedule->call('App\Http\Controllers\BalansController@create_record_valoviy')->cron('59 * * * *');

//        $schedule->call('App\Http\Controllers\TestController@update_guid')->cron('* * * * *');
//        $schedule->call('App\Http\Controllers\SutJournalController@copy_record')->cron('0 8 * * *');
//        $schedule->call('App\Http\Controllers\AstraGazController@get_result_astragaz')->cron('* * * * *');
//        $schedule->call('App\Http\Controllers\AstraGazController@create_astragaz_files')->cron('0 * * * *');
//        $schedule->call('App\Http\Controllers\ToAlphaController@update_data_to_alpha')->cron('* * * * *');



    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');


        require base_path('routes/console.php');
    }
}
