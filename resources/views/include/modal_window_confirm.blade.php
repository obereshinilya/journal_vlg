<div class="modal_confirm_ober" id="modal_confirm_ober">
    <div class="modal-window-confirm-ober">
        <table style="display: table; table-layout: fixed">
            <tbody>
            <tr>
                <h2 id="text_modal_confirm"></h2>
            </tr>
            <tr>
                <button id="confirm_ober" class="button button1">Подтвердить</button>
                <button id="cancel_ober" class="button button1" style="margin-left: 30px">Отмена</button>
            </tr>
            </tbody>
        </table>

    </div>
    <div class="overlay_confirm_ober" id="overlay_confirm_ober">

    </div>
</div>

<script>
    function open_modal_confirm_ober(text, object) {

        document.getElementById('modal_confirm_ober').style.display = 'flex'
        document.getElementById('text_modal_confirm').textContent = text
        var result = ''

        document.getElementById('confirm_ober').addEventListener('click', function () {
            document.getElementById('modal_confirm_ober').style.display = 'none'
            if (object) {
                confirm_request(object)
            } else {
                confirm_request_print()
            }
        })
        document.getElementById('cancel_ober').addEventListener('click', function () {
            document.getElementById('modal_confirm_ober').style.display = 'none'
        })
        document.getElementById('overlay_confirm_ober').addEventListener('click', function () {
            document.getElementById('modal_confirm_ober').style.display = 'none'
        })

    }


</script>

<style>
    .modal_confirm_ober {
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

    .modal_confirm_ober .overlay_confirm_ober {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
        opacity: 0.7;
        z-index: 9999899;
    }

    .modal-window-confirm-ober {
        z-index: 9999999;
        position: relative;
        width: 300px;
        border-radius: 10px;
        box-shadow: 0 10px 15px rgba(0, 0, 0, .4);
        background-color: #fff;
        padding: 20px;
    }

</style>
