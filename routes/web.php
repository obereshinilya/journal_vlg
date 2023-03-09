<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

Route::get('/check_new_dz', [Controllers\ReportController::class, 'check_new_dz'])->name('check_new_dz');
Route::get('/check_error_xml', [Controllers\XMLController::class, 'check_error_xml'])->name('check_error_xml');

Route::get('/hand_for_masdu/{hours_xml}', [Controllers\XMLController::class, 'hand_for_masdu'])->name('hand_for_masdu');
Route::get('/save_fact_month/{year}/{month}/{obj}/{val}', [Controllers\ReportController::class, 'save_fact_month'])->name('save_fact_month');
Route::get('/create_month_xml', [Controllers\ReportController::class, 'create_month_xml'])->name('create_month_xml');

Route::get('/update_data_to_alpha', [Controllers\ToAlphaController::class, 'update_data_to_alpha'])->name('update_data_to_alpha');

Route::get('/report_param_dks', [Controllers\ReportController::class, 'report_param_dks'])->name('report_param_dks');
Route::get('/get_param_dks/{date}', [Controllers\ReportController::class, 'get_param_dks'])->name('get_param_dks');
Route::get('/print_param_dks/{date}', [Controllers\ReportController::class, 'print_param_dks'])->name('print_param_dks');  //Отправляем на печать

Route::get('/report_skv', [Controllers\ReportController::class, 'report_skv'])->name('report_skv');
Route::get('/get_skv/{date}', [Controllers\ReportController::class, 'get_skv'])->name('get_skv');
Route::get('/print_skv/{date}', [Controllers\ReportController::class, 'print_skv'])->name('print_skv');  //Отправляем на печать



    //Для разграничения уровней ГП и АДМ
    Route::get('/get_level', [Controllers\MenuController::class, 'get_level'])->name('get_level');
	Route::get('/create_xml_hand/{hours_xml}', [Controllers\XMLController::class, 'create_xml_hand'])->name('create_xml_hand');
        Route::get('/check_status_gpa', [Controllers\TestController::class, 'check_status_gpa'])->name('check_status_gpa');
        Route::get('/check_hour_param', [Controllers\TestController::class, 'check_hour_param'])->name('check_hour_param');
        Route::get('/check_sut_param', [Controllers\TestController::class, 'check_sut_param'])->name('check_sut_param');
        Route::get('/get_rezhim_table', [Controllers\BalansController::class, 'get_rezhim_table'])->name('get_rezhim_table');

