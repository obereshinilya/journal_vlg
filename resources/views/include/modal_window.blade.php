<div class="modal_ober" id="modal_ober">
    <div class="modal-window-ober">
        <h1 id="text_modal"></h1>
    </div>
    <div class="overlay_ober" onclick="this.parentNode.style.display = 'none'">

    </div>
</div>

<script>
    function open_modal_ober(text){
        document.getElementById('modal_ober').style.display = 'flex'
        document.getElementById('text_modal').textContent = text
    }
</script>

<style>
    .modal_ober{
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
    .modal_ober .overlay_ober{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
        opacity: 0.7;
        z-index: 9999899;
    }
    .modal-window-ober{
        z-index: 9999999;
        position: relative;
        width: 300px;
        border-radius: 10px;
        box-shadow: 0 10px 15px rgba(0,0,0,.4);
        background-color: #fff;
        padding: 20px;
    }

</style>
