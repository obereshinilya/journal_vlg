function save_click(className){
    var selectable_elements = document.getElementsByClassName(className);
    for (element of selectable_elements){
        element.addEventListener('click', function (){
            localStorage.setItem(className, this.id);
        })
    }
}

function get_checked(className){
    var checked_element_id=localStorage.getItem(className);
    if (checked_element_id!=null){
        document.getElementById(checked_element_id).checked=true;
    }
    else{
        document.getElementsByClassName(className)[0].checked=true;
    }
}

function clear_info_about_checked(className){
    localStorage.removeItem(className);
}




