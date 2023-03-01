function link_to_changeable(url_link, classname='changeable_td') {

    if (!document.getElementById(`confirm-change-cell-modal-${classname}`)){
        $( `<div id=\"confirm-change-cell-modal-${classname}\" style=\"display: none\">\n` +
            "        <div class=\"form-group-all-width\">\n" +
            "            <span>Подтвердите изменение значения</span>\n" +
            "        </div>\n"+
            "        <div class=\"form-group-half-width onleft\">\n" +
            `            <input type=\"submit\" id=\"change-cell-confirm-btn-${classname}\" style=\"background-color: #c56667; color: white; text-transform: uppercase\" value=\"Изменить\">\n` +
            "        </div>\n" +
            "        <div class=\"form-group-half-width onright\">\n" +
            `            <input type=\"button\" id=\"cancel-change-cell-btn-${classname}\" value=\"Отменить\">\n` +
            "        </div>\n" +
            "</div>"
        ).appendTo("body");
    }


    var confirm_cahnge_cell_modal_content=document.getElementById(`confirm-change-cell-modal-${classname}`)
    var confirm_cahnge_cell_modal=null;

    if (typeof ModalWindow === 'undefined') {
        // Adding the script tag to the head as suggested before
        var head = document.head;
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'assets/libs/modal-windows/modal_windows.js';

        var stylesheet=document.createElement('link');
        stylesheet.rel='stylesheet'
        stylesheet.type='text/css'
        stylesheet.href='assets/libs/modal-windows/modal_windows.css'
        script.onreadystatechange  = function (){
            confirm_cahnge_cell_modal=new ModalWindow('Подтвердите действие', confirm_cahnge_cell_modal_content, AnimationsTypes['slideIn'])
        };
        script.onload  = function (){
            confirm_cahnge_cell_modal=new ModalWindow('Подтвердите действие', confirm_cahnge_cell_modal_content, AnimationsTypes['slideIn'])
        };

        // Fire the loading
        head.appendChild(script);
        head.appendChild(stylesheet);
    }
    else{
        confirm_cahnge_cell_modal=new ModalWindow('Подтвердите действие', confirm_cahnge_cell_modal_content, AnimationsTypes['slideIn'])
    }

    $(`#confirm-change-cell-modal-${classname}`).removeAttr('style')




    var text = null;
    var oldText=null;


    $(`.${classname}`).blur(function (event) {
        // console.log(event.currentTarget.tagName)
        if (event.currentTarget.tagName==='DIV'){
            // if (event.currentTarget.getAttribute('data-type')==='select'){
            //
            // }
        }
        else {
            // console.log('ypypy',text)
            $(this).text(text);
            text = null;
        }
    });

    $(`.${classname}`).focus(function (event) {
        if (event.currentTarget.tagName!=='DIV'){
            text = $(this).text();
        }
    });

    $(`.${classname}.cs-select>.cs-options`).click( function(event){
        var value=event.currentTarget.querySelector('.cs-selected').getAttribute('data-value');
        var id=$(event.currentTarget.parentNode.parentNode.parentNode).attr('data-id');
        var column=$(event.currentTarget.parentNode).attr('data-column');

        var data={
            'id':id,
            'column':column,
            'value':value
            // 'type':value
        }


        $.ajax({
            url:url_link,
            type:'POST',
            data: data,
            success:(data)=>{
                console.log(data)
                if (data[0]===true){
                    // text = event.target.textContent;
                    //Успех
                }
                else{
                    //Известить об ошибке
                    console.log(data)
                }
            },
            async: true
        });
    })

    $(`.${classname}`).click((event)=>{
        // console.log(event.currentTarget)
        if (event.currentTarget.tagName==='DIV'){

        }
        else{
            oldText = event.target.textContent;
            // console.log(oldText)
        }
    });

    var datatype=null;
    var data=null;
    var textContent=null;

    $(`.${classname}`).keypress(function (event) {
        // console.log(event.keyCode)
        datatype=$(this).attr('data-type');
        // text=event.target.textContent;
        // console.log(textContent)

        if (event.keyCode === 13) {
            if ($(event.target).attr('sutki') != undefined){
                data={
                    'value':$(event.target).text(),
                    'date':$(event.target).attr('date'),
                    'hfrpok':$(event.target).attr('hfrpok'),
                    'type':'sutki',
                }
                // console.log(data)

            } else {
                data={
                    'column':$(event.target).attr('data-column'),
                    'value':$(event.target).text()
                }
            }


            if (event.target.hasAttribute('data-row-id')){
                data['id']=$(event.target).attr('data-row-id')
            }
            else{
                data['id']=$(event.target.parentNode.parentNode).attr('data-id')
            }

            $(`#change-cell-confirm-btn-${classname}`).click(()=>{
                $.ajax({
                    url:url_link,
                    type:'POST',
                    data: data,
                    success:(res)=>{
                        if (res[0]===true){
                            // text = textContent;
                            // console.log('1234', text, event.target)
                            event.target.blur();
                            $(this).css({
                                'background-color': 'indianred'
                            })
                        }
                        else{
                            //Известить об ошибке
                            console.log(res)
                        }

                        confirm_cahnge_cell_modal.close();
                        $(`#change-cell-confirm-btn-${classname}`).unbind('click');
                    }
                })
            });
            $(`#cancel-change-cell-btn-${classname}`).click(()=> {
                confirm_cahnge_cell_modal.close()
                event.target.textContent=oldText;
                console.log('yoyoy', oldText)
                $(`#cancel-change-cell-btn-${classname}`).unbind('click');
            });
            text=event.target.textContent;
            confirm_cahnge_cell_modal.show()

            return;
        }

        var x = event.charCode || event.keyCode;
        if (datatype==='float'){
            if (isNaN(String.fromCharCode(event.which)) && (String.fromCharCode(event.which) !='.') || (String.fromCharCode(event.which) ==='.' && event.currentTarget.innerText.includes('.'))) {
                event.preventDefault();
            }
        }
        else if (datatype==='int'){
            if (isNaN(String.fromCharCode(event.which))) {
                event.preventDefault();
            }
        }

    })

    // function onconfirm_change(){
    //     $.ajax({
    //         url:url_link,
    //         type:'POST',
    //         data: data,
    //         success:(res)=>{
    //             if (res[0]===true){
    //                 text = textContent;
    //                 // console.log('1234', text)
    //             }
    //             else{
    //                 //Известить об ошибке
    //                 console.log(res)
    //             }
    //         }
    //     })
    //     return false;
    // }

    $(`input.${classname}`).on('change', function(event){
        var data={
            'id':$(event.target.parentNode.parentNode).attr('data-id'),
            'column':$(event.target).attr('data-column'),
            'value':$(event.target).val()
        }
        // console.log(data);

        $.ajax({
            url:url_link,
            type:'POST',
            data: data,
            success:(data)=>{
                if (data[0]===true){
                    text = event.target.textContent;
                }
                else{
                    //Известить об ошибке
                    console.log(data)
                }
            }
        }, )
        event.target.blur();
    })
}



