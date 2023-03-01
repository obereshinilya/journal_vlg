<div class="date_div">
    <div class="date-input-group">
        <input type="date" id="table_date_start" class="date_input" required onkeydown="return false">
        <label for="table_date_start" class="table_date_label">Дата</label>
    </div>
    <div class="date-input-group">
        <select onchange="get_table_data()" class="date_input" id="select_interval">
            <option value="0">00:00-01:00</option>
            <option value="1">01:00-02:00</option>
            <option value="2">02:00-03:00</option>
            <option value="3">03:00-04:00</option>
            <option value="4">04:00-05:00</option>
            <option value="5">05:00-06:00</option>
            <option value="6">06:00-07:00</option>
            <option value="7">07:00-08:00</option>
            <option value="8">08:00-09:00</option>
            <option value="9">09:00-10:00</option>
            <option value="10">10:00-11:00</option>
            <option value="11">11:00-12:00</option>
            <option value="12">12:00-13:00</option>
            <option value="13">13:00-14:00</option>
            <option value="14">14:00-15:00</option>
            <option value="15">15:00-16:00</option>
            <option value="16">16:00-17:00</option>
            <option value="17">17:00-18:00</option>
            <option value="18">18:00-19:00</option>
            <option value="19">19:00-20:00</option>
            <option value="20">20:00-21:00</option>
            <option value="21">21:00-22:00</option>
            <option value="22">22:00-23:00</option>
            <option value="23">23:00-00:00</option>
        </select>
        <label for="table_date_start" class="table_date_label">Час</label>
    </div>
</div>




<style>
    /*.none div{*/
    /*    display: none;*/
    /*}*/

    .date_div {
        position: relative;
        padding: 15px 0 0;
        margin-top: 10px;
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
