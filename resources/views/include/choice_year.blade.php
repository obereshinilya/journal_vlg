<div class="date_div">
    <div class="date-input-group">
        <select class="date_input" id="year"></select>
        <label for="table_date_start" class="table_date_label">Год</label>
    </div>

    <input type="hidden" value="date" id="date-type">
</div>

<script>
    start_date =moment(new Date()).format('YYYY')
    for (let year = 2021; year <= start_date; year++) {
        let options = document.createElement("OPTION");
        if (year == start_date){
            options.setAttribute ("selected", true);
        }
        document.getElementById("year").appendChild(options).innerHTML = year;
    }

    $('#year').change(function (){
         start_date = moment(new Date($('#year').val())).format('YYYY');
    })

</script>



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
