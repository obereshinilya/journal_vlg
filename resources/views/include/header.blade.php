{{--Подключаем слайдер--}}
<link rel="stylesheet" href="{{asset('assets/css/slider.css')}}">
{{--Подключает вспывашку--}}
<link rel="stylesheet" href="{{asset('assets/css/tooltip.css')}}">
<div class="header">
    <div class="header_container">
            <ul class="header_menu">
                <li onclick="localStorage.clear()"><a href="/" style="padding-right: 37px;">Часовые показатели</a></li>
                <li onclick="localStorage.clear()"><a href="/sut" style="padding-right: 37px;">Суточные показатели</a></li>
{{--                <li onclick="localStorage.clear()"><a href="/minutes" style="padding-right: 37px;">Реальное время</a></li>--}}
                <style>
                    .circle{
                        width: 20px;
                        height: 20px;
                        opacity: 0.8;
                        border-radius: 50%;
                        background-color: yellow;
                        position: relative;
                        float: right;
                        top: -15px;
                        font-size: 15px;
                        text-align: center;
                        font-weight: bold;
                        color: black;
                        padding-top: 2px;
                        display: none;
                    }
                </style>
                <li id="setting_OJD_header"><a href="#" style="padding-right: 37px;">Смежные системы<i class="fa fa-angle-down"></i><div class="circle" id="sum_count">0</div></a>
                    <ul class="submenu_ul">
                        <div>
                            <li class="submenu_li"><a href="/journal_xml" class="submenu_a level1">Журнал XML<div class="circle" id="xml_error_count" style="top: 0px">0</div></a></li>
                        </div>
                        <div>
                            <li class="submenu_li"><a href="/journal_dz" class="submenu_a level1">Диспетчерские задания<div class="circle" id="dz_count" style="top: 0px">0</div></a></li>
                        </div>
                    </ul>
                </li>
                <li><a href="/reports" style="padding-right: 37px;">Отчеты<i class="fa fa-angle-down"></i></a>
                <li id="setting_OJD_header"><a href="#" style="padding-right: 37px;">Настройка ОЖД<i class="fa fa-angle-down"></i></a>
                    <ul class="submenu_ul">
                        <div>
                            <li class="submenu_li"><a href="/open_user_log" class="submenu_a level1">Журнал действий оператора</a></li>
                        </div>
                        <div>
                            <li class="submenu_li"><a href="/signal_settings" class="submenu_a level1">Редактирование параметров</a></li>
                        </div>
                        <div>
                            <li class="submenu_li"><a href="/signal_create" class="submenu_a level1">Добавление параметров</a></li>
                        </div>
                        <div>
                            <li class="submenu_li">
                                <a href="#" class="submenu_a" onclick="if (document.getElementById('sftp_setting').style.display === 'none'){document.getElementById('sftp_setting').style.display = 'table'; get_setting_sftp()} else {document.getElementById('sftp_setting').style.display = 'none'}">Сервер М АСДУ</a>
                            </li>
                            <div id="sftp_setting" style="display: none; background-color: #1E90FF">
                                <div style="width: 100%">
                                    <table style="display: table; table-layout: fixed; width: 100%; text-align: center; margin-top: 5px">
                                        <tbody>
                                        <tr>
                                            <td id="osnovnoi" style="font-weight: bold">Основной</td>
                                            <td>
                                                <label class="switch">
                                                    <input id="switch" data-server="osnovnoi" onchange="change_server_sftp(this)" type="checkbox">
                                                    <span class="slider"></span>
                                                </label>
                                            </td>
                                            <td id="reserv">Резервный</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <table style="width: auto; table-layout: fixed">
                                    <colgroup>
                                        <col style="width: 30%">
                                        <col style="width: 70%">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <td style="padding: 0 5px; margin: 0 5px; text-align: center">Логин</td>
                                            <td style="padding: 0 5px; margin: 0 5px"><input onchange="save_sftp_setting()" id="user" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 0 5px; margin: 0 5px; text-align: center">Пароль</td>
                                            <td class="input-wrapper_type_disc" style="padding: 0 5px; margin: 0 5px"><input onchange="save_sftp_setting()" id="password" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 0px; margin: 0px; text-align: center">Адрес</td>
                                            <td style="padding: 0 5px; margin: 0 5px"><input onchange="save_sftp_setting()" id="adres_sftp" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 0px; margin: 0px; text-align: center">Путь</td>
                                            <td style="padding: 0 5px; margin: 0 5px"><input onchange="save_sftp_setting()" id="path_sftp" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align: center">
                                                <button id="button_test_sftp" class="button button1" style="font-size: 12px; padding: 3px" onclick="check_sftp(this)">Тестовое подключение</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </ul>
                </li>
{{--                <li id="journal_operator_header"><a href="/open_user_log" style="padding-right: 37px;">Журнал действий оператора<i class="fa fa-angle-down"></i></a>--}}
                <li id="journal_operator_header"><a href="/open_journal_smeny" style="padding-right: 37px;">Журнал смены<i class="fa fa-angle-down"></i></a>
                </li>
            </ul>
        <h3 style="margin-top: 7px; float: right">Оперативный журнал диспетчера</h3>
    </div>
