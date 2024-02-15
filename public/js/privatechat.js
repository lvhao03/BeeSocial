function goToPrivateChat(id){
    is_group = false;
    autoScrollToBottom();
    subscribeToNotificationChannel();
    if (receiver_id == id){
        return;
    }
    receiver_id = id;
    $(`#${receiver_id}`).removeClass('highlight-message');
    $.ajax({
        url: '/private-chat/' + receiver_id, 
        method: 'GET',
        headers: {
            'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            changeReceiverName(response[response.length - 1])
            subscribeToPrivateChannel(receiver_id);
            $('#messageList').html('');
            response.slice(0, -1).forEach(message => {
               showMessage(message, response[response.length - 1]);
            })
        },
    });
}

function subscribeToPrivateChannel(receiver_id){
    room_id = receiver_id + sender_id;
    gainAccessToPrivateChannel()
    Echo.private('private.' + room_id)
        .listen('.SendChat', (message) => {
            showMessage(message, message);
            autoScrollToBottom();
        })
}

function gainAccessToPrivateChannel(){
    $.ajax({
        url: '/save', 
        method: 'POST',
        headers: {
            'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            receiver_id : receiver_id,
        },
        success: function(response) {
        },
    });
}

function subscribeToNotificationChannel(){
    Echo.private('notification.' + sender_id)
    .listen('.SendChat', (message) => {
        notificationChatForReceiver(message);
    })
}

function showMessage(message, receiver){
    let html = '';
    if (message.message_text.endsWith('.png')){
        showImageMessage(message, receiver);
        return;
    }
    if (message.receiver_id != sender_id) {
        html += ` <li class="my-4 d-flex flex-row-reverse align-items-center ">
            <div class="d-flex flex-column p-2 rounded mr-2" style=" color:white; background-color: #0084FF">
                <span class="pb-2 ">${message.message_text}</span>
                <span style="font-size:12px;">${get_hours_and_minutes(message.sent_date)}</span>
            </div>
        </li>`;
    } else {
        html +=  `<li class="my-4 d-flex align-items-center ">
            <img class="border" style="with:50px; height:50px ; border-radius:50%" src="${receiver.image_url}">
            <div class="d-flex flex-column p-2 rounded ml-2  receiver-message">
                <span class="pb-2">${message.message_text}</span>
                <span style="font-size:12px;">${get_hours_and_minutes(message.sent_date)}</span>
            </div>
        </li>`
    }
    $('#messageList').append(html);
}

function showImageMessage(message, receiver){
    let html = '';
    let imageSrc = image_url + '/' + message.message_text;
    if (message.receiver_id != sender_id) {
        html += ` <li class="my-4 d-flex flex-row-reverse align-items-center ">
            <div class="d-flex flex-column mr-2">
                <img class="rounded" style="width: 550px; height: 200px ; object-fit:cover;" src="${imageSrc}">
                <span style="font-size:12px;">${get_hours_and_minutes(message.sent_date)}</span>
            </div>
        </li>`;
    } else {
        html +=  `<li class="my-4 d-flex align-items-center ">
            <img class="border" style="with:50px; height:50px ; border-radius:50%" src="${receiver.image_url}">
            <div class="d-flex flex-column p-2 rounded ml-2">
                <img class="rounded" style="width: 550px; height: 200px object-fit:cover;" src="${imageSrc}">
                <span style="font-size:12px;">${get_hours_and_minutes(message.sent_date)}</span>
            </div>
        </li>`
    }
    $('#messageList').append(html);
}


function notificationChatForReceiver(message){
    let id = message.sender_id;
    if ( $(`#${id}`) == receiver_id) {
        $(`#${id}`).addClass('highlight-message');
    }
    $(`#${id} span`).text(message.message_text);
}

function send_private_chat_message(event){
    let message = $('#message').val();
    event.preventDefault();
    $.ajax({
        url: '/send', 
        method: 'POST',
        headers: {
            'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            message: message,
            sender_id: sender_id,
            receiver_id : receiver_id,
            room_id : room_id,
            sent_date:  new Date()
        },
        success: function(response) {
            $('#message').val('');
            $(`#${receiver_id} span`).text(message);
        },
    });
}