<div class="modal_ober" id="modal_smena">
    <div class="modal-window-ober">
        <h1 id="text_smena"></h1>
        <button class="button button1" id="smena_button" style=""
                onclick="confirm_smena()">Принять смену
        </button>
    </div>
    <div class="overlay_ober" >
{{--        onclick="this.parentNode.style.display = 'none'"--}}
    </div>
</div>

<script>
    function open_modal_smena(text){
        document.getElementById('modal_smena').style.display = 'flex'
        document.getElementById('text_smena').textContent = text
    }
    function confirm_smena(){
        $.ajax({
            url: '/confirm_smena',
            method: 'GET',
            success: function (res) {
                document.getElementById('modal_smena').style.display = 'none'
            },
            async: false
        })
    }
    function pass_smena(){
        $.ajax({
            url: '/pass_smena',
            method: 'GET',
            success: function (res) {
                window.location.href = '/'
            },
            async: false
        })
    }
    ///Проверка принятия смены
    function check_smena(){
        $.ajax({
            url: '/check_smena',
            method: 'GET',
            success: function (res) {
                if (res['text']){
                    if (res['commit_smena'] == true){
                        document.getElementById('smena_button').style.display = 'inline-block'
                    }else{
                        document.getElementById('smena_button').style.display = 'none'
                    }
                    open_modal_smena(res['text'])
                }
            },
            async: false
        })
    }
</script>

