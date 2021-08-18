function dropdown_sort_changed(div){
    var params = JSON.parse(div.value);

    set_multiple_params_in_current_array(params);
}

function set_multiple_params_in_current_array(param_to_val_arr){
    var url = new URL(window.location.href);
    var search_params = url.searchParams;
    
    var param;
    var value;
    for (param in param_to_val_arr){
        value = param_to_val_arr[param];
        
        if (value == ""){
            search_params.delete(param);
        } else {
            search_params.set(param, value);
        }
    }

    url.search = search_params.toString();
    
    var new_url = url.toString();
    window.location.href = new_url;
}