//Главная
    Route::get('/', [Controllers\MenuController::class, 'index_hour'])->name('gazprom');
    Route::get('/print_hour/{date}/{parent}/{search}', [Controllers\HoursController::class, 'print_hour']);
    Route::post('/print_hour_area/{date}', [Controllers\HoursController::class, 'print_hour_area']);
    Route::get('/excel_hour/{date}/{parent}/{search}', [Controllers\ExcelController::class, 'excel_hour']);
    Route::post('/excel_hour_area/{date}', [Controllers\ExcelController::class, 'excel_hour_area']);
    Route::get('/sut', [Controllers\MenuController::class, 'index_sut']);
    Route::get('/print_sut/{date}/{parent}/{search}', [Controllers\SutController::class, 'print_sut']);
    Route::post('/print_sut_area/{date}', [Controllers\SutController::class, 'print_sut_area']);
    Route::get('/excel_sut/{date}/{parent}/{search}', [Controllers\ExcelController::class, 'excel_sut']);
    Route::post('/excel_sut_area/{date}', [Controllers\ExcelController::class, 'excel_sut_area']);
    Route::get('/minutes', [Controllers\MenuController::class, 'index_minut']);
    Route::get('/hours_param_minutes/{date}/{hour}', [Controllers\HoursController::class, 'get_min_param']);
    //Подтверждение достоверности
    Route::get('/accept_time_param/{type}/{hour_day}/{date_month}', [Controllers\MinutesController::class, 'accept_time_param']);
    Route::get('/get_accept/{type}/{date_month}', [Controllers\MinutesController::class, 'get_accept']);


    //Журнал действий оператора
    Route::get('/open_user_log', [Controllers\MenuController::class, 'open_user_log']);
    Route::get('/get_user_log', [Controllers\MenuController::class, 'get_user_log']);

    //По-новому минутные параметры
    Route::get('/minutes_param/{date}/{hour}', [Controllers\MinutesController::class, 'get_minute_param']);

    //По-новому часовые параметры
    Route::get('/hours_param/{date}', [Controllers\HoursController::class, 'get_hour_param']);

    //По-новому суточные параметры
    Route::get('/sut_param/{month}', [Controllers\SutController::class, 'get_sut_param']);

    //Для добавления в крон
    Route::get('/create_param/{params_type}', [Controllers\TestController::class, 'test']);     //создание тестовых данных
    Route::get('/create_record_rezhim_dks', [Controllers\TestController::class, 'create_record_rezhim_dks']);     //запись для отчета р\4 часа
    Route::get('/create_xml/{hours_xml}', [Controllers\XMLController::class, 'create_xml'])->name('create_xml'); //отправка xml
    Route::get('/create_record_svodniy', [Controllers\BalansController::class, 'create_record_svodniy'])->name('create_record_svodniy'); //Создание строк в сводном отчете каждый час в конце часа
    Route::get('/create_record_valoviy', [Controllers\BalansController::class, 'create_record_valoviy'])->name('create_record_valoviy'); //Создание строк в валовом отчете каждый час в конце часа
    Route::get('/update_guid', [Controllers\TestController::class, 'update_guid']);        ///Обновление GUID
    Route::get('/copy_record', [Controllers\SutJournalController::class, 'copy_record']);        ///Перенос серых строк в 8 утра каждого дня
    Route::get('/get_result_astragaz', [Controllers\AstraGazController::class, 'get_result_astragaz'])->name('get_result_astragaz');   //чтение папки с результатами АстраГаз
    Route::get('/create_astragaz_files', [Controllers\AstraGazController::class, 'create_astragaz_files'])->name('create_astragaz_files');   //класть файлы для расчетов АстраГаз
    Route::get('/create_xml_transgaz/{hours_xml}', [Controllers\XMLController_new::class, 'create_xml_transgaz'])->name('create_xml_transgaz'); //отправка xml
    Route::get('/create_xml_ius/{hours_xml}', [Controllers\XMLController_new::class, 'create_xml_ius'])->name('create_xml_ius'); //отправка xml

    //Изменение test_table
    Route::get('/signal_settings', [Controllers\TestTableController::class, 'settings']);
    Route::post('/signal_settings_store', [Controllers\TestTableController::class, 'signal_settings_store'])->name('signal_settings_store');

    Route::get('/signal_create', [Controllers\TestTableController::class, 'create']);
    Route::post('/store_object', [Controllers\TestTableController::class, 'store_object']);
    Route::post('/store_signal', [Controllers\TestTableController::class, 'store_signal']);



