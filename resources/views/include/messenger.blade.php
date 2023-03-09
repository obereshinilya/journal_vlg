<div class="messenger_mini" id="messenger_mini" onclick="open_messenger()">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 121, 194, 1);transform: scaleX(-1);msFilter:progid:DXImageTransform.Microsoft.BasicImage(rotation=0, mirror=1);"><path d="M12 2C6.486 2 2 5.589 2 10c0 2.908 1.898 5.515 5 6.934V22l5.34-4.005C17.697 17.852 22 14.32 22 10c0-4.411-4.486-8-10-8zm0 14h-.333L9 18v-2.417l-.641-.247C5.67 14.301 4 12.256 4 10c0-3.309 3.589-6 8-6s8 2.691 8 6-3.589 6-8 6z"></path><path d="M7 7h10v2H7zm0 4h7v2H7z"></path></svg>
</div>
<div class="messenger" id="messenger">
    <div class="people_block">
        <div class="search_block">
            <div class="search_input">
                <input class="text-field__input" type="text" id="search_people" style="width: 100%" placeholder="Поиск пользователя...">
            </div>
        </div>
        <div class="people">
            <div class="one_people selected_people">
                <table class="table_one_people">
                    <tbody>
                        <tr>
                            <td class="nick_name">AdminQA2</td>
                            <td>12:45</td>
                        </tr>
                        <tr>
                            <td>Обратите внимание на перенос сообщения</td>
                            <td>2</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="one_people">
                <table class="table_one_people">
                    <tbody>
                        <tr>
                            <td class="nick_name">AdminQA1</td>
                            <td>12:45</td>
                        </tr>
                        <tr>
                            <td>Обратите внимание на перенос сообщения</td>
                            <td>2</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="name_recipient">
        <div class="close_messenger" onclick="open_messenger()">
            <p style="color: white; text-align: center; vertical-align: center; font-weight: bold">&#10006;</p>
        </div>
        <div class="name" style="text-align: center">
            <p style="color: white; width: auto; margin: 5px; font-size: 14px">Иванов Иван Иванович</p>
            <p style="color: white; width: auto; margin: 0px; font-size: 14px">В сети 5 минут назад</p>
        </div>
    </div>
    <div class="chat_div">
        <div class="text_chat">
            <table style="width: 100%">
                <tbody>
                    <tr>
                        <td>
                            <p class="mine_text">Мой текст</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="other_text">Сужой текст</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="input_message" style="">
            <div style="width: 75%; float: left; height: 100%; position:relative; vertical-align: center">
                <input class="text-field__input" type="text" id="sender_text" style="width: 100%; float: left; margin-left: 2%; position: absolute; top: 50%; transform: translate(0, -50%)" placeholder="Новое сообщение...">
            </div>
            <div style="width: 15%; float: right; height: 100%">
                <svg xmlns="http://www.w3.org/2000/svg" width="80%" height="80%" viewBox="0 0 24 24" style="fill: rgba(186, 196, 245, 1);transform: ;msFilter:; margin-top: 10%"><path d="m21.426 11.095-17-8A.999.999 0 0 0 3.03 4.242L4.969 12 3.03 19.758a.998.998 0 0 0 1.396 1.147l17-8a1 1 0 0 0 0-1.81zM5.481 18.197l.839-3.357L12 12 6.32 9.16l-.839-3.357L18.651 12l-13.17 6.197z"></path></svg>
            </div>
        </div>
    </div>
</div>



<script>
    $(".one_people").on('click', function () {
        $(".one_people").removeClass('selected_people');
        $(this).addClass('selected_people');
    });
    function open_messenger(){
        if (document.getElementById('messenger').style.display === 'none'){
            document.getElementById('messenger').style.display = 'block'
            document.getElementById('messenger_mini').style.display = 'none'
        }else {
            document.getElementById('messenger').style.display = 'none'
            document.getElementById('messenger_mini').style.display = ''
        }
    }
    var input_people = document.getElementById('search_people')
    input_people.oninput = function() {
        search_people()
    };
    function search_people(){
        var search_text = new RegExp(document.getElementById('search_people').value, 'i');
        var all_tagnames = document.getElementsByClassName('nick_name')
        if (all_tagnames){
            for (var tagname of all_tagnames){
                if (tagname.textContent.match(search_text)){
                    tagname.parentNode.parentNode.parentNode.parentNode.style.display = ''
                }else {
                    tagname.parentNode.parentNode.parentNode.parentNode.style.display = 'none'
                }
            }
        }
    }
