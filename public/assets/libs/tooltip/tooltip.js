// class TooltipTypes{
//     static classicPrompt='classicPrompt'
// }

var TooltipTypes={
    'classicPrompt':'classicPrompt',
    'byRightClick':'byRightClick'
}

class Tooltip{
    //Для старых не работает
    // #content;
    // #id;
    // #type;
    // #form;

    constructor(content, id, bind_link_data, type=TooltipTypes['classicPrompt'], cls=false) {
        this.content=content;
        this.id=id;
        this.type=type.toString();
        this.form=null;
        this.cls=cls;
        this.html_forming();
        this.bind_event(bind_link_data)
    }

    change_content(content){
        this.content=content;
    }

    html_forming(){
        this.form=document.createElement('div');
        this.form.id=this.id;
        this.form.className=this.type;
        this.form.appendChild(this.content);
        if (this.cls===true){
            var link=`.${bind_link_data}`
            var parent=document.getElementsByClassName(link)
        }
        else{
            link=`#${bind_link_data}`
        }

        document.body.appendChild(this.form);
    }

    bind_event(bind_link_data){
        var id=this.id;
        if (this.cls===true){
            var link=`.${bind_link_data}`
        }
        else{
            link=`#${bind_link_data}`
        }
        if (this.type===TooltipTypes['classicPrompt']){
            $(`${link}`).mousemove(function (eventObject){
                $(`#${id}`)
                    .css({
                        "top":eventObject.pageY+5,
                        "left":eventObject.pageX+5
                    })
                    .show();
            }).mouseleave(function () {
                $(`#${id}`).hide()
                    .css({
                        "top":0,
                        "left":0
                    });
            });
        }
        else if (this.type===TooltipTypes['byRightClick']){
            var onLink=false;
            var onContext=false;

            $(`#${id}`).mouseenter(function(){
                onContext=true;
                hide_or_show();
            });

            $(`#${id}`).mouseleave(function(){
                onContext=false;
                hide_or_show();
            });

            $(`${link}`).mouseenter(function (){
                onLink=true;
                hide_or_show();
            })

            $(`${link}`).mouseleave(function(){
                onLink=false;
                hide_or_show();
            });

            $(`${link}`).bind('contextmenu', function (event){
                $(`#${id}`)
                    .css({
                        "top":event.pageY,
                        "left":event.pageX
                    })
                    .show('slow');
                return false;
            });

            function hide_or_show(){
                if (onContext===false && onLink===false){
                    $(`#${id}`).hide('slow');
                }
            }
        }

    }
}