//Получить дерево side_menu
    Route::get('/getsidetree', [Controllers\SidetreeController::class, 'getSideTree']);
    Route::get('/get_parent/{parentId}', [Controllers\TestController::class, 'get_parent']);   //для дерева
    Route::get('/get_parent_name/{parentId}', [Controllers\TestController::class, 'get_parent_name']);   //для всплывашег над временными

    //Изменить временные показатели
    Route::post('/changetimeparams/{type}', [Controllers\SidetreeController::class, 'change_time_params']);
    Route::post('/createtimeparams', [Controllers\SidetreeController::class, 'create_time_params']);
    Route::post('/changeminsparams', [Controllers\SidetreeController::class, 'change_mins_params']);
    Route::post('/createminsparams', [Controllers\SidetreeController::class, 'create_mins_params']);

    //Журнал XML
    Route::get('/get_journal_xml_data', [Controllers\XMLController::class, 'get_journal_xml_data'])->name('get_journal_xml_data');
    Route::get('/journal_xml', [Controllers\XMLController::class, 'journal_xml'])->name('journal_xml');
    //Журнал DZ
    Route::get('/get_journal_dz', [Controllers\DZController::class, 'get_journal_dz'])->name('get_journal_dz');
    Route::get('/journal_dz', [Controllers\DZController::class, 'journal_dz'])->name('journal_dz');
    Route::get('/save_comment_dz/{id}/{text}', [Controllers\DZController::class, 'save_comment_dz'])->name('save_comment_dz');
    Route::get('/confirm_dz/{id}', [Controllers\DZController::class, 'confirm_dz'])->name('confirm_dz');

    //Отчеты
    Route::get('/reports', [Controllers\BalansController::class, 'reports'])->name('reports');
        //ДКС
            //Изменение режимов работы ГПА на ДКС
    Route::get('/gpa_rezhim', [Controllers\BalansController::class, 'gpa_rezhim'])->name('gpa_rezhim');
    Route::get('/get_gpa_rezhim', [Controllers\BalansController::class, 'get_gpa_rezhim'])->name('get_gpa_rezhim');
    Route::post('/post_gpa_rezhim', [Controllers\BalansController::class, 'post_gpa_rezhim'])->name('post_gpa_rezhim');
            //Отчет режимы работы турбоагрегатов
    Route::get('/get_gpa_rezhim_report/{dks}', [Controllers\BalansController::class, 'get_gpa_rezhim_report'])->name('get_gpa_rezhim_report');
    Route::get('/get_gpa_rezhim_report_data/{date}/{dks}', [Controllers\BalansController::class, 'get_gpa_rezhim_report_data'])->name('get_gpa_rezhim_report_data');
    Route::get('/print_gpa_rezhim_report/{date}/{dks}', [Controllers\BalansController::class, 'print_gpa_rezhim_report'])->name('print_gpa_rezhim_report');
        //Сводный отчет ННГДУ
    Route::get('/open_svodniy', [Controllers\BalansController::class, 'open_svodniy'])->name('open_svodniy');   //Открывает главную
    Route::get('/get_svodniy/{date}', [Controllers\BalansController::class, 'get_svodniy'])->name('get_svodniy');   //Получаем инфу в таблицу
    Route::get('/print_svodniy/{date}', [Controllers\BalansController::class, 'print_svodniy'])->name('print_svodniy');  //Отправляем на печать
    Route::get('/update_param_svodniy/{param_name}/{timestamp}/{id}/{val}', [Controllers\BalansController::class, 'update_param_svodniy'])->name('update_param_svodniy');  //Сохраняем в базу

    Route::get('/svodniy_setting', [Controllers\BalansController::class, 'svodniy_setting'])->name('svodniy_setting');  //Переход на страницу настройки
    Route::get('/get_all_params', [Controllers\MainTableController::class, 'get_all_params'])->name('get_all_params');   //Получаем все параметры (без объектов)
    Route::get('/save_param_svodniy/{params}/{hfrpok}', [Controllers\BalansController::class, 'save_param_svodniy'])->name('save_param_svodniy');  //Сохраняем настройки
    Route::get('/get_setting_svodniy', [Controllers\BalansController::class, 'get_setting_svodniy'])->name('get_setting_svodniy');  //Для получения настроек
    Route::get('/excel_svodniy/{date}', [Controllers\ExcelController::class, 'excel_svodniy'])->name('excel_svodniy');  //Эксель
        //Валовая добыча
    Route::get('/open_val_year', [Controllers\BalansController::class, 'open_val'])->name('open_val');   //открытие формы  (ok)
    Route::get('/get_val/{date}/{type}', [Controllers\BalansController::class, 'get_val'])->name('get_val');  //получение данных для таблиц (ok)
    Route::get('/print_val/{date}/{type}/{mesto}', [Controllers\BalansController::class, 'print_val'])->name('print_val'); //печать
    Route::get('/save_plan_month/{date}/{value}/{mestorozhdeniye}', [Controllers\BalansController::class, 'save_plan_month'])->name('save_plan_month');   //сохранение месячного плана
    Route::get('/get_plan/{date}/{type}/{mesto}', [Controllers\BalansController::class, 'get_plan'])->name('get_plan'); //получение планов на год по месторождениям
    Route::get('/valoviy_setting', [Controllers\BalansController::class, 'valoviy_setting'])->name('valoviy_setting');  //Переход на страницу настройки  (ok)
    Route::get('/get_setting_valoviy', [Controllers\BalansController::class, 'get_setting_valoviy'])->name('get_setting_valoviy');  //Для получения настроек  (ok)
    Route::get('/save_param_valoviy/{params}/{hfrpok}', [Controllers\BalansController::class, 'save_param_valoviy'])->name('save_param_valoviy');  //Сохраняем настройки (ok)
    Route::get('/open_val_month', [Controllers\BalansController::class, 'open_val_month'])->name('open_val_month');   //открытие формы (ok)
    Route::get('/open_val_day', [Controllers\BalansController::class, 'open_val_day'])->name('open_val_day');   //открытие формы (ok)

        //Балансовый
    Route::get('/open_balans', [Controllers\BalansController::class, 'open_balans'])->name('open_balans');   //открытие формы
    Route::get('/get_balans/{date}', [Controllers\BalansController::class, 'get_balans'])->name('get_balans');   //получение данных
    Route::get('/print_balans/{date}', [Controllers\BalansController::class, 'print_balans'])->name('print_balans');   //печать

