
<DIV CLASS="side_menu" id="side_menu">
    <img id="show-hide-side-menu-btn" src="<?php echo e(asset('assets/images/left-arrow-icon.png')); ?>">
</DIV>

<style>
    .content{
        width: calc(80% - 40px);
    }

    #show-hide-side-menu-btn{
        width:30px;
        height:30px;
        position: absolute;
        bottom:0;
        right:0;
        transition: 0.25s;
        margin: 10px;
    }

</style>

<script>
    $(document).ready(function (){

        $( "#side_menu" ).width('12%')
        $( "#side_menu" ).resizable({
            handles: 'e'
        });
        $('#side_menu').resize(function(){
            $('#main_content').width($(document.body).width()-$('#side_menu').width()-45)
        })

        $(window).resize(function(){
            $('#main_content').width($(document.body).width()-$("#side_menu").width()-45);
            // $('#side_menu').height($(document.body).height());
        })

        $('#main_content').width($(document.body).width()-$('#side_menu').width()-45)


        var tableItems=[];

        $.ajax({
            url: '/getsidetree',
            method: 'GET',
            dataType: 'html',
            success: function(data){
                var side_tree=document.createElement('div');
                side_tree.className='side_tree';
                side_tree.innerHTML=data;
                document.getElementById('side_menu').insertBefore(side_tree, document.getElementById('show-hide-side-menu-btn'))
                tableItems=$('.tableItem').click(ItemClick);
                // tableItems[0].className+=' choiced'   //чтоб по дефолту не выделял первый элемент
                var content_header=document.getElementById('content-header');
                // content_header.innerHTML=`<h3>${header_content} ${tableItems[0].textContent}</h3>` //чтоб не писал по дефолту первый элемент
                content_header.innerHTML=`<h3>${header_content}</h3>`
            }
        })


        function ItemClick (event){
            var target=null;
            if (event.target.className==='treePlusIcon'){ //если нажали на плюсик
                if (event.target.style.webkitTransform===''){   //если плюс не повернут
                    event.target.style.webkitTransform = "rotate(45deg)";
                    event.target.style.transform="rotate(45deg)";
                } else{   //если повернут
                    event.target.style.webkitTransform='';
                    event.target.style.transform=''
                }
                let childrenContainer = event.target.parentNode.parentNode.querySelector('ul');
                if (childrenContainer)
                    childrenContainer.hidden=!childrenContainer.hidden;
                target=event.target.parentNode;
            } else{   //если нажали на объект
                target=event.target;
                if (!target.className.includes('choiced')){ //если не был выбран
                    $('.tableItem').removeClass('choiced');
                    target.className+=' choiced';
                } else{                                   //если был выбран
                    $(target).removeClass('choiced');
                }
                //обозначим хедер
                var content_header=document.getElementById('content-header');
                //работа с отображаемыми сигналами
                var choiced_item = document.getElementsByClassName('tableItem choiced')[0] //выбранный объект
                if (choiced_item){ //если объект выбран
                    content_header.innerHTML=`<h3>${header_content} ${target.textContent}</h3>`
                    var parentId = choiced_item.getAttribute('data-id')
                    var name_parent = choiced_item.textContent
                    //для создания нового объекта
                    var for_create_object = document.getElementById('child_name')
                    if (for_create_object){
                        document.getElementsByClassName('parent')[0].value = name_parent
                        document.getElementsByClassName('parent')[1].value = name_parent
                        document.getElementById('parentId_buff').textContent = parentId
                    }
                    //Запрос на получение id строк, которые скроем
                    $.ajax({
                        url:'/get_parent/'+parentId,
                        type:'GET',
                        success:(res)=>{
                            console.log(res)
                            var un_visible_rows = Object.values(res)
                            var all_trs = document.querySelectorAll('tbody tr')
                            for (var one_tr of all_trs){
                                if (un_visible_rows.includes(Number (one_tr.getAttribute('data-id'))) ){ //если данную строку надо скрыть
                                    one_tr.classList.add('hidden_rows')
                                    one_tr.style.display = 'none'
                                } else {    //если строку надо показать
                                    one_tr.classList.remove('hidden_rows')
                                    one_tr.style.display = ''
                                }
                            }
                        }, async: false
                    })
                    //если есть поисковая строка
                    try {
                        search_object() ///функция из search_row.blade
                    }catch (e){

                    }
                } else {   //если объект не выбран
                    content_header.innerHTML=`<h3>${header_content}</h3>`
                    var all_tr = document.querySelectorAll('tbody tr')
                    for (var i of all_tr){
                        i.style.display = ''
                        i.classList.remove('hidden_rows')
                    }
                    try {
                        search_object() ///функция из search_row.blade
                    }catch (e){

                    }
                }
            }
        }


        $('#show-hide-side-menu-btn').click(function(event){
            if (event.target.style.webkitTransform===''){
                event.target.style.webkitTransform = "rotate(180deg)";
                event.target.style.transform="rotate(180deg)";


                $('.side_tree').hide();
                $('#side_menu').css({
                    "min-width": "50px",
                });
                $('#side_menu').width('50px');
                $('#main_content').width($(document.body).width()-$('#side_menu').width()-75)
                $('#side_menu').attr('class', 'side_menu');

                $('#side_menu').resizable('disable');

            }
            else{
                event.target.style.webkitTransform='';
                event.target.style.transform=''

                $('.side_tree').show();
                $('#side_menu').css({
                    "min-width": "200px",
                });
                $('#side_menu').width('200px');
                $('#main_content').width($(document.body).width()-$('#side_menu').width()-45);
                $('#side_menu').attr('class', 'side_menu ui-resizable');

                $('#side_menu').resizable('enable');
            }
        })

    })


</script>


<style>
    .side_tree {
        margin-left: -30px;
        height: 95%;
        overflow-y: auto;
    }


    .side_tree ul {
        list-style-position: inside;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    .side_tree li {
        list-style-type: none;
        position: relative;
        padding: 5px 5px 0 5px;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    .side_tree li::after{
        content: '';
        position: absolute; top: 0;

        width: 3%; height: 50px;
        right: auto; left: -2.5%;

    }

    .tableItem{
        border: 1px solid #ccc;
        padding: 5px 10px;
        text-decoration: none;
        color: #666;
        font-family: arial, verdana, tahoma;
        font-size: 11px;
        display: inline-block;
        width: 90%; height: 20px;
        background-color: white;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .tableItem:hover, .tableItem:hover+ul li .tableItem
    {
        background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
    }

    .choiced{
        background-color: #c8e4f8;
    }

    .treePlusIcon{
        width:16px;
        height:16px;
        display:inline-block;
        vertical-align:bottom;
        float: right;
        background-color: rgba(0,0,0,0);
        -webkit-transition: all 0.2s; transition: all 0.2s;
    }


</style>
