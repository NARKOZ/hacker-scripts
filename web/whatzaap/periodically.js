var contact_name = "Bae name here",
    timeout = 1000*60,
    message = "hey nice pic";

if (!window.localStorage["WhatsEasy"]) {
    var data = JSON.stringify({"dp" : ""});
    window.localStorage['WhatsEasy'] = data;
}

console.log("last_image ", window.localStorage["WhatsEasy"]);

function send(msg) {
        jQuery('.input-container').find('.input').html(msg);
        jQuery('.btn-emoji').click();
        jQuery('.icon-emoji-people').click();
        jQuery('.emojiordered1167').click();
        jQuery('.send-container').click();     
}
function convertImgToBase64URL(url, callback, outputFormat){
    var img = new Image();
    img.crossOrigin = 'Anonymous';
    img.onload = function(){
        var canvas = document.createElement('CANVAS'),
        ctx = canvas.getContext('2d'), dataURL;
        canvas.height = this.height;
        canvas.width = this.width;
        ctx.drawImage(this, 0, 0);
        dataURL = canvas.toDataURL(outputFormat);
        callback(dataURL);
        canvas = null; 
    };
    img.src = url;
}

function get_image_url() {
    var image_url = jQuery('#main').find('.icon-user-default').find('.avatar-image').attr('src');
    return image_url
}

function get_image_and_send_message() {
    var image_url = get_image_url(),
        current_dp = JSON.parse(window.localStorage["WhatsEasy"])["dp"];

    convertImgToBase64URL(image_url, function(b64) {
        console.log(b64);
        console.log(window.localStorage['WhatsEasy']);
        if (current_dp == "") {
            console.log("First time. Save the image");
            var data = JSON.stringify({"dp" : b64});
            window.localStorage["WhatsEasy"] = data;
        }
        else {
            if (b64 != current_dp) {
                var data = JSON.stringify({"dp" : b64});
                window.localStorage["WhatsEasy"] = data;
                console.log("NEW DP");
                send(message);

            }
            else {
                console.log("SAME DP");
            }    
        }
        
    });
}
function periodically(contact_name, smiley_number) {
    console.log("Runnning", contact_name);
    jQuery('.chat:contains("' + contact_name + '")').click();
    var current_window = jQuery.trim(jQuery('.pane-chat').find('.chat-title').find('span').text());
    
    if (current_window == contact_name) {
        console.log("item found");
        get_image_and_send_message();
        setTimeout(function() {
            periodically(contact_name);
        }, timeout);
    }
    else {
        console.log("Wrong window. ", current_window);
        setTimeout(function() {
            periodically(contact_name);
        }, 1000*5);

    }
}

setTimeout(function() {
    periodically(contact_name);
}, timeout);
