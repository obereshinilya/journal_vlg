<div class="modal_export_ober" id="modal_export_ober">
    <div class="modal-window-export-ober">
        <p style="float: right; top: 0px; margin: 0px; opacity: 0.5" onclick="document.getElementById('modal_export_ober').style.display = 'none'">x</p>
        <table style="display: table; table-layout: fixed">
            <tbody>
                <tr>
                    <h2>Укажите формат экспорта</h2>
                </tr>
                <tr>
                    <button id="excel_ober" class="button button1" onclick="CallExcel()">Excel</button>
                    <button id="print_time_ober" class="button button1" style="margin-left: 30px" onclick="CallPrint()">Печать (pdf)</button>
                </tr>
            </tbody>
        </table>

    </div>
    <div class="overlay_export_ober" id="overlay_export_ober">

    </div>
</div>

<script>
    function open_modal_export_ober(object){

        document.getElementById('modal_export_ober').style.display = 'flex'
        var result = ''

        document.getElementById('export_ober').addEventListener('click', function () {
            document.getElementById('modal_export_ober').style.display = 'none'
            if (object){
                export_request(object)
            }else {
                export_request()
            }
        })
        document.getElementById('cancel_ober').addEventListener('click', function () {
            document.getElementById('modal_export_ober').style.display = 'none'
        })
        document.getElementById('overlay_export_ober').addEventListener('click', function () {
            document.getElementById('modal_export_ober').style.display = 'none'
        })

    }


</script>

<style>
    .modal_export_ober{
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
    .modal_export_ober .overlay_export_ober{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
        opacity: 0.7;
        z-index: 9999899;
    }
    .modal-window-export-ober{
        z-index: 9999999;
        position: relative;
        width: 300px;
        border-radius: 10px;
        box-shadow: 0 10px 15px rgba(0,0,0,.4);
        background-color: #fff;
        padding: 20px;
    }

</style>