/////График проведения ППР
    Route::get('/open_ppr', [Controllers\PPRController::class, 'open_ppr'])->name('open_ppr');    //открытие формы
    Route::get('/get_ppr/{year}', [Controllers\PPRController::class, 'get_ppr'])->name('get_ppr');    //получение данных
    Route::get('/delete_record_ppr/{id}', [Controllers\PPRController::class, 'delete_record_ppr'])->name('delete_record_ppr');    //получение данных
    Route::post('/update_record_ppr/{id_row}', [Controllers\PPRController::class, 'update_record_ppr'])->name('update_record_ppr');    //обновить вложенную строку
    Route::post('/create_record_ppr', [Controllers\PPRController::class, 'create_record_ppr'])->name('create_record_ppr');    //добавить запись
    Route::get('/print_ppr/{year}', [Controllers\PPRController::class, 'print_ppr'])->name('print_ppr');


/////Суточный журнал смены
    Route::get('/last_DZ/{date}', [Controllers\SutJournalController::class, 'last_DZ'])->name('last_DZ');    //открытие формы
    Route::get('/open_journal_smeny', [Controllers\SutJournalController::class, 'open_journal_smeny'])->name('open_journal_smeny');    //открытие формы
    Route::post('/save_journal_smeny/{date}', [Controllers\SutJournalController::class, 'save_journal_smeny'])->name('save_journal_smeny');   //сохранение строки
    Route::get('/get_insert_tabels/{timestamp}/{name_table}', [Controllers\SutJournalController::class, 'get_insert_tabels'])->name('get_insert_tabels');    //получение вложенных таблиц
    Route::get('/delete_record/{id_row}', [Controllers\SutJournalController::class, 'delete_record'])->name('delete_record');   //удалить вложенную строку
    Route::post('/update_record/{id_row}', [Controllers\SutJournalController::class, 'update_record'])->name('update_record');    //обновить вложенную строку
    Route::get('/replace_record/{id_row}/{date}', [Controllers\SutJournalController::class, 'replace_record'])->name('replace_record');    //перенести на текущий день
    Route::post('/save_td', [Controllers\SutJournalController::class, 'save_td'])->name('save_td');    //сохранение ячейки
    Route::get('/get_tds/{date}', [Controllers\SutJournalController::class, 'get_tds'])->name('get_tds');   //получение значений ячеек
    Route::get('/print_journal_smeny/{date}', [Controllers\SutJournalController::class, 'print_journal_smeny'])->name('print_journal_smeny');    //напечатать
    Route::get('/print_journal_smeny/{date_start}/{date_end}', [Controllers\SutJournalController::class, 'print_journal_smeny_period'])->name('print_journal_smeny_period');    //напечатать за период
    Route::post('/change_color_td', [Controllers\SutJournalController::class, 'change_color_td'])->name('change_color_td');   //смена цвета ячейки


////ТЭР
    Route::get('/open_ter/{yams_yub}', [Controllers\TerController::class, 'open_ter'])->name('open_ter');
    Route::get('/get_ter/{date}/{type}/{yams_yub}', [Controllers\TerController::class, 'get_ter'])->name('get_ter');
    Route::post('/save_ter/{yams_yub}', [Controllers\TerController::class, 'save_ter'])->name('save_ter');
    Route::get('/print_ter/{date}/{type}/{yams_yub}', [Controllers\TerController::class, 'print_ter'])->name('print_ter');

///sftp-настройка
    Route::post('/save_sftp_setting/{type}', [Controllers\XMLController::class, 'save_sftp_setting']);        ///Сохранение настроек sftp
    Route::get('/get_sftp_setting/{type}', [Controllers\XMLController::class, 'get_sftp_setting']);        ///Получение настроек sftp
    Route::get('/test_sftp/{type}', [Controllers\XMLController::class, 'test_sftp'])->name('test_sftp');

