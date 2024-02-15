var scrollContainer = document.getElementById('messageList');
function autoScrollToBottom() {
    scrollContainer.scrollTop = scrollContainer.scrollHeight;
}

function padZero(number) {
    return number < 10 ? '0' + number : number;
}
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
}

function changeReceiverName(receiverName){
    $("#avatar").attr("src", receiverName.image_url);
    $('.receiver-name').html(receiverName.name);
}

function get_hours_and_minutes(time){
    var dateObject = new Date(time);
    var hour = dateObject.getHours();
    var minute = dateObject.getMinutes();
    return padZero(hour) + ':' +  padZero(minute);
}

function getUserImageURL(userID){
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/user/url', 
            method: 'POST',
            headers: {
                'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                userID: userID
            },
            success: function(response) {
                resolve(response.image_url);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}