function link_to_create(url_link, classname='create_td') {
    // if (!document.getElementById("confirm-change-cell-modal")){

    if (!document.getElementById(`confirm-change-cell-modal-${classname}`)){
        $( `<div id=\"confirm-change-cell-modal-${classname}\" style=\"display: none\">\n` +
            "        <div class=\"form-group-all-width\">\n" +
            "            <span>Подтвердите изменение значения</span>\n" +
            "        </div>\n"+
            "        <div class=\"form-group-half-width onleft\">\n" +
            `            <input type=\"submit\" id=\"change-cell-confirm-btn-${classname}\" style=\"background-color: #c56667; color: white; text-transform: uppercase\" value=\"Изменить\">\n` +
            "        </div>\n" +
            "        <div class=\"form-group-half-width onright\">\n" +
            `            <input type=\"button\" id=\"cancel-change-cell-btn-${classname}\" value=\"Отменить\">\n` +
            "        </div>\n" +
            "</div>"
        ).appendTo("body");
    }


    var confirm_cahnge_cell_modal_content=document.getElementById(`confirm-change-cell-modal-${classname}`)
    var confirm_cahnge_cell_modal=null;
    // console.log(typeof(ModalWindow))

    if (typeof ModalWindow === 'undefined') {
        // Adding the script tag to the head as suggested before
        var head = document.head;
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'assets/libs/modal-windows/modal_windows.js';

        var stylesheet=document.createElement('link');
        stylesheet.rel='stylesheet'
        stylesheet.type='text/css'
        stylesheet.href='assets/libs/modal-windows/modal_windows.css'
        script.onreadystatechange  = function (){
            confirm_cahnge_cell_modal=new ModalWindow('Подтвердите действие', confirm_cahnge_cell_modal_content, AnimationsTypes['slideIn'])
        };
        script.onload  = function (){
            confirm_cahnge_cell_modal=new ModalWindow('Подтвердите действие', confirm_cahnge_cell_modal_content, AnimationsTypes['slideIn'])
        };

        // Fire the loading
        head.appendChild(script);
        head.appendChild(stylesheet);
    }
    else{
        confirm_cahnge_cell_modal=new ModalWindow('Подтвердите действие', confirm_cahnge_cell_modal_content, AnimationsTypes['slideIn'])
    }

    $(`#confirm-change-cell-modal-${classname}`).removeAttr('style')




    var text = null;
    var oldText=null;


    $(`.${classname}`).blur(function (event) {
        // console.log(event.currentTarget.tagName)
        if (event.currentTarget.tagName==='DIV'){
            // if (event.currentTarget.getAttribute('data-type')==='select'){
            //
            // }
        }
        else {
            // console.log('ypypy',text)
            $(this).text(text);
            text = null;
        }
    });

    $(`.${classname}`).focus(function (event) {
        if (event.currentTarget.tagName!=='DIV'){
            text = $(this).text();
        }
    });

    $(`.${classname}.cs-select>.cs-options`).click( function(event){
        var value=event.currentTarget.querySelector('.cs-selected').getAttribute('data-value');
        var id=$(event.currentTarget.parentNode.parentNode.parentNode).attr('data-id');
        var column=$(event.currentTarget.parentNode).attr('data-column');

        var data={
            'id':id,
            'column':column,
            'value':value
        }
        // console.log(data);

        $.ajax({
            url:url_link,
            type:'POST',
            data: data,
            success:(data)=>{


                if (data[0]===true){
                    // text = event.target.textContent;
                    //Успех
                }
                else{
                    //Известить об ошибке
                    console.log(data)
                }
            },
            async: true
        });
    })

    $(`.${classname}`).click((event)=>{
        // console.log(event.currentTarget)
        if (event.currentTarget.tagName==='DIV'){

        }
        else{
            oldText = event.target.textContent;
            console.log(oldText)
        }
    });

    var datatype=null;
    var data=null;
    var textContent=null;

    $(`.${classname}`).keypress(function (event) {
        // console.log(event.keyCode)
        datatype=$(this).attr('data-type');
        // text=event.target.textContent;
        // console.log(textContent)

        if (event.keyCode === 13) {
            if ($(event.target).attr('sutki') != undefined){
                data={
                    'value':$(event.target).text(),
                    'date':$(event.target).attr('date'),
                    'hfrpok':$(event.target).attr('hfrpok'),
                    'type':'sutki',
                }
                // console.log(data)
            } else {
                data={
                    'value':$(event.target).text(),                //новое значение
                    'number_column':$(event.target).attr('numbercolumn'),                //номер колонки
                    'hfrpok':$(event.target).attr('hfrpok'),               //hfrpok параметра
                    'day':$(event.target).attr('day'),                //дата формата 2022-01-17 10:00
                    'type':'hour',

                }
            }

// console.log(data)
//             if (event.target.hasAttribute('data-row-id')){
//                 data['id']=$(event.target).attr('data-row-id')
//             }
//             else{
//                 data['id']=$(event.target.parentNode.parentNode).attr('data-id')
//             }

            $(`#change-cell-confirm-btn-${classname}`).click(()=>{
                $.ajax({
                    url:url_link,
                    type:'POST',
                    data: data,
                    success:(res)=>{
                        // console.log(res)
                        if (res==true){
                            // text = textContent;
                            // console.log('1234', text, event.target)
                            event.target.blur();
                            $(this).css({
                                'background-color': 'indianred'
                            })
                        }
                        else{
                            //Известить об ошибке
                            console.log(res)
                        }

                        confirm_cahnge_cell_modal.close();
                        $(`#change-cell-confirm-btn-${classname}`).unbind('click');
                    }
                })
            });
            $(`#cancel-change-cell-btn-${classname}`).click(()=> {
                confirm_cahnge_cell_modal.close()
                event.target.textContent=oldText;
                console.log('yoyoy', oldText)
                $(`#cancel-change-cell-btn-${classname}`).unbind('click');
            });
            text=event.target.textContent;
            confirm_cahnge_cell_modal.show()

            return;
        }

        var x = event.charCode || event.keyCode;
        if (datatype==='float'){
            if (isNaN(String.fromCharCode(event.which)) && (String.fromCharCode(event.which) !='.') || (String.fromCharCode(event.which) ==='.' && event.currentTarget.innerText.includes('.'))) {
                event.preventDefault();
            }
        }
        else if (datatype==='int'){
            if (isNaN(String.fromCharCode(event.which))) {
                event.preventDefault();
            }
        }

    })

    // function onconfirm_change(){
    //     $.ajax({
    //         url:url_link,
    //         type:'POST',
    //         data: data,
    //         success:(res)=>{
    //             if (res[0]===true){
    //                 text = textContent;
    //                 // console.log('1234', text)
    //             }
    //             else{
    //                 //Известить об ошибке
    //                 console.log(res)
    //             }
    //         }
    //     })
    //     return false;
    // }

    $(`input.${classname}`).on('change', function(event){
        var data={
            'id':$(event.target.parentNode.parentNode).attr('data-id'),
            'column':$(event.target).attr('data-column'),
            'value':$(event.target).val()
        }
        // console.log(data);

        $.ajax({
            url:url_link,
            type:'POST',
            data: data,
            success:(data)=>{
                if (data[0]===true){
                    text = event.target.textContent;
                }
                else{
                    //Известить об ошибке
                    console.log(data)
                }
            }
        }, )
        event.target.blur();
    })
}



