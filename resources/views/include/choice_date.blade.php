<div class="date_div">
    <div class="date-input-group">
        <input type="date" id="table_date_start" class="date_input" required onkeydown="return false">
        <label for="table_date_start" class="table_date_label">Дата</label>
    </div>

    <div class="date-input-group" id="stop-date-div">
        <input type="date" id="table_date_stop" class="date_input" required onkeydown="return false">
        <label for="table_date_stop" class="table_date_label">Конец</label>
    </div>


{{--    <div class="period-btns">--}}
{{--        <a class="choice-period-btn" id="period-btn">Выбрать период</a>--}}
{{--        <a class="choice-period-btn" id="all-period-btn">Весь период</a>--}}
{{--    </div>--}}


    <input type="hidden" value="date" id="date-type">
</div>

<script>
    // $('#table_date_stop').hide();
    // $('.table_date_label[for=table_date_stop]').hide();

    $('#stop-date-div').hide();

    $('#period-btn').click(function (){
        if ($('#date-type').val()=='date'){
            // $('#table_date_stop').show();
            // $('.table_date_label[for=table_date_stop]').show();
            $('#stop-date-div').show('fast');
            $('#date-type').val('period')
            $('.table_date_label[for=table_date_start]').text('Начало');
            $(this).text('Выбрать дату');
        }
        else{
            // $('#table_date_stop').hide();
            // $('.table_date_label[for=table_date_stop]').hide();
            // $('#stop-date-div').slideToggle('medium', function() {
            //     if ($(this).is(':visible'))
            //         $(this).css('display','inline-block');
            // });
            $('#stop-date-div').hide("fast");
            $('#date-type').val('date')
            $('.table_date_label[for=table_date_start]').text('Дата');
            $(this).text('Выбрать период');
        }

    })

    var today = new Date();

    // function dates_to_today(){
    //     $('#table_date_start').val(today.toISOString().substring(0, 10))
    //     $('#table_date_stop').val(today.toISOString().substring(0, 10))
    // }


    // $('#table_date_start').val(today.toISOString().substring(0, 10))
    $('#table_date_stop').val(today.toISOString().substring(0, 10))

    document.getElementById("table_date_start").setAttribute("max", today.toISOString().substring(0, 10));
    document.getElementById("table_date_stop").setAttribute("max", today.toISOString().substring(0, 10));
    document.getElementById("table_date_stop").setAttribute("min", $('#table_date_start').val());


    $('#table_date_start').change(function() {
        document.getElementById("table_date_stop").setAttribute("min", $('#table_date_start').val());

        var start_date = new Date($('#table_date_start').val());
        var stop_date = new Date($('#table_date_stop').val());

        if (start_date > stop_date) {
            $('#table_date_stop').val(start_date.toISOString().substring(0, 10));
        }
    });
</script>



<style>
    /*.none div{*/
    /*    display: none;*/
    /*}*/

    .date_div {
        position: relative;
        /*padding: 15px 0 0;*/
        /*margin-top: 10px;*/
        width: 100%;
        display: table;
    }
    .date_input{
        font-family: inherit;
        width: 100%;
        border: 0;
        border-bottom: 2px solid #9b9b9b;
        outline: 0;
        font-size: 1.3rem;
        color: black;
        padding: 7px 0;
        background: transparent;
        transition: border-color 0.2s;
    }

    .date-input-group{
        /*width: 30%;*/
        margin: 8px 5px;
        display: table-cell;
        padding-left: 5px;
        padding-right: 10px;
        float:left;
    }

    input[type=date]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        display: none;
    }
    input[type=date]::-webkit-clear-button {
        -webkit-appearance: none;
        display: none;
    }



    .date_input::placeholder {
        color: transparent;
    }
    .date_input:placeholder-shown ~ .form__label {
        font-size: 1.3rem;
        cursor: text;
        top: 20px;
    }

    .table_date_label {
        position: absolute;
        top: 0;
        display: block;
        transition: 0.2s;
        font-size: 1rem;
        color: #9b9b9b;
    }
    .date_input:focus {
        /*padding-bottom: 6px;*/
        font-weight: 700;
        /*border-width: 3px;*/
        border-image: linear-gradient(to right, black, gray);
        border-image-slice: 1;
    }
    .date_input:focus ~ .table_date_label {
        position: absolute;
        top: 0;
        display: block;
        transition: 0.2s;
        font-size: 1rem;
        color: black;
        font-weight: 700;
    }

    .choice-period-btn{
        /*box-sizing: border-box;*/
        display: inline-block;
        min-width: 1.5em;
        padding: .5em 1em;
        text-align: center;
        text-decoration: none !important;
        cursor: pointer;
        color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        background-color: #0079c2;
        /*margin-left: 15px;*/
        /*margin-right: 5px;*/
        /*position: absolute;*/
        /*top: calc(50% - 8px);*/
        width: 110px;
    }

    .period-btns{
        display: inline-block;
        text-align: center;
        text-decoration: none !important;
        cursor: pointer;
        position: absolute;
        top: calc(50% - 8px);
    }

</style>
