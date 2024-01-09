<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .user:hover {
            background-color: #F3F3F3;
            cursor: pointer;
        }
        .message-item {
            position: relative;
            cursor: pointer;
        }
        .hover-text {
            opacity: 0;
            position: absolute;
            top: 50%;
            right: 7%; /* Đặt right thành 100% để hiển thị bên trái */
            transform: translateY(-50%);
            background-color: #fff;
            padding: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: opacity 0.3s ease-in-out;
        }

        .hover-text-2 {
            opacity: 0;
            position: absolute;
            top: 50%;
            right: 7%; /* Đặt right thành giá trị dương để hiển thị bên phải */
            transform: translateY(-50%);
            background-color: #fff;
            padding: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: opacity 0.3s ease-in-out;
        }

        .message-item:hover .hover-text{
            opacity: 1;
        }
        .message-item:hover .hover-text-2{
            opacity: 1;
        }
    </style>
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
                        <img style="with:50px; height:50px ; border-radius:50%" src="https://scontent.fsgn8-4.fna.fbcdn.net/v/t1.6435-1/67620016_124718828803630_4274866063075704832_n.jpg?stp=dst-jpg_p100x100&_nc_cat=111&ccb=1-7&_nc_sid=2b6aad&_nc_ohc=l3_dLuDN1BoAX_MTm3R&_nc_ad=z-m&_nc_cid=0&_nc_ht=scontent.fsgn8-4.fna&oh=00_AfDILU491Ve62OM4Z130dQe5fOl7J8FgW7Wde1z6EGU0QA&oe=65BE39F8">
                        <div class="d-flex  flex-column" style="margin-left: 12px">
                            <h4 class="mt-2">{{$friend->name}}</h4>
                            <span>hello</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-8 border">
                <div class="d-flex align-items-center user ">
                    <img style="with:50px; height:50px ; border-radius:50%" src="https://scontent.fsgn8-4.fna.fbcdn.net/v/t1.6435-1/67620016_124718828803630_4274866063075704832_n.jpg?stp=dst-jpg_p100x100&_nc_cat=111&ccb=1-7&_nc_sid=2b6aad&_nc_ohc=l3_dLuDN1BoAX_MTm3R&_nc_ad=z-m&_nc_cid=0&_nc_ht=scontent.fsgn8-4.fna&oh=00_AfDILU491Ve62OM4Z130dQe5fOl7J8FgW7Wde1z6EGU0QA&oe=65BE39F8">
                    <div class="d-flex  flex-column " style="margin-left: 12px">
                        <h6 class="mt-2">User</h4>
                        <span>Hello</span>
                    </div>
                  </div>
                <ul id="messageList">
                </ul>
                <form action="/send" method="POST"  id="myForm">
                    @csrf
                    <input class="form-control rounded mb-4" name="message" id="message" type="text" placeholder="Aa">
                </form>
            </div>
        </div>
    </div>
    <a href="/logout">Log out</a>
    <script>
        goToPrivateChat(1);
        var sender_id = <?php echo \Auth::user()->id?>

        var receiver_id = 0;
        function goToPrivateChat(id){
            receiver_id = id;
            $.ajax({
                url: '/private-chat/' + receiver_id, 
                method: 'GET',
                headers: {
                    'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#messageList').html('');
                    let html = '';
                    response.forEach(message => {
                       showMessage(message);
                    })
                    $('#messageList').append(html);
                },
            });
        }

        function showMessage(message){
            let html = '';
            if (message.receiver_id != sender_id) {
                html += ` <li class="my-4 d-flex flex-row-reverse align-items-center ">
                    <span style="border-radius:10px; color:white; margin-right: 15px ; padding: 10px; background-color: #0084FF">${message.message_text}</span>
                </li>`;
            } else {
                html +=  `<li class="my-4 d-flex align-items-center ">
                    <img style="with:50px; height:50px ; border-radius:50%" src="https://scontent.fsgn8-4.fna.fbcdn.net/v/t1.6435-1/67620016_124718828803630_4274866063075704832_n.jpg?stp=dst-jpg_p100x100&_nc_cat=111&ccb=1-7&_nc_sid=2b6aad&_nc_ohc=l3_dLuDN1BoAX_MTm3R&_nc_ad=z-m&_nc_cid=0&_nc_ht=scontent.fsgn8-4.fna&oh=00_AfDILU491Ve62OM4Z130dQe5fOl7J8FgW7Wde1z6EGU0QA&oe=65BE39F8">
                    <span style="border-radius:10px; margin-left: 15px ; padding: 10px; background-color: #F1F0F0 ; color:black">${message.message_text}</span>
                </li>`
            }
            $('#messageList').append(html);
        }

        $(document).ready(function() {
            $('#myForm').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: '/send', // Replace with your server endpoint
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        message: $('#message').val(),
                        receiver_id : receiver_id
                    },
                    success: function(response) {
                        $('#message').val('');
                    },
                });
            });
        });
    </script>
    <script type="module">
        Echo.channel('chat')
            .listen('.SendChat', (message) => {
                showMessage(message);
            });
    </script>
</body>
</html>