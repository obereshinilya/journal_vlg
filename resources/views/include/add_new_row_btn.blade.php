

<script src="{{asset('assets/libs/tooltip/popper.min.js')}}"></script>
<script src="{{asset('assets/libs/tooltip/tippy-bundle.umd.min.js')}}"></script>


<div id="add-new-row">
    <img class="add-plus-icon" src="{{asset('assets/images/add_plus_icon.svg')}}">
    <img class="delete-minus-icon" src="{{asset('assets/images/minus-btn-icon.png')}}" hidden>
</div>


<script>
    $(document).ready(function(){
        tippy('.add-plus-icon', {
            content:'Добавить',
            delay: 500, // ms
        })
    })

</script>

<style>
.add-plus-icon{
    width:38px;
    height:38px;
    background-color: #d8d8d8;
    border-radius: 5px;
    position: absolute;
    bottom:0;
    left:0;
    transition: 0.25s;
}
.add-plus-icon:hover,
.add-plus-icon:focus {
    background-color: #a7a7a7;
}

#add-new-row{
    float:left;
    height: 56px;
    position:relative;
}

.delete-minus-icon{
    width:32px;
    height:32px;
    background-color: #d8d8d8;
    border-radius: 5px;
    position: absolute;
    bottom:0;
    left:50px;
    transition: 0.25s;
    padding: 3px;
}

.delete-minus-icon:hover,
.delete-minus-icon:focus {
    background-color: #a7a7a7;
}
</style>
