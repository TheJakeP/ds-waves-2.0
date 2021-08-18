function toggle_accordion(div){
    var parent = div.parentElement;
    var list = parent.getElementsByTagName("accordion");
    
    // div.newvalue = "test";
    
    for (var i = 0; i < list.length; i++){
        list[i].classList.toggle("hidden");
        var caret = parent.querySelector("[caret]");
        toggle_caret(caret);
        return; 
    }
}

function toggle_caret(caret){
    var current = caret.getAttribute('src');
    var opposite = caret.getAttribute('opposite');
    caret.src = opposite;
    caret.setAttribute("opposite", current);
}

function clear_params(url){
    history.replaceState({},'', url);
}

// function toggle_in_param_list (open, id){

//     if (open){
//         add_to_param_list(id);
//     } else {
//         remove_from_param_list(id);
//     }

// }

// function add_to_param_list(id){
//     console.log("Removing " + id);

//     const urlParams = new URLSearchParams(window.location.search);
//     var open_list = urlParams.get("accordion");

//     if (open_list == null){
//         open_list = Array();
//         console.log('null');
//     } else {
//         open_list = JSON.parse(decodeURIComponent(open_list));
//     }
    
//     if (open_list.indexOf(id)) {
//         open_list.push(id);
//     }
    
//     console.log(open_list);
//     var arrStr = encodeURIComponent(JSON.stringify(open_list));
//     urlParams.set('accordion', arrStr);

//     console.log(window.location.pathname + "?" + urlParams);
//     window.history.pushState('', '', window.location.pathname + "?" + urlParams);
    
// }

// function remove_from_param_list(id){
//     console.log("Removing " + id);

//     const urlParams = new URLSearchParams(window.location.search);
//     var open_list = urlParams.get("accordion");

//     if (open_list == null){
//         return;
//     } 

//     open_list = JSON.parse(decodeURIComponent(open_list));
//     // params_existing = arrStr = decodeURIComponent(JSON.stringify(params_existing));
//     console.log(typeof open_list);
    
//     var index = open_list.indexOf(id);
//     open_list.splice(index, 1);
    
//     if (open_list.length > 0){
//         var arrStr = encodeURIComponent(JSON.stringify(open_list));
//         urlParams.set('accordion', arrStr);
//     } else {
//         urlParams.delete("accordion");
//     }
    
//     window.history.pushState('', '', window.location.pathname + "?" + urlParams);
// }


//     /*
//     var new_params;
//     var params;

//     if (old_params == null){
//         //No Parameters
//         params = [div.id];
//         urlParams.set('accordion', params);
//     } else {
//         //Params exist

//         if (old_params.includes(div.id)){
//             console.log("duplicated");
//         } else {
//             console.log("Need to Add");
//         }
//         // params = new_params.concat(old_params);

//         // if (params == null){
//         //     urlParams.set('accordion', params);
//         // } else {
//         //     urlParams.delete('accordion');
//         // }

//     }

//     window.history.pushState('', '', window.location.pathname + "?" + urlParams);
//     console.log(window.location.pathname + "?" + urlParams);
// */

// function url_param_exists(param){
//     var url = window.location.href;
//     if(url.indexOf('?' + param + '=') != -1)
//         return true;
//     else if(url.indexOf('&' + param + '=') != -1)
//         return true;
//     return false
// }