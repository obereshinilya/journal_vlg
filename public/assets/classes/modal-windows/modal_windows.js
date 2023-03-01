//***************Работа с диалоговыми окнами*******************//
// const overlay=document.querySelector('.modal_overlay'),
//     modals=document.querySelectorAll('.dlg-modal'),
//     closers=document.querySelectorAll('[dlg-close]');
// var statuses=[]

class AnimationsTypes{
    static fadeIn='Fade';
    static stickyUp='Sticky';
    static justMe='JustMe';
    static slideIn='Slide';
}

class ModalWindow{
    #modal;
    #content;
    #header;
    #className='dlg-modal';
    #animation_type;
    #overlay;
    #closer;
    #status;
    #view;
    constructor(headertext, content, animation, clickable_overlay=true, closer=true) {
        this.#content=content;
        this.#status=false;
        this.#animation_type=animation;

        this.#overlay=document.createElement('div');
        this.#overlay.className='overlay';

        this.#dlg_formed(headertext, closer)



        if (clickable_overlay){
            this.#overlay.addEventListener('click', ()=>{
                this.close();
            })
        }
    }

    change_header_text(header_text){
        this.#header.innerText=header_text
    }
    change_content(content){
        var dlg_content=document.querySelector('.dlg-content');
        dlg_content.removeChild(dlg_content.lastElementChild)
        dlg_content.appendChild(content)
    }

    close(){
        if (this.#status==true){
            this.#modal.className=this.#className+' '+this.#animation_type+'Out';
            this.#status=false;
        }
    }
    show(){
        if (this.#status==false){
            this.#modal.className=this.#className+' '+this.#animation_type+'In';
            this.#status=true;
        }
    }

    #dlg_formed(headertext, closer){
        this.#modal=document.createElement('div');
        this.#modal.className=this.#className+' '+this.#animation_type+'Out';

        this.#view=document.createElement('div')
        this.#view.className='dlg-view'

        this.#header=document.createElement('div');
        this.#header.className='dlg-header';

        if (closer){
            if (this.#animation_type!=AnimationsTypes.justMe){
                this.#closer=document.createElement('span');
                this.#closer.className='dlg-close-btn';
                this.#header.appendChild(this.#closer);

            }
            else{
                this.#closer=document.createElement('button')
                this.#closer.className='close-btn'
                this.#closer.innerText='Закрыть'
            }
        }


        var header_text=document.createElement('h3');
        header_text.className='dlg-header-text';
        header_text.innerText=headertext;

        this.#header.appendChild(header_text);


        this.#view.appendChild(this.#header);

        var dlgcontent=document.createElement('div')
        dlgcontent.className='dlg-content'
        dlgcontent.appendChild(this.#content)


        this.#view.appendChild(dlgcontent);
        if (closer){
            if (this.#animation_type==AnimationsTypes.justMe){
                this.#view.appendChild(this.#closer)
            }
            this.#closer.addEventListener('click', ()=>{
                this.close();
            })
        }

        this.#modal.appendChild(this.#view)
        document.body.appendChild(this.#modal);
        document.body.appendChild(this.#overlay);
    }
}
