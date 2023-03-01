<div class="tr-context-menu" data-row-id="">
    <ul>
        <li id="delete-row-from-table-btn">Удалить строку</li>
    </ul>
</div>

<div id="confirm-delete-row-modal">
    <form action="" method="post" onsubmit="return onconfirm_delete()">
        <div class="form-group-all-width">
            <span>Подтвердите удаление строки</span>
        </div>

        <div class="form-group-half-width onleft">
            <input type="submit" id="delete-row-confirm-btn" style="background-color: #c56667; color: white; text-transform: uppercase" value="Удалить">
        </div>
        <div class="form-group-half-width onright">
            <input type="button" id="cancel-delete-row-btn" value="Отменить">
        </div>
    </form>

</div>

<style>
    .tr-context-menu{
        display: none;
        position: fixed;
        z-index: 99999;
        top: 0;
        left: 0;
        box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.54);
        background-color: white;
        border-radius: 5px;
    }
    .tr-context-menu ul {
        padding: 0;
        margin: 0;
    }
    .tr-context-menu ul li {
        cursor: pointer;
        list-style: none;
        padding: 10px 12px;
        margin: 0;
        border-bottom: 1px solid #BFE2FF;
    }
    .tr-context-menu ul li:last-child {
        border-bottom: 1px solid transparent;
    }
    .tr-context-menu ul li:hover {
        background-color: #BFE2FF;
        border-radius: 5px;
    }
</style>

<script>

    var confirm_delete_row_modal=null;

    $(document).ready(function(){
        var confirm_delete_row_modal_content=document.getElementById('confirm-delete-row-modal')
        confirm_delete_row_modal=new ModalWindow('Подтвердите действие', confirm_delete_row_modal_content, AnimationsTypes['slideIn'])

        $('#cancel-delete-row-btn').click(function () {
            confirm_delete_row_modal.close()
        });

        // $('#delete-row-confirm-btn').click(function(){
        //     var row_id=$('.tr-context-menu').attr('data-row-id');
        //     $.ajax({
        //         url:delete_query,
        //         data:{'row_id':row_id},
        //         type:'POST',
        //         success:(res)=>{
        //             if (Boolean(res)===true){
        //                 $(`tr[data-id=${row_id}]`).remove()
        //                 confirm_delete_row_modal.close();
        //                 // update_side_tree();
        //                 // get_main_table_data($('#itemInfoTable').attr('data-id'));
        //             }
        //         }
        //     })
        // });
    });
    var contextMenu=$('.tr-context-menu');

    $(document).on('click', function () {
        contextMenu.hide();
    });

   function link_contextmenu_to_tr(){
        $('#itemInfoTable').find('tbody').find('tr').on('contextmenu', function(event){
            event.preventDefault();
            $(contextMenu).attr('data-row-id', $(event.currentTarget).attr('data-id'));
            contextMenu.css({top: event.clientY + 'px', left: event.clientX + 'px' });
            contextMenu.show();
        })
    }



    function link_delete_query(delete_query){
        $('#confirm-delete-row-modal>form').attr('action', delete_query);
        $('#delete-row-from-table-btn').click(function(event){
            confirm_delete_row_modal.show();
        });
    }

    function onconfirm_delete(){
        var row_id=$('.tr-context-menu').attr('data-row-id');
        $.ajax({
            url:$('#confirm-delete-row-modal>form').attr('action'),
            data:{'row_id':row_id},
            type:'POST',
            success:(res)=>{
                if (Boolean(res)===true){
                    $(`tr[data-id=${row_id}]`).remove()
                    // update_side_tree();
                    // get_main_table_data($('#itemInfoTable').attr('data-id'));
                }
            }
        })
        confirm_delete_row_modal.close();
        return false;
    }
</script>