</script>

<style>
    .mine_text{
        width: auto;
        max-width: 60%;
        float: right;
        background: #E3E6EA;
        border-radius: 10px;
        padding: 10px;
        margin: 0px;
    }
    .other_text{
        width: auto;
        max-width: 60%;
        float: left;
        background: #E3E6EA;
        border-radius: 10px;
        padding: 10px;
        margin: 0px;
    }
    .input_message{
        position: absolute;
        bottom: 0px;
        width: 100%;
        height: 60px;
        border-top: 1px solid grey;
    }
    .text_chat{
        overflow-y: auto;
        top: 0;
        position: absolute;
        width: 100%;
        height: calc(100% - 60px);
    }
    .table_one_people{
        width: 100%;
        display: table;
        table-layout: fixed;
        height: 100%;
        vertical-align: middle;
    }
    .table_one_people:hover{
        background: lightgrey;
    }
    .table_one_people tr:first-child td:first-child{
        font-weight: bold;
        width: 80%;
    }
    .table_one_people tr:first-child td:last-child{
        width: 20%;
        text-align: center;
    }
    .table_one_people tr:last-child td:last-child{
        font-weight: bolder;
        padding-right: 10px;
    }
    .table_one_people tr td:first-child{
        padding-left: 5px;
        text-align: left;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .table_one_people tr td:last-child{
        text-align: right;
    }
    .selected_people{
        border-left: 4px solid #0079c2;
        background: white;
    }
    .one_people{
        height: 60px;
        border-top: 1px solid grey;
        border-bottom: 1px solid grey;
        width: calc(100% - 4px);
    }
    .close_messenger{
        border-radius: 0px 10px 0px 0px;
        background: red;
        position: absolute;
        right: 0;
        top: 0;
        height: 50px;
        width: 50px;
    }
    .chat_div{
        position: absolute;
        bottom: 0;
        right: 0;
        height: calc(100% - 50px);
        width: 70%;
        border-radius: 0px 0px 10px 0px;
        overflow-y: hidden;
        background: white;
    }
    .name_recipient{
        position: absolute;
        top: 0;
        right: 0;
        width: 70%;
        height: 50px;
        min-height: 50px;
        border-radius: 0px 10px 0px 0px;
        background: #0079C2;
    }
    .search_input{
        /*position: ap;*/
        margin-top: 10px;
        margin-bottom: 10px;
        width: 80%;
        margin-left: 5%;
        top: 10px;
    }
    .search_block{
        position: absolute;
        width: 30%;
        top: 0;
        height: 55px;
    }
    .people{
        position: absolute;
        width: 30%;
        height: calc(100% - 55px);
        overflow-y: auto;
        overflow-x: hidden;
        bottom: 0;
    }
    .people_block{
        display: inline-block;
        vertical-align: top;
        margin-top: 0px;
        margin-left: 0px;
        width: 30%;
        height: 100%;
        background: #E3E6EA;
        border-radius: 10px 0px 0px 10px;
        overflow-y: auto;
        overflow-x: hidden;
        border-right: 1px solid darkgray;

    }
    .messenger{
        border: 1px solid black;
        width: 40%;
        height: 80%;
        border-radius: 10px;
        position: absolute;
        right: 15px;
        top: 10%;
        background: white;
        z-index: 888;
        box-shadow: 10px 5px 45px black;
        display: none;
    }
    .messenger_mini{
        width: 80px;
        height: 80px;
        position: absolute;
        bottom: 10px;
        right: 10px;
        z-index: 889;
    }
    .messenger_mini svg{
        height: 100%;
        width: 100%;
        opacity: 0.5;
    }

    .text-field__input {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-family: inherit;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #bdbdbd;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .text-field__input::placeholder {
        color: #212529;
        opacity: 0.4;
    }

    .text-field__input:focus {
        color: #212529;
        background-color: #fff;
        border-color: #bdbdbd;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(158, 158, 158, 0.25);
    }

    .text-field__input:disabled,
    .text-field__input[readonly] {
        background-color: #f5f5f5;
        opacity: 1;
    }
</style>