///AstraGaz
    Route::get('/open_astragaz', [Controllers\AstraGazController::class, 'open_astragaz'])->name('open_astragaz');
    Route::get('/get_astragaz', [Controllers\AstraGazController::class, 'get_astragaz'])->name('get_astragaz');
    Route::get('/remove_astragaz/{id}', [Controllers\AstraGazController::class, 'remove_astragaz'])->name('remove_astragaz');
    Route::get('/astragaz_setting', [Controllers\AstraGazController::class, 'astragaz_setting'])->name('astragaz_setting');   //выбор источника АстраГаз
    Route::get('/get_setting_astragaz', [Controllers\AstraGazController::class, 'get_setting_astragaz'])->name('get_setting_astragaz');   //выбор источника АстраГаз
    Route::get('/save_param_astragaz/{name_param}/{hfrpok}', [Controllers\AstraGazController::class, 'save_param_astragaz'])->name('save_param_astragaz');   //выбор источника АстраГаз


///Оперативное состояние скважин
    Route::get('/report_oper_skv_main', [Controllers\VlgController::class, 'report_oper_skv_main'])->name('report_oper_skv_main');
    Route::get('/report_oper_skv/{timestamp}', [Controllers\VlgController::class, 'open_oper_skv'])->name('open_oper_skv');
    Route::get('/get_data_oper_skv/{timestamp}', [Controllers\VlgController::class, 'get_data_oper_skv'])->name('get_data_oper_skv');
    Route::get('/save_td_oper/{id}/{text}/{timestamp}', [Controllers\VlgController::class, 'save_td_oper'])->name('save_td_oper');
    Route::get('/create_record_oper_skv', [Controllers\VlgController::class, 'create_record_oper_skv'])->name('create_record_oper_skv');
    Route::get('/remove_record_ope_skv/{timestamp}', [Controllers\VlgController::class, 'remove_record_ope_skv'])->name('remove_record_ope_skv');
    Route::get('/editable_record_ope_skv/{bool}/{timestamp}', [Controllers\VlgController::class, 'editable_record_ope_skv'])->name('editable_record_ope_skv');
    Route::get('/print_oper_skv/{timestamp}', [Controllers\VlgController::class, 'print_oper_skv'])->name('print_oper_skv');
    Route::get('/excel_oper_skv/{timestamp}',  [Controllers\ExcelController::class, 'excel_oper_skv'])->name('excel_oper_skv');

///Прием-сдача смены
    Route::get('/check_smena', [Controllers\DZController::class, 'check_smena'])->name('check_smena');   ///проверка, что в логах сдачи смены
    Route::get('/confirm_smena', [Controllers\DZController::class, 'confirm_smena'])->name('confirm_smena');   ///проверка, что в логах сдачи смены
    Route::get('/pass_smena', [Controllers\DZController::class, 'pass_smena'])->name('pass_smena');   ///проверка, что в логах сдачи смены
    Route::get('/log_smena', [Controllers\DZController::class, 'log_smena'])->name('log_smena');   ///страница с историей приема\сдачи смены
    Route::get('/get_journal_log_smena', [Controllers\DZController::class, 'get_journal_log_smena'])->name('get_journal_log_smena');   ///страница с историей приема\сдачи смены
///Грфик за период
    Route::get('/get_graph_history/{hfrpok}/{date_start}/{date_Stop}/{type}', [Controllers\DZController::class, 'get_graph_history'])->name('get_graph_history');   ///проверка, что в логах сдачи смены
///Блок для чата
    Route::get('/get_people_block', [Controllers\ChatController::class, 'get_people_block'])->name('get_people_block');   ///получаем список людей
    Route::get('/get_chat/{name}', [Controllers\ChatController::class, 'get_chat'])->name('get_chat');   ///получаем текст чата
    Route::get('/set_type_messege/{id}/{type}', [Controllers\ChatController::class, 'set_type_messege'])->name('set_type_messege');   ///устанавливаем тип сообщения
    Route::post('/send_messege', [Controllers\ChatController::class, 'send_messege'])->name('send_messege');   ///отправляем сообщение
    Route::get('/update_people_block', [Controllers\ChatController::class, 'update_people_block'])->name('update_people_block');   ///обновляем список


//ГЛАВНАЯ ТАБЛИЦА
    Route::post('/add-index', [Controllers\MainTableController::class, 'add_index']);
    Route::get('/maintable', [Controllers\MainTableController::class, 'index']);
    Route::get('/getmaintable', [Controllers\MainTableController::class, 'getMainTableInfo']);
    Route::post('/changetable', [Controllers\MainTableController::class, 'changeMainTable']);
    Route::post('/delete-from-main-table', [Controllers\MainTableController::class, 'delete_row']);
    Route::get('/getfieldsnames', [Controllers\MainTableController::class, 'getFieldsName']);





