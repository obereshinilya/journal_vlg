<div class="modal_graph" id="modal_graph">
    <div class="modal-window-graph">
        <h1 id="text_graph" style="display: none"></h1>
        <div style="position: absolute; display: inline-block; z-index: 9999; left: 30%">
            <img onclick="add_param()" style="width: 20px; height: auto; margin: auto" src="assets/images/icons/dot.svg">
            <input type="date" id="graph_date_start" onchange="get_graph_history()" style="width: 150px; height: auto; z-index: 9999; margin-right: 15px; margin-left: 15px; color: black" class="date_input">
            <input type="date" id="graph_date_stop" onchange="get_graph_history()" style=" width: 150px; height: auto; z-index: 9999; margin-right: 15px; color: black" class="date_input">
            <button class="button button1" id="smena_button" style="height: auto; z-index: 9999"
                    onclick="print_graph()"><p style="margin: 0px">Печать</p>
            </button>
        </div>
        <div id="div_to_graph"></div>
    </div>
    <div class="overlay_graph" onclick="this.parentNode.style.display = 'none'; document.getElementById('modal_graph').classList.remove('many_param')">

    </div>
</div>

<script>
    function open_modal_graph(text){
        var current_url = window.location.href;
        current_url = current_url.split('/')[3]
        if (current_url === ''){
            var today = new Date();
            document.getElementById('graph_date_start').setAttribute('type', 'date')
            document.getElementById('graph_date_stop').setAttribute('type', 'date')
            document.getElementById("graph_date_start").setAttribute("max", today.toISOString().substring(0, 10));
            document.getElementById("graph_date_stop").setAttribute("max", today.toISOString().substring(0, 10));
            document.getElementById("graph_date_stop").setAttribute("min", today.toISOString().substring(0, 10));
        }else {
            var today = moment(new Date()).format('YYYY-MM');
            document.getElementById('graph_date_start').setAttribute('type', 'month')
            document.getElementById('graph_date_stop').setAttribute('type', 'month')
            document.getElementById("graph_date_start").setAttribute("max", today);
            document.getElementById("graph_date_stop").setAttribute("max", today);
            document.getElementById("graph_date_stop").setAttribute("min", today);
        }

        document.getElementById('graph_date_start').value = $('#table_date_start').val()
        document.getElementById('graph_date_stop').value = $('#table_date_start').val()

        document.getElementById('modal_graph').style.display = 'flex'
        document.getElementById('text_graph').textContent = text
    }
    function print_graph(){
        var current_url = window.location.href;
        current_url = current_url.split('/')[3]
        if (current_url === ''){
            localStorage.setItem('day', $('#table_date_start').val().split('-')[2])
            localStorage.setItem('month', $('#table_date_start').val().split('-')[1])
            localStorage.setItem('year', $('#table_date_start').val().split('-')[0])
        }else {
            localStorage.setItem('to_month', $('#table_date_start').val())
        }
        var ptintContent = document.getElementById('div_to_graph').innerHTML
        document.body.innerHTML = ptintContent
        window.print()
        window.location.href = current_url
    }
    function add_param(){
        document.getElementById('modal_graph').style.display = 'none'
        document.getElementById('modal_graph').classList.add('many_param')
        open_modal_ober('Выберете дополнительный параметр!')
    }
    function get_graph_history(){
        document.getElementById("graph_date_start").setAttribute("max", $('#graph_date_stop').val());
        document.getElementById("graph_date_stop").setAttribute("min", $('#graph_date_start').val());

        var hfrpok = document.getElementById('text_graph').textContent
        var date_start = document.getElementById('graph_date_start').value
        var date_stop = document.getElementById('graph_date_stop').value
        var current_url = window.location.href;
        current_url = current_url.split('/')[3]
        if (current_url !== ''){
            var type = 'sut'
        }else {
            var type = 'hour'
        }
        $.ajax({
            url: '/get_graph_history/' + hfrpok+ '/'+date_start+'/'+date_stop+'/'+type,
            method: 'GET',
            success: function (res) {
                var series = []
                var xaxis = res['xaxis'][0]
                for (var i=0; i<res['data'].length; i++){
                    let series_obj = {
                        name: res['statick_tr'][i],
                        data: res['data'][i],
                    }
                    series.push(series_obj)
                }
                if (i>1){
                    var static_tr = 'Несколько параметров'
                    var e_unit = ''
                }else {
                    var static_tr = document.getElementById('statickItemInfoTable').querySelector(`tr[data-id="${hfrpok}"]`).querySelector(`td[data-name="namepar1"]`).textContent
                    var e_unit = document.getElementById('statickItemInfoTable').querySelector(`tr[data-id="${hfrpok.split(' ')[0]}"]`).getElementsByTagName('span')[0].textContent
                }
                document.getElementById('div_to_graph').innerText = ''
                var options = {
                    series: series,
                    chart: {
                        height: 450,
                        type: 'area',
                        locales: [{
                            name: 'en',
                            options: {
                                months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                                shortMonths: ['Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Ноя', 'Дек'],
                                days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                                shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                                toolbar: {
                                    download: 'Загрузить SVG',
                                    selection: 'Selection',
                                    selectionZoom: 'Приблизить область',
                                    zoomIn: 'Приблизить',
                                    zoomOut: 'Отдалить',
                                    pan: 'Захват',
                                    reset: 'Сбросить приближение',
                                }
                            }
                        }]
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    title: {
                        text: static_tr,
                        align: 'left'
                    },
                    subtitle: {
                        text: 'За период с ' + date_start + ' по ' + date_stop,
                        align: 'left'
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: xaxis,
                    },
                    yaxis: {
                        title: {
                            text: e_unit
                        },
                    },
                    tooltip: {
                        x: {
                            format: 'dd MMM HH:mm'
                        },
                    },
                };

                var chart = new ApexCharts(document.querySelector("#div_to_graph"), options);
                chart.render();

            },
            async: false
        })


    }
</script>

<style>
    .modal_graph{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        text-align: center;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .modal_graph .overlay_graph{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
        opacity: 0.7;
        z-index: 9999899;
    }
    .modal-window-graph{
        z-index: 9999999;
        position: relative;
        width: 70%;
        border-radius: 15px;
        box-shadow: 0 10px 15px rgba(0,0,0,.4);
        background-color: #fff;
        padding: 20px;
    }

</style>