function link_to_create_min(url_link, classname='create_td') {
    // if (!document.getElementById("confirm-change-cell-modal")){

    if (!document.getElementById(`confirm-change-cell-modal-${classname}`)){
        $( `<div id=\"confirm-change-cell-modal-${classname}\" style=\"display: none\">\n` +
            "        <div class=\"form-group-all-width\">\n" +
            "            <span>Подтвердите изменение значения</span>\n" +
            "        </div>\n"+
            "        <div class=\"form-group-half-width onleft\">\n" +
            `            <input type=\"submit\" id=\"change-cell-confirm-btn-${classname}\" style=\"background-color: #c56667; color: white; text-transform: uppercase\" value=\"Изменить\">\n` +
            "        </div>\n" +
            "        <div class=\"form-group-half-width onright\">\n" +
            `            <input type=\"button\" id=\"cancel-change-cell-btn-${classname}\" value=\"Отменить\">\n` +
            "        </div>\n" +
            "</div>"
        ).appendTo("body");
    }


    var confirm_cahnge_cell_modal_content=document.getElementById(`confirm-change-cell-modal-${classname}`)
    var confirm_cahnge_cell_modal=null;
    // console.log(typeof(ModalWindow))

    if (typeof ModalWindow === 'undefined') {
        // Adding the script tag to the head as suggested before
        var head = document.head;
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'assets/libs/modal-windows/modal_windows.js';

        var stylesheet=document.createElement('link');
        stylesheet.rel='stylesheet'
        stylesheet.type='text/css'
        stylesheet.href='assets/libs/modal-windows/modal_windows.css'
        script.onreadystatechange  = function (){
            confirm_cahnge_cell_modal=new ModalWindow('Подтвердите действие', confirm_cahnge_cell_modal_content, AnimationsTypes['slideIn'])
        };
        script.onload  = function (){
            confirm_cahnge_cell_modal=new ModalWindow('Подтвердите действие', confirm_cahnge_cell_modal_content, AnimationsTypes['slideIn'])
        };

        // Fire the loading
        head.appendChild(script);
        head.appendChild(stylesheet);
    }
    else{
        confirm_cahnge_cell_modal=new ModalWindow('Подтвердите действие', confirm_cahnge_cell_modal_content, AnimationsTypes['slideIn'])
    }

    $(`#confirm-change-cell-modal-${classname}`).removeAttr('style')




    var text = null;
    var oldText=null;


    $(`.${classname}`).blur(function (event) {
        // console.log(event.currentTarget.tagName)
        if (event.currentTarget.tagName==='DIV'){
            // if (event.currentTarget.getAttribute('data-type')==='select'){
            //
            // }
        }
        else {
            // console.log('ypypy',text)
            $(this).text(text);
            text = null;
        }
    });

    $(`.${classname}`).focus(function (event) {
        if (event.currentTarget.tagName!=='DIV'){
            text = $(this).text();
        }
    });

    $(`.${classname}.cs-select>.cs-options`).click( function(event){
        var value=event.currentTarget.querySelector('.cs-selected').getAttribute('data-value');
        var id=$(event.currentTarget.parentNode.parentNode.parentNode).attr('data-id');
        var column=$(event.currentTarget.parentNode).attr('data-column');

        var data={
            'id':id,
            'column':column,
            'value':value
        }
        // console.log(data);

        $.ajax({
            url:url_link,
            type:'POST',
            data: data,
            success:(data)=>{


                if (data[0]===true){
                    // text = event.target.textContent;
                    //Успех
                }
                else{
                    //Известить об ошибке
                    console.log(data)
                }
            },
            async: true
        });
    })

    $(`.${classname}`).click((event)=>{
        // console.log(event.currentTarget)
        if (event.currentTarget.tagName==='DIV'){

        }
        else{
            oldText = event.target.textContent;
            console.log(oldText)
        }
    });

    var datatype=null;
    var data=null;
    var textContent=null;

    $(`.${classname}`).keypress(function (event) {
        // console.log(event.keyCode)
        datatype=$(this).attr('data-type');
        // text=event.target.textContent;
        // console.log(textContent)

        if (event.keyCode === 13) {
            data={
                'value':$(event.target).text(),                //новое значение
                'time':$(event.target).attr('time'),                //время записи
                'hfrpok':$(event.target).attr('hfrpok'),               //hfrpok параметра
            }
// console.log(data)
//             if (event.target.hasAttribute('data-row-id')){
//                 data['id']=$(event.target).attr('data-row-id')
//             }
//             else{
//                 data['id']=$(event.target.parentNode.parentNode).attr('data-id')
//             }

            $(`#change-cell-confirm-btn-${classname}`).click(()=>{
                $.ajax({
                    url:url_link,
                    type:'POST',
                    data: data,
                    success:(res)=>{
                        // console.log(res)
                        if (res==true){
                            // text = textContent;
                            // console.log('1234', text, event.target)
                            event.target.blur();
                            $(this).css({
                                'background-color': 'indianred'
                            })
                        }
                        else{
                            //Известить об ошибке
                            console.log(res)
                        }

                        confirm_cahnge_cell_modal.close();
                        $(`#change-cell-confirm-btn-${classname}`).unbind('click');
                    }
                })
            });
            $(`#cancel-change-cell-btn-${classname}`).click(()=> {
                confirm_cahnge_cell_modal.close()
                event.target.textContent=oldText;
                console.log('yoyoy', oldText)
                $(`#cancel-change-cell-btn-${classname}`).unbind('click');
            });
            text=event.target.textContent;
            confirm_cahnge_cell_modal.show()

            return;
        }

        var x = event.charCode || event.keyCode;
        if (datatype==='float'){
            if (isNaN(String.fromCharCode(event.which)) && (String.fromCharCode(event.which) !='.') || (String.fromCharCode(event.which) ==='.' && event.currentTarget.innerText.includes('.'))) {
                event.preventDefault();
            }
        }
        else if (datatype==='int'){
            if (isNaN(String.fromCharCode(event.which))) {
                event.preventDefault();
            }
        }

    })

    // function onconfirm_change(){
    //     $.ajax({
    //         url:url_link,
    //         type:'POST',
    //         data: data,
    //         success:(res)=>{
    //             if (res[0]===true){
    //                 text = textContent;
    //                 // console.log('1234', text)
    //             }
    //             else{
    //                 //Известить об ошибке
    //                 console.log(res)
    //             }
    //         }
    //     })
    //     return false;
    // }

    $(`input.${classname}`).on('change', function(event){
        var data={
            'id':$(event.target.parentNode.parentNode).attr('data-id'),
            'column':$(event.target).attr('data-column'),
            'value':$(event.target).val()
        }
        // console.log(data);

        $.ajax({
            url:url_link,
            type:'POST',
            data: data,
            success:(data)=>{
                if (data[0]===true){
                    text = event.target.textContent;
                }
                else{
                    //Известить об ошибке
                    console.log(data)
                }
            }
        }, )
        event.target.blur();
    })
}



