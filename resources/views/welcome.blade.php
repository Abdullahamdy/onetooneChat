<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
</head>



<body>
    <main class="content">
        <div class="container p-0">

            <h1 class="h3 mb-3">Messages</h1>
            @inject('user', 'App\Models\User')
            @inject('message', 'App\Models\Message')
            @php
                $users = $user->all();
                $messages = $message->all();
            @endphp
            <div class="card">
                <div class="row g-0">
                    <div class="col-12 col-lg-5 col-xl-3 border-right">

                        <div class="px-4 d-none d-md-block">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control my-3" placeholder="Search...">
                                </div>
                            </div>
                        </div>
                        @forelse ($users as $user)
                            <a data-friendId="{{ $user->id }}"
                                class="list-group-item list-group-item-action border-0 reciver_id">
                                <div class="badge bg-success float-right">0</div>
                                <div class="d-flex align-items-start">
                                    <img src="Images/{{ $user->image }}" class="rounded-circle mr-1"
                                        alt="Vanessa Tucker" width="40" height="40">
                                    <div class="flex-grow-1 ml-3">
                                        {{ $user->name }}
                                        <div class="small"><span class="fas fa-circle chat-online"></span> Online</div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <h1>No Users Found</h1>
                        @endforelse


                        <hr class="d-block d-lg-none mt-1 mb-0">
                    </div>
                    <div class="col-12 col-lg-7 col-xl-9">




                        <div class="py-2 px-4 border-bottom d-none d-lg-block">
                            <div class="d-flex align-items-center py-1">
                                <div class="position-relative">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar3.png"
                                        class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
                                </div>
                                <div class="flex-grow-1 pl-3">
                                    <strong>Sharon Lessman</strong>
                                    <div class="text-muted small"><em>Typing...</em></div>
                                </div>

                            </div>
                        </div>



                        <div class="position-relative">
                            <div class="chat-messages p-4">

                            </div>
                        </div>

                        <div class="flex-grow-0 py-3 px-4 border-top">
                            <div class="input-group">
                                <input type="text" id='chatMessage' class="form-control"
                                    placeholder="Type your message">
                                <button class="btn btn-primary" id="sendMessage">Send</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        let activeUser = null;
        var UserId = {{ auth()->user()->id }};
        $('.reciver_id').on('click', function() {
            var friendId = $(this).attr("data-friendId");
            activeUser = friendId;
            console.log(activeUser);
            var Message = $('#chatMessage').val()
            $.ajax({
                type: "get",
                url: `{{ url('privateMessage/${friendId}') }}`,
                dataType: "json",
                data: {
                    'message': Message,

                },

                success: function(response) {
                    $('.chat-messages').html('');
                    $.each(response.fetchMessages, function(k, v) {

                        $('.chat-messages').append(`
                            <div class="chat-message-${UserId == v.user_id ? 'left' : 'right' } pb-4">
                                     <div>
                                         <img src="{{ asset('images/${v.user.image}') }}"
                                             class="rounded-circle mr-1" alt="Chris Wood" width="40"
                                             height="40">
                                         <div class="text-muted small text-nowrap mt-2">2:33 am</div>
                                     </div>
                                     <div class="flex-shrinv-1 bg-light rounded py-2 px-3 mr-3">
                                         <div class="font-weight-bold mb-1">${UserId == v.user_id ? 'You' : v.user.name }</div>
                                        ${v.message}
                                     </div>
                                 </div>
                     `)
                    });

                }

            });

        });







        $('#sendMessage').on('click', function() {
            console.log(activeUser);
            var Message = $('#chatMessage').val()

            $.ajax({
                type: "post",
                url: `{{ url('privateMessage/${activeUser}') }}`,
                dataType: "json",
                data: {
                    'message': Message,
                    'receiver_id': activeUser,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Echo.private('lchat')
                        .listen('MessageSent', (e) => {
                            console.log(e);
                            $('.chat-messages').append(`
                       <div class="chat-message-${UserId == e.message.user_id ? 'left' : 'right' } pb-4">
                                <div>
                                    <img src="{{ asset('images/${e.user.image}') }}"
                                        class="rounded-circle mr-1" alt="Chris Wood" width="40"
                                        height="40">
                                    <div class="text-muted small text-nowrap mt-2">2:33 am</div>
                                </div>
                                <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                                    <div class="font-weight-bold mb-1">${UserId == e.message.user_id ? 'You' : e.user.name }</div>
                                   ${e.message.message}
                                </div>
                            </div>
                `)
                        })
                    $('#chatMessage').val('')

                }
            });




        });
    </script>
</body>

</html>
