
function register_select_listeners(){
    var selects = document.getElementsByClassName("select_listener");
    // console.log(selects);
    // alert("Here");
    for (var i = 0; i < selects.length; i++){
        selects[i].addEventListener('change', select_changed, false);
    }
}

function test_JSON(text) {
    if (typeof text !== "string") {
        return false;
    }
    try {
        JSON.parse(text);
        return true;
    } catch (error) {
        return false;
    }
}


function select_changed(event){
    var div = event.target;
    var data = [];
    
    var is_JSON = test_JSON(div.value);
    if (is_JSON){
        var json_array = JSON.parse(div.value);
        data = json_array_to_js_array(json_array);
    } else {
        data['value'] = div.value;
    }
    data['select'] = "changed";
    set_url_from_array(data);
}

function json_array_to_js_array(json_array){
    var result = [];
    for(var key in json_array)
        result[key] = json_array[key];
    return result;
}

function set_url_from_array(param_to_val_arr){
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
    console.log(new_url);
    window.location.href = new_url;
}


document.addEventListener('DOMContentLoaded', function() {
    register_select_listeners();
 }, false);


