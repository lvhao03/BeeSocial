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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4" style="padding-right:0px">
                <div class="d-flex py-2 justify-content-between align-items-center"> 
                    <div class="d-flex">
                        <img id="main-avatar" class="border" style="with:50px; height:50px ; border-radius:50%" src="https://scontent.fsgn8-4.fna.fbcdn.net/v/t1.6435-1/67620016_124718828803630_4274866063075704832_n.jpg?stp=dst-jpg_p100x100&_nc_cat=111&ccb=1-7&_nc_sid=2b6aad&_nc_ohc=l3_dLuDN1BoAX_MTm3R&_nc_ad=z-m&_nc_cid=0&_nc_ht=scontent.fsgn8-4.fna&oh=00_AfDILU491Ve62OM4Z130dQe5fOl7J8FgW7Wde1z6EGU0QA&oe=65BE39F8">
                        <div class="d-flex  flex-column " style="margin-left: 12px">
                            <h6 class="mt-2 receiver-name">User</h4>
                            <span style="font-size: 14px">Đang hoạt động</span>
                        </div>
                    </div>
                    <a href="/logout">
                        <button class="btn btn-primary mr-4">Đăng xuất</button>
                    </a>
                </div>
                <button  onclick="toggleDarkMode()"><i class="fa-solid fa-sun"></i></button>
                <div class="mt-2 mr-4">
                    <input class="form-control rounded " type="text" placeholder="Tìm kiếm">
                </div>
                <div class="list">
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
                    <ul id="group-list">
                        @foreach($groupList as $group)
                            <li class="d-flex align-items-center user rounded p-2" onclick="goToPrivateChat({{$group->id}})">
                                <img class="border" style="with:50px; height:50px ; border-radius:50%" src="">
                                <div class="d-flex  flex-column" style="margin-left: 12px">
                                    <h4 class="mt-2">{{$group->group_name}}</h4>
                                    <span>hello</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <hr>
                <button class="btn btn-primary mt-2" data-toggle="modal" data-target="#exampleModal">
                    Tạo nhóm
                </button>

                <!-- The Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tạo nhóm</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Modal content goes here -->
                                <form id="groupForm" action="" method="POST">
                                    <label for="groupName">Tên nhóm</label>
                                    <input id="groupName" class="form-control rounded mb-2" type="text" placeholder="Nhập tên nhóm">
                                    <button class="btn btn-primary ml-auto">Tạo nhóm</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <!-- Additional buttons or actions can be added here -->
                            </div>
                        </div>
                    </div>
                </div>
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
                <div class="p-2 d-flex align-items-center gap-3 mb-2">
                    <form class="w-100" action="/send" method="POST"  id="myForm">
                        @csrf
                        <input class="form-control rounded " require name="message" id="message" type="text" placeholder="Aa">
                    </form>
                    <i class="fs-5 fa-solid fa-link"></i>
                    <form action="" enctype="multipart/form-data" id="fileUpload">
                        <label for="fileInput">
                            <i class="fs-5 fa-regular fa-image"></i>
                        </label>
                        <input type="file" id="fileInput" name="image" hidden>
                    </form>
                    <button class="btn btn-primary">Gửi</button>
                </div>
            </div>
        </div>
    </div>
    
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
        

        var image_url = '{{ asset("storage/") }}';
        var receiver_id = null;
        var room_id;

        function goToPrivateChat(id){
            autoScrollToBottom();
            if (receiver_id == id){
                return;
            }
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
                    response.slice(0, -1).forEach(message => {
                       showMessage(message, response[response.length - 1]);
                    })
                },
            });
        }

        function changeReceiverName(receiverName){
            $("#avatar").attr("src", receiverName.image_url);
            console.log(receiverName.image_url);
            $('.receiver-name').html(receiverName.name);
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

            $('#groupForm').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: '/create/group', 
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        group_name: $('#groupName').val()
                    },
                    success: function(response) {
                        $('#exampleModal').modal('hide');
                        $('#groupName').val('');
                    },
                });
            });


            $('#fileInput').change(function(event) {
                $('#fileUpload').submit();
            });

            $('#fileUpload').submit(function(event){
                var formData = new FormData(this);
                formData.append('receiver_id', receiver_id);
                formData.append('room_id', room_id);
                formData.append('sent_date', new Date());
                event.preventDefault();
                $.ajax({
                    url: '/upload', 
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false, // Important for sending FormData
                    contentType: false, 
                    data: formData,
                    success: function(response) {
                        $('#message').val('');
                    },
                });
            })
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>