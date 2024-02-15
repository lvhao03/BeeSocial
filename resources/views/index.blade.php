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
    <div class="row">
        <div class="col-md-4 pl-4" style="padding-right:0px; padding-left: 40px">
            <div class="d-flex py-2 justify-content-between align-items-center"> 
                <div class="d-flex">
                    <img class="main-avatar" class="border" style="with:50px; height:50px ; border-radius:50%" src="https://scontent.fsgn8-4.fna.fbcdn.net/v/t1.6435-1/67620016_124718828803630_4274866063075704832_n.jpg?stp=dst-jpg_p100x100&_nc_cat=111&ccb=1-7&_nc_sid=2b6aad&_nc_ohc=l3_dLuDN1BoAX_MTm3R&_nc_ad=z-m&_nc_cid=0&_nc_ht=scontent.fsgn8-4.fna&oh=00_AfDILU491Ve62OM4Z130dQe5fOl7J8FgW7Wde1z6EGU0QA&oe=65BE39F8">
                    <div class="d-flex  flex-column " style="margin-left: 12px">
                        <h6 class="mt-2 receiver-name">${currentUser->image_url}</h4>
                        <span style="font-size: 14px">Đang hoạt động</span>
                    </div>
                </div>
                <a href="/logout">
                    <button class="btn btn-primary mr-4">Đăng xuất</button>
                </a>
            </div>
            <!-- <button  onclick="toggleDarkMode()"><i class="fa-solid fa-sun"></i></button> -->
            <hr>
            <div class="list">
                <ul id="friend-list">
                    @foreach($friendList as $friend)
                    <li id="{{$friend->id}}" class=" d-flex align-items-center user rounded p-2" onclick="goToPrivateChat({{$friend->id}})">
                        <img class="border" style="with:50px; height:50px ; border-radius:50%" src="{{$friend->image_url}}">
                        <div class="d-flex  flex-column" style="margin-left: 12px">
                            <h4 class="mt-2">{{$friend->name}}</h4>
                            <span class="opacity-75">{{$friend->newest_message}}</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <ul id="group-list">
                    @foreach($groupList as $group)
                        <li class="d-flex align-items-center user rounded p-2" onclick="subscribeToGroupChannel({{$group->id}})">
                            <img class="border" style="with:50px; height:50px ; border-radius:50%" src="">
                            <div class="d-flex  flex-column" style="margin-left: 12px">
                                <h4 class="mt-2">{{$group->group_name}}</h4>
                                <span class="opacity-75">hello</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <hr>
            <button class="btn btn-primary mt-2" data-toggle="modal" data-target="#findFriendModal">
                Tìm bạn
            </button>
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
            <div class="modal fade" id="findFriendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tìm bạn</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Modal content goes here -->
                            <form id="findFriendForm" action="" method="POST">
                                <label for="friendName">Tên bạn bè</label>
                                <div class="d-flex" style="height: 50px">
                                    <input id="friendName" class="form-control rounded" type="text" placeholder="Nhập tên bạn">
                                    <button class="btn btn-primary text-black" style="font-size:16px" type="submit">Tìm</button>
                                </div>
                            </form>
                            <ul class="find-friend-list mt-2">
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <!-- Additional buttons or actions can be added here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Thông tin nhóm</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="findFriendForm" action="" method="POST">
                                <label for="group-name">Tên nhóm</label>
                                <div class="d-flex">
                                    <input id="group-name" class="form-control rounded" type="text" placeholder="Nhập tên nhóm">
                                </div>
                            </form>
                            Thành viên nhóm:
                            <ul class="d-flex mb-2 group-member">
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" onclick="leaveGroup(<?php echo \Auth::user()->id; ?>)">Rời nhóm</button>
                            <!-- Additional buttons or actions can be added here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8" style="border-left: 1px solid #2F3031; padding:0px">
            <div class="d-flex align-items-center justify-content-between user py-2 ">
                <div class="d-flex">
                    <img class="main-avatar" class="border" style="with:50px; height:50px ; border-radius:50%" src="https://scontent.fsgn8-4.fna.fbcdn.net/v/t1.6435-1/67620016_124718828803630_4274866063075704832_n.jpg?stp=dst-jpg_p100x100&_nc_cat=111&ccb=1-7&_nc_sid=2b6aad&_nc_ohc=l3_dLuDN1BoAX_MTm3R&_nc_ad=z-m&_nc_cid=0&_nc_ht=scontent.fsgn8-4.fna&oh=00_AfDILU491Ve62OM4Z130dQe5fOl7J8FgW7Wde1z6EGU0QA&oe=65BE39F8">
                    <div class="d-flex  flex-column " style="margin-left: 12px">
                        <h6 class="mt-2 receiver-name">User</h4>
                        <span style="font-size: 14px">Đang hoạt động</span>
                    </div>
                </div>
                <button class="btn btn-primary mt-2" data-toggle="modal" data-target="#groupModal">
                    <i class="fa-solid fa-circle-info"></i>
                </button>
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
    
    <script src="{{asset('/js/helper.js')}}"></script>
    <script src="{{asset('/js/privatechat.js')}}"></script>
    <script src="{{asset('/js/groupchat.js')}}"></script>
    <script>
        var currentUser = <?php echo \Auth::user() ?>

        $('.receiver-name').text(currentUser.name);
        $(".main-avatar").attr("src", currentUser.image_url);

        var sender_id = <?php echo \Auth::user()->id?>
        

        var image_url = '{{ asset("storage/") }}';
        var friendList = <?php echo $friendList ?>


        var receiver_id = null;
        var room_id;
        var group_id = 10;
        var is_group = false;

        function leaveGroup(userID){
            $.ajax({
                url: '/leave/group/',
                method: 'DELETE',
                headers: {
                    'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
                }, 
                data: {
                    groupID: group_id,
                    userID: userID
                },
                dataType: 'json',
                success: function(response) {
                    location.reload();
                }
            });
        }

        function showGroupInfo(groupID){
            $.ajax({
                url: '/group/detail/' + groupID, 
                method: 'GET',
                headers: {
                    'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
                }, 
                dataType: 'JSON',
                success: function(groupInfo) {
                    let html = '';
                    $('.group-member').html('');
                    groupInfo.forEach(member => {
                        html += `
                        <li class="rounded px-2 mx-2 border">
                            <span>${member.name}</span> <a href="" onclick="leaveGroup(${member.id})">x</a>
                        </li>`;
                    })
                    $('.group-member').append(html);
                    $('#group-name').val(groupInfo[0].group_name);
                }
            });
        }

        function addFriend(friendID){
            let url = '/get/user/' + friendID;
            $.ajax({
                url: url, 
                method: 'GET',
                headers: {
                    'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
                }, 
                dataType: 'JSON',
                success: function(friend) {
                    if ($('#' + friend.id).length) {
                        $('#findFriendModal').modal('hide');
                        return
                    }
                    $('#friend-list').prepend(`
                        <li class=" d-flex align-items-center user rounded p-2" onclick="goToPrivateChat(${friend.id})">
                            <img class="border" style="width:50px; height:50px ; border-radius:50%" src="${friend.image_url}">
                            <div class="d-flex  flex-column" style="margin-left: 12px">
                                <h4 class="mt-2">${friend.name}</h4>
                                <span class="opacity-75">${friend.email}</span>
                            </div>
                        </li>`)
                    $('#findFriendModal').modal('hide');
                }
            });
        }

        $(document).ready(function() {
            $('#groupModal').on('show.bs.modal', function (e) {
                if(!group_id){
                    return;
                }
                showGroupInfo(group_id);
            });

            $('#myForm').submit(function(event) {
                if (is_group){
                    sendGroupChatMessage(event);
                    return 
                }
                send_private_chat_message(event);
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
                        group_name: $('#groupName').val(),
                        member_id: sender_id
                    },
                    success: function(response) {
                        $('#exampleModal').modal('hide');
                        $('#groupName').val('');
                        location.reload();
                    },
                });
            });

            $('#findFriendForm').submit(function(event) {
                console.log($('#friendName').val());
                event.preventDefault();
                $.ajax({
                    url: '/user/by/' + $('#friendName').val(), 
                    method: 'GET',
                    headers: {
                        'X-CSRF-Token':  $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let html = '';
                        $('.find-friend-list').html('');
                        response.forEach(friend => {
                            html += `<li class="d-flex align-items-center user rounded p-2" onclick="addFriend(${friend.id})">
                                <img class="border" style="width:50px; height:50px; border-radius:50%" src="${friend.image_url}">
                                <div class="d-flex flex-column" style="margin-left: 12px">
                                    <h4 class="mt-2">${friend.name}</h4>
                                    <span class="opacity-75">${friend.email}</span>
                                </div>
                            </li>`;
                        })
                        $('.find-friend-list').append(html)
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
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>