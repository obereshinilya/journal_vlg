
<DIV CLASS="side_menu" id="side_menu">
{{--    <img id="show-hide-side-menu-btn" src="<?php echo e(asset('assets/images/left-arrow-icon.png')); ?>">--}}
</DIV>

<style>
    .content{
        width: calc(80% - 40px);
    }

    #show-hide-side-menu-btn{
        width:30px;
        height:30px;
        /*background-color: #f2f2f2;*/
        /*border-radius: 5px;*/
        position: absolute;
        bottom:0;
        right:0;
        transition: 0.25s;
        margin: 10px;
    }

</style>

<script>
    var click_side_menu_func=null;



    $(document).ready(function (){

        $( "#side_menu" ).width('13%')

        $( "#side_menu" ).resizable({
            handles: 'e'
        });
        $('#side_menu').resize(function(){
            $('#main_content').width($(document.body).width()-$('#side_menu').width()-45)
            try {
                // document.getElementById('tableDiv_yams').style.width = '87%'
            }catch ( e){

            }
        })

        $(window).resize(function(){
            $('#main_content').width($(document.body).width()-$("#side_menu").width()-45);
        })

        $('#main_content').width($(document.body).width()-$('#side_menu').width()-45)


        var tableItems=[];

        $.ajax({
            url: '',
            method: 'GET',
            dataType: '',
            success: function(){
                var side_tree=document.createElement('div');
                side_tree.className='side_tree';
                var new_data = `<div style="margin-right: 10px">
                                    <ul id="jour_smeny">
                                        <li><a id="5" class="tableItem">Журнал смены</a></li>
                                    </ul>
                                    <ul id="svodniy_report_header">
                                        <li><a id="3" class="tableItem">Часовая сводка</a></li>
                                    </ul>
                                    <ul id="svodniy_report_header">
                                        <li><a id="99" class="tableItem">Оперативное состояние скважин</a></li>
                                    </ul>
                                    <ul id="balans_report_header">
                                        <li><a id="4" class="tableItem">Балансовый отчет</a>
                                            <ul>
                                                <li><a id="41" class="tableItem">Год</a></li>
                                                <li><a id="42" class="tableItem">Месяц</a></li>
                                                <li><a id="43" class="tableItem">Сутки</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <ul id="ppr">
                                        <li><a id="8" class="tableItem">График ППР</a></li>
                                    </ul>
                                </div>`
                side_tree.innerHTML=new_data;
                document.getElementById('side_menu').style.width = 'auto'
                document.getElementById('side_menu').insertBefore(side_tree, document.getElementById('show-hide-side-menu-btn'))
                tableItems=$('.tableItem').click(ItemClick);
                var choiced = localStorage.getItem('choiced_id')
                if (choiced){
                    document.getElementById(choiced).className+=' choiced'
                }
            }
        })



        function ItemClick (event){

            var target=null;
            if (event.target.className==='treePlusIcon'){
                if (event.target.style.webkitTransform===''){
                    event.target.style.webkitTransform = "rotate(45deg)";
                    event.target.style.transform="rotate(45deg)";
                } else{
                    event.target.style.webkitTransform='';
                    event.target.style.transform=''
                }

                let childrenContainer = event.target.parentNode.parentNode.querySelector('ul');
                if (childrenContainer)
                    childrenContainer.hidden=!childrenContainer.hidden;
                target=event.target.parentNode;
            }
            else{
                target=event.target;
            }

            if (!target.className.includes('choiced')){
                $('.tableItem').removeClass('choiced');
                target.className+=' choiced';
            } else{
                $(target).removeClass('choiced');
            }

            var vibrano = target.getAttribute('id')
            localStorage.setItem('choiced_id', vibrano);
            if (vibrano === '1'){
                document.location.href = '/gpa_rezhim'
            } else if (vibrano === '21' || vibrano === '22'){
                document.location.href = '/get_gpa_rezhim_report/'+vibrano.slice(1)
            } else if(vibrano === '3'){
                document.location.href = '/open_svodniy'
            }else if(vibrano === '41'){
                document.location.href = '/open_val_year'
            }else if(vibrano === '42'){
                document.location.href = '/open_val_month'
            }else if(vibrano === '43'){
                document.location.href = '/open_val_day'
            }else if(vibrano === '5'){
                document.location.href = '/open_journal_smeny'
            }else if(vibrano === '61'){
                document.location.href = '/open_ter/yams'
            }else if(vibrano === '62'){
                document.location.href = '/open_ter/yub'
            }else if(vibrano === '7'){
                document.location.href = '/open_balans'
            }else if(vibrano === '8'){
                document.location.href = '/open_ppr'
            }else if(vibrano === '9'){
                document.location.href = '/open_astragaz'
            }else if(vibrano === '10'){
                document.location.href = '/report_param_dks'
            }else if(vibrano === '11'){
                document.location.href = '/report_skv'
            }else if(vibrano === '99'){
                document.location.href = '/report_oper_skv_main'
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
                $('#main_content').width($(document.body).width()-$('#side_menu').width()-45)
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
	margin-top: 3px;
	margin-bottom: 3px;
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
