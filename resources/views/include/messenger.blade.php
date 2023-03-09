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
        <div class="people" id="people">

        </div>
    </div>
    <div class="name_recipient">
        <div class="close_messenger" onclick="open_messenger()">
            <p style="color: white; text-align: center; vertical-align: center; font-weight: bold">&#10006;</p>
        </div>
        <div class="name" style="text-align: center">
            <p style="color: white; width: auto; margin: 5px; font-size: 14px" id="name_people"></p>
            <p style="color: white; width: auto; margin: 0px; font-size: 14px" id="last_visit"></p>
        </div>
    </div>
    <div class="chat_div">
        <div class="text_chat" id="chat_window">
            <table style="width: 100%">
                <tbody class="body_chat" id="body_chat">

                </tbody>
            </table>
        </div>
        <div class="input_message">
            <div style="width: 75%; float: left; height: 100%; position:relative; vertical-align: center">
                <input class="text-field__input" type="text" id="sender_text" style="width: 100%; float: left; margin-left: 2%; position: absolute; top: 50%; transform: translate(0, -50%)" placeholder="Новое сообщение...">
            </div>
            <div style="width: 15%; float: right; height: 100%" onclick="send_messege()">
                <svg xmlns="http://www.w3.org/2000/svg" width="80%" height="80%" viewBox="0 0 24 24" style="fill: rgba(186, 196, 245, 1);transform: ;msFilter:; margin-top: 10%"><path d="m21.426 11.095-17-8A.999.999 0 0 0 3.03 4.242L4.969 12 3.03 19.758a.998.998 0 0 0 1.396 1.147l17-8a1 1 0 0 0 0-1.81zM5.481 18.197l.839-3.357L12 12 6.32 9.16l-.839-3.357L18.651 12l-13.17 6.197z"></path></svg>
            </div>
            <button class="button button1" style="margin-left: 15px; display: none" onclick="set_type_messege('Пожар', this)">Пожар</button>
            <button class="button button1" style="margin-left: 15px; display: none" onclick="set_type_messege('Тренировка', this)">Тренировка</button>
            <button class="button button1" style="margin-left: 15px; display: none" onclick="set_type_messege('Происшествие', this)">Происшествие</button>
            <button class="button button1" style="margin-left: 15px; display: none" onclick="set_type_messege('-', this)">Без отметки</button>
        </div>
    </div>
</div>



