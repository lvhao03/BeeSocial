<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4" style="padding-right:0px">
                <h2>Đoạn chat</h2>
                <input class="form-control rounded mr-4" type="text" placeholder="Tìm kiếm">
                <ul id="friend-list">
                    @foreach($friendList as $friend)
                    <li class="d-flex align-items-center user rounded p-2" onclick="goToPrivateChat({{$friend->id}})">
                        <img class="border" style="with:50px; height:50px ; border-radius:50%" src="{{$friend->image_url}}">
                        <div class="d-flex  flex-column" style="margin-left: 12px">
                            <h4 class="mt-2">{{$friend->name}}</h4>
                            <span>hello</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-8" style="border-left: 1px solid #2F3031">
                <div class="d-flex align-items-center user ">
                    <img id="avatar" class="border" style="with:50px; height:50px ; border-radius:50%" src="https://scontent.fsgn8-4.fna.fbcdn.net/v/t1.6435-1/67620016_124718828803630_4274866063075704832_n.jpg?stp=dst-jpg_p100x100&_nc_cat=111&ccb=1-7&_nc_sid=2b6aad&_nc_ohc=l3_dLuDN1BoAX_MTm3R&_nc_ad=z-m&_nc_cid=0&_nc_ht=scontent.fsgn8-4.fna&oh=00_AfDILU491Ve62OM4Z130dQe5fOl7J8FgW7Wde1z6EGU0QA&oe=65BE39F8">
                    <div class="d-flex  flex-column " style="margin-left: 12px">
                        <h6 class="mt-2 receiver-name">User</h4>
                        <span style="font-size: 14px">Đang hoạt động</span>
                    </div>
                  </div>
                <div id="messageList">
                </div>
                <form action="/send" method="POST"  id="myForm">
                    @csrf
                    <input class="form-control rounded mb-4" require name="message" id="message" type="text" placeholder="Aa">
                </form>
            </div>
        </div>
    </div>
    <button onclick="toggleDarkMode()">Toggle Dark Mode</button>
    <a href="/logout">Log out</a>
    <!-- <script type="module">
        Echo.join(`presence-chat.3.2`)
            .here((users) => {
                console.log('Người dùng hiện tại trong kênh:', users);
            })
            .joining((user) => {
                console.log('Người dùng tham gia kênh:', user);
            })
            .leaving((user) => {
                console.log('Người dùng rời khỏi kênh:', user);
            })
    </script> -->
    <script>
        var sender_id = <?php echo \Auth::user()->id?>
        
        var receiver_id = 0;
        var room_id;

        function goToPrivateChat(id){
            autoScrollToBottom();
            receiver_id = id;
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
                    let html = '';
                    response.slice(0, -1).forEach(message => {
                       showMessage(message, response[response.length - 1]);
                    })
                    $('#messageList').append(html);
                },
            });
        }

        function changeReceiverName(receiverName){
            $("#avatar").attr("src", receiverName.image_url);
            $('.receiver-name').html(receiverName.name);
        }
        
        function subscribeToPrivateChannel(receiver_id){
            room_id = receiver_id + sender_id;
            gainAccessToPrivateChannel()
            Echo.private('private.' + room_id)
                .listen('.SendChat', (message) => {
                    showMessage(message, message);
                    autoScrollToBottom();
                });
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

        function showMessage(message, receiver = 0){
            let html = '';
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


        function get_hours_and_minutes(time){
            var dateObject = new Date(time);
            var hour = dateObject.getHours();
            var minute = dateObject.getMinutes();
            return padZero(hour) + ':' +  padZero(minute);
        }

        $(document).ready(function() {
            $('#myForm').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: '/send', 
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        message: $('#message').val(),
                        receiver_id : receiver_id,
                        room_id : room_id,
                        sent_date:  new Date()
                    },
                    success: function(response) {
                        $('#message').val('');
                    },
                });
            });
        });
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
    </script>
</body>
</html>