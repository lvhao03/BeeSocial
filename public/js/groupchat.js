function subscribeToGroupChannel(groupID){
    is_group = true;
    group_id = groupID;
    Echo.join(`group.` + groupID) 
        .listen('.SendGroupChat', (message) => {
            showGroupMessage(message, message);
            autoScrollToBottom();
        })
        .here((users) => {
            console.log('Người dùng hiện tại trong kênh:', users);
        })
        .joining((user) => {
            console.log('Người dùng tham gia kênh:', user);
        })
        .leaving((user) => {
            console.log('Người dùng rời khỏi kênh:', user);
        })

    getGroupMessage(groupID);
}

function showGroupMessage(message, senderImageUrl){
    let html = '';
    // if (message.message_text.endsWith('.png')){
    //     showImageMessage(message, receiver);
    //     return;
    // }
    // nếu mình là người gửi thì hiện trái 
    if (message.sender_id == sender_id) {
        html += ` <li class="my-4 d-flex flex-row-reverse align-items-center ">
            <div class="d-flex flex-column p-2 rounded mr-2" style=" color:white; background-color: #0084FF">
                <span class="pb-2 ">${message.message_text}</span>
                <span style="font-size:12px;">${get_hours_and_minutes(message.sent_date)}</span>
            </div>
        </li>`;
    } else {
        html +=  `<li class="my-4 d-flex align-items-center ">
            <img class="border" style="with:50px; height:50px ; border-radius:50%" src="${senderImageUrl}">
            <div class="d-flex flex-column p-2 rounded ml-2  receiver-message">
                <span class="pb-2">${message.message_text}</span>
                <span style="font-size:12px;">${get_hours_and_minutes(message.sent_date)}</span>
            </div>
        </li>`
    }
    $('#messageList').append(html);
}

function sendGroupChatMessage(event){
    let message = $('#message').val();
    event.preventDefault();
    $.ajax({
        url: '/group/send', 
        method: 'POST',
        headers: {
            'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            message: message,
            sender_id: sender_id,
            group_id : group_id,
            sent_date:  new Date()
        },
        success: function(response) {
            $('#message').val('');
        },
    });
}

function getGroupMessage(groupID){
    $.ajax({
        url: '/group/' + groupID, 
        method: 'GET',
        headers: {
            'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#messageList').html('');
            response.forEach(message => {
                getUserImageURL(message.sender_id).then(senderImageUrl => {
                    showGroupMessage(message, senderImageUrl);
                })
            })
        },
    });
}

function leaveGroup(){

}