<script>

    setInterval(get_chat, 5000)
    function set_type_messege(type, button){
        var id = button.getAttribute('data-id')
        $.ajax({
            url: '/set_type_messege/'+id+'/'+type,
            method: 'GET',
            success: function(res){
                var bottom_div = document.getElementsByClassName('input_message')[0]
                for (var div of bottom_div.getElementsByTagName('div')){
                    div.style.display = ''
                }
                for(var button of bottom_div.getElementsByTagName('button')){
                    button.style.display = 'none'
                    button.style.height = '35px'
                    button.setAttribute('data-id', id)
                }
                create_chat(document.getElementById('name_people').textContent)
            }
        })
    }
    function select_type(p_with_messege){
        var id = p_with_messege.getAttribute('data-id')
        var bottom_div = document.getElementsByClassName('input_message')[0]
        for (var div of bottom_div.getElementsByTagName('div')){
            div.style.display = 'none'
        }
        for(var button of bottom_div.getElementsByTagName('button')){
            button.style.display = 'inline-block'
            button.style.height = '35px'
            button.setAttribute('data-id', id)
        }
    }
    function get_chat(){
        $.ajax({
            url: '/get_people_block',
            method: 'GET',
            success: function(data){
                var name_selected_people = ''
                if (document.getElementsByClassName('selected_people')[0]){
                    name_selected_people = document.getElementsByClassName('selected_people')[0].getElementsByClassName('nick_name')[0].textContent
                }else{
                    name_selected_people = false
                }
                var peoples_div = document.getElementById('people')
                peoples_div.innerText = ''
                for (var people of data){
                    var class_name = ''
                    if (name_selected_people === people['name']){
                        class_name = 'one_people selected_people'
                    }else {
                        class_name = 'one_people'
                    }
                    peoples_div.innerHTML +=
                        `<div class="${class_name}">
                            <table class="table_one_people">
                                <tbody>
                                <tr>
                                    <td class="nick_name">${people['name']}</td>
                                    <td>${people['time_last_messege']}</td>
                                </tr>
                                <tr>
                                    <td>${people['last_messege']}</td>
                                    <td>${people['count_unread_messege']}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>`
                    if (name_selected_people){
                        create_chat(name_selected_people)
                    }else {
                        document.getElementsByClassName('one_people')[0].classList.add('selected_people')
                        create_chat(document.getElementsByClassName('one_people')[0].getElementsByClassName('nick_name')[0].textContent)
                    }
                }
                $(".one_people").on('click', function () {
                    $(".one_people").removeClass('selected_people');
                    $(this).addClass('selected_people');
                    create_chat(this.getElementsByClassName('nick_name')[0].textContent)
                    get_chat()
                });
            }
        })
    }

    function create_chat(name){
        $.ajax({
            url: '/get_chat/'+name,
            method: 'GET',
            success: function(data){
                document.getElementById('last_visit').textContent = data['last_seen']
                document.getElementById('name_people').textContent = name
                delete data['last_seen']
                var body_chat = document.getElementById('body_chat')
                body_chat.innerText = ''
                var keys = Object.keys(data)
                for (var j=0; j<keys.length; j++){
                    var tr = document.createElement('tr')
                    tr.innerHTML = `${keys[j]}`
                    tr.style.textAlign = 'center'
                    tr.style.fontSize = '12px'
                    tr.style.fontWeight = 'bolder'
                    body_chat.appendChild(tr)
                    for (var messege of data[keys[j]]){
                        var tr = document.createElement('tr')
                        if (messege['user_sender'] !== name){ //если пишем мы
                            tr.innerHTML = `<td class="mine_td">
                            <p class="info_mine_p">${messege['type_message']}</p>
                            <p class="info_mine_p">${messege['timestamp'].split(' ')[1].slice(0, -3)}</p>
                            <p class="mine_text" data-id="${messege['id']}" oncontextmenu="select_type(this)">${messege['message']}</p>
                        </td>`
                        }else {    //если пишут нам
                            tr.innerHTML = `<td class="other_td">
                            <p class="other_text">${messege['message']}</p>
                            <p class="info_other_p">${messege['type_message']}</p>
                            <p class="info_other_p">${messege['timestamp'].split(' ')[1].slice(0, -3)}</p>
                        </td>`
                        }
                        body_chat.appendChild(tr)
                    }
                }
                var chat_window = document.getElementById('chat_window')
                chat_window.scrollTo(0, chat_window.scrollHeight)
            }
        })
    }

    function send_messege(){
        if(document.getElementById('sender_text').value){
            var arr = new Map()
            arr.set('text', document.getElementById('sender_text').value)
            arr.set('recipient', document.getElementById('name_people').textContent)
            var data = Object.fromEntries(arr)
            $.ajax({
                url: '/send_messege',
                method: 'POST',
                data: data,
                success: function(res){
                    document.getElementById('sender_text').value = ''
                    create_chat(document.getElementById('name_people').textContent)
                }
            })
        }

    }




    function open_messenger(){
        if (document.getElementById('messenger').style.display === 'none'){
            document.getElementById('messenger').style.display = 'block'
            document.getElementById('messenger_mini').style.display = 'none'
            get_chat()
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

    .body_chat tr td{
        margin-top: 5px;
    }
    .other_td{
        display: flex; align-items: end; justify-content: flex-start;
    }
    .info_other_p{
        width: auto; margin: 0px; font-size: 12px; display: inline-block; float: left; margin-left: 5px
    }
    .mine_td{
        display: flex; align-items: end; justify-content: flex-end
    }
    .info_mine_p{
        width: auto; margin: 0px; font-size: 12px; display: inline-block; float: right; margin-right: 5px
    }
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