</div>

<script>
    function check_error_xml(){
        $.ajax({
            url: '/check_error_xml',
            method: 'get',
            success: function (res) {
                if (Number(res) === 0){
                    document.getElementById('xml_error_count').textContent = '0';
                    document.getElementById('xml_error_count').style.display = 'none';
                }else {
                    document.getElementById('xml_error_count').textContent = Number(res);
                    document.getElementById('xml_error_count').style.display = 'inline-block';
                    document.getElementById('sum_count').textContent = Number(res)+Number(document.getElementById('dz_count').textContent);
                    document.getElementById('sum_count').style.display = 'inline-block';
                }
            },
        })
    }

    function check_new_dz(){
        $.ajax({
            url: '/check_new_dz',
            method: 'get',
            success: function (res) {
                if (Number(res) === 0){
                    document.getElementById('dz_count').textContent = '0';
                }else {
                    document.getElementById('dz_count').textContent = Number(res);
                    document.getElementById('dz_count').style.display = 'inline-block';
                    document.getElementById('sum_count').textContent = Number(res)+Number(document.getElementById('xml_error_count').textContent);
                    document.getElementById('sum_count').style.display = 'inline-block';
                }
            },
        })
    }
    function check_sftp(button){
        var type = document.getElementById('switch').getAttribute('data-server')
        $.ajax({
            url: '/test_sftp/'+type,
            method: 'get',
            async:false,
            timeout:300,
            success: function (res) {
                button.textContent = res
            },
            error: function (){
                button.textContent = 'Ошибка!'
            },
        })
    }
    function get_setting_sftp(){
        var type = document.getElementById('switch').getAttribute('data-server')
        $.ajax({
            url: '/get_sftp_setting/'+type,
            method: 'get',
            success: function (res) {
                document.getElementById('user').value = res['user']
                document.getElementById('password').value = res['password']
                document.getElementById('adres_sftp').value = res['adres_sftp']
                document.getElementById('path_sftp').value = res['path_sftp']
            },
            error: function (){
                document.getElementById('user').value = ''
                document.getElementById('password').value = ''
                document.getElementById('adres_sftp').value = ''
                document.getElementById('path_sftp').value = ''
            },
            async:false
        })
    }
    function change_server_sftp(input){
        if (document.getElementById('osnovnoi').style.fontWeight === 'bold'){
            document.getElementById('osnovnoi').style.fontWeight = 'normal'
            document.getElementById('reserv').style.fontWeight = 'bold'
        }else {
            document.getElementById('osnovnoi').style.fontWeight = 'bold'
            document.getElementById('reserv').style.fontWeight = 'normal'
        }
        if (input.getAttribute('data-server') === 'osnovnoi'){
            input.setAttribute('data-server', 'reserv')
        } else {
            input.setAttribute('data-server', 'osnovnoi')
        }
        document.getElementById('button_test_sftp').textContent = 'Тестовое подключение'
        get_setting_sftp()
    }
    function save_sftp_setting(){
        var arr = new Map()
        arr.set('type', document.getElementById('switch').getAttribute('data-server'))
        arr.set('user', document.getElementById('user').value)
        arr.set('password', document.getElementById('password').value)
        arr.set('adres_sftp', document.getElementById('adres_sftp').value)
        arr.set('path_sftp', document.getElementById('path_sftp').value)
        var data = Object.fromEntries(arr)
        $.ajax({
            url: '/save_sftp_setting/'+document.getElementById('switch').getAttribute('data-server'),
            data: data,
            method: 'POST',
            success: function (res) {
                document.getElementById('button_test_sftp').textContent = 'Тестовое подключение'
            },
            async:false
        })
    }

    $(document).ready(function(){
        check_error_xml()
        check_new_dz()
        setInterval(check_error_xml, 10000)
        setInterval(check_new_dz, 10000)
        $('.level2').hide()

        $('.level1').mouseenter(function(event){
            $(event.currentTarget.parentNode).next().show()
        });

        $('.level1').parent().mouseleave(function(event){
            if ($(event.currentTarget).next().is(':hover') === false){
                $(event.currentTarget).next().hide()
            }
        })

    })


    function Show_child_first() {
        var first_list = document.querySelectorAll("#first_list");
        if (first_list[0].style.display == ""){
            for(var i = 0; i < first_list.length; i++){
                first_list[i].style.display = "none";
                var icon = document.getElementById('icon_first');
                icon.querySelector('b').textContent = "+";
            }
        } else {
            for(var i = 0; i < first_list.length; i++){
                first_list[i].style.background = 'rgb(58,146,229)';
                first_list[i].style.display = "";
                var icon = document.getElementById('icon_first');
                icon.querySelector('b').textContent = "-";
                Close_child(1);
            }
        }
    }

    function Show_child_second() {
        var second_list = document.querySelectorAll("#second_list");
        if (second_list[0].style.display == ""){
            for(var i = 0; i < second_list.length; i++){
                second_list[i].style.display = "none";
                var icon = document.getElementById('icon_second');
                icon.querySelector('b').textContent = "+";
            }
        } else {
            for (var i = 0; i < second_list.length; i++) {
                second_list[i].style.background = 'rgb(58,146,229)';
                second_list[i].style.display = "";
                var icon = document.getElementById('icon_second');
                icon.querySelector('b').textContent = "-";
                Close_child();
            }
        }
    }

    function Close_child(a) {
        if(a){
            var icon = document.getElementById('icon_second');
            icon.querySelector('b').textContent = "+";
            var list = document.querySelectorAll("#second_list");
        } else {
            var icon = document.getElementById('icon_first');
            icon.querySelector('b').textContent = "+";
            var list = document.querySelectorAll("#first_list");

        }
        for(var i = 0; i < list.length; i++){
            list[i].style.display = "none";
        }
    }

