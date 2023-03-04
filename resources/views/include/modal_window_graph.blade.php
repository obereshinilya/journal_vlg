<div class="modal_graph" id="modal_graph">
    <div class="modal-window-graph">
        <h1 id="text_graph"></h1>
        <button class="button button1" id="smena_button" style="position: absolute; height: auto; z-index: 9999"
                onclick="print_graph()"><p style="margin: 0px">Печать</p>
        </button>
        <div id="div_to_graph"></div>
    </div>
    <div class="overlay_graph" onclick="this.parentNode.style.display = 'none'">

    </div>
</div>

<script>
    function open_modal_graph(text){
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
