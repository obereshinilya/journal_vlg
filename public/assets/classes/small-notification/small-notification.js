class SmallNotification{
    #closer;
    constructor(header_text, text, color='green') {
        this.body=document.createElement('div');
        this.body.className='small-notification';

        var h4=document.createElement('h4');
        h4.innerText=header_text;
        var txt=document.createElement('a');
        txt.innerText=text;

        this.body.style.background=color;

        this.#closer=document.createElement('span');
        this.#closer.className='small-notification-closer';
        this.#closer.addEventListener('click', ()=>{
            this.close();
        })

        this.body.appendChild(this.#closer);

        this.body.appendChild(h4); this.body.appendChild(txt);

        document.body.appendChild(this.body);
    }

    close(){
        this.body.style.display='none';
        this.body.remove();
    }
}