</script>

<style>
    input:checked + .slider{
        background-color: #cccccc;
    }
    .input-wrapper_type_disc input {
        -webkit-text-security: disc;
    }
    .header_menu li ul {
        visibility: hidden;
        opacity: 0;
        min-width: 5rem;
        position: absolute;
        padding-top: 1rem;
        left: 0;
        display: none;
        background:#0079c2;
        padding-left: 0px;
        border-radius: 5px;
    }

    .header_menu li ul li a{
        margin-bottom: 10px;
        margin-top: 10px;
        width: 200px;
    }

    .header_menu li:hover > ul,
    .header_menu li ul:hover {
        visibility: visible;
        opacity: 1;
        display: block;
    }

    .header_menu li ul li {
        clear: both;
        width: 100%;
        border-bottom: 1px solid rgba(255,255,255,.3);
    }

    .header_menu li  {
        display: block;
        float: left;
        position: relative;
        text-decoration: none;
        transition-duration: 0.5s;
    }

    .header_menu li li:hover ul {
        display:block; /* shows sub-sublist on hover */
        margin-left:200px; /* this should be the same width as the parent list item */
        margin-top:-35px; /* aligns top of sub menu with top of list item */
    }
    .button {
        background-color: #4CAF50;
        border: none;
        border-radius: 6px;
        color: white;
        height: 3%;
        padding: 6px 12px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 13px;
        margin: 4px 2px;
        -webkit-transition-duration: 0.4s; /* Safari */
        transition-duration: 0.4s;
        cursor: pointer;
    }

    .button1 {
        background-color: white;
        color: black;
        border: 2px solid #008CBA;
    }

    .button1:hover {
        background-color: #008CBA;
        color: white;
    }
</style>

