<div id="search_div" style="width: 100%; height: 100%; margin-bottom: 10px">
    <input class="text-field__input" type="text" id="search_row" style="width: 100%" placeholder="Поиск...">
</div>


<script>
    var input = document.getElementById('search_row')
    input.oninput = function() {
        search_object()
    };
    function search_object(){
        var body_statick = document.getElementsByClassName('tbody_for_search')[0]
        var all_tr_statick = body_statick.getElementsByTagName('tr')
        var search_text = new RegExp(document.getElementById('search_row').value, 'i');

        var body_dynamic = document.getElementsByClassName('tbody_dynamic')[0]
        if (body_dynamic){
            var all_tr_dynamic = body_dynamic.getElementsByTagName('tr')
            for(var i=0; i<all_tr_statick.length; i++){
                if (!all_tr_statick[i].classList.contains('hidden_rows')){
                    if (all_tr_statick[i].getElementsByTagName('td')[0].textContent.match(search_text) || all_tr_statick[i].getElementsByTagName('td')[1].textContent.match(search_text)){
                        all_tr_statick[i].style.display = ''
                        all_tr_dynamic[i].style.display = ''
                    }else {
                        all_tr_statick[i].style.display = 'none'
                        all_tr_dynamic[i].style.display = 'none'
                    }
                }
            }
        }else {
            for(var i=0; i<all_tr_statick.length; i++){
                if (!all_tr_statick[i].classList.contains('hidden_rows')){
                    if (all_tr_statick[i].getElementsByTagName('td')[0].textContent.match(search_text) || all_tr_statick[i].getElementsByTagName('td')[1].textContent.match(search_text)){
                        all_tr_statick[i].style.display = ''
                    }else {
                        all_tr_statick[i].style.display = 'none'
                    }
                }
            }
        }
    }

</script>
<style>
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
