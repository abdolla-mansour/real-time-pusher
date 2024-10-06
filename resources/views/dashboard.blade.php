<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="page-content page-container" id="page-content">
                        <div class="padding">
                            <div class="row container d-flex justify-content-center">
                                <div class="col-md-4">

                                    <div class="box box-warning direct-chat direct-chat-warning">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Chat {{ $user->name }}</h3>

                                            <div class="box-tools pull-right">
                                                <span data-toggle="tooltip" title="" class="badge bg-yellow" data-original-title="3 New Messages">20</span>
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                </button>
                                                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="Contacts">
                                                    <i class="fa fa-comments"></i></button>
                                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="box-body">

                                            <div id="chat_area" class="direct-chat-messages">



                                                @foreach ($messages as $message)
                                                    @if ($message->sender == auth()->id())
                                                        <div class="direct-chat-msg right">
                                                            <div class="direct-chat-info clearfix">
                                                                <span class="direct-chat-name pull-right">Sarah Bullock</span>
                                                                <span class="direct-chat-timestamp pull-left">{{ $message->created_at->diffforhumans() }}</span>
                                                            </div>

                                                            <img class="direct-chat-img" src="https://img.icons8.com/office/36/000000/person-female.png" alt="message user image">

                                                            <div class="direct-chat-text">
                                                                {{ $message->message }}
                                                            </div>

                                                        </div>
                                                    @else
                                                        <div class="direct-chat-msg">
                                                            <div class="direct-chat-info clearfix">
                                                                <span class="direct-chat-name pull-left">Timona Siera</span>
                                                                <span class="direct-chat-timestamp pull-right">{{ $message->created_at->diffforhumans() }}</span>
                                                            </div>

                                                            <img class="direct-chat-img" src="https://img.icons8.com/color/36/000000/administrator-male.png" alt="message user image">

                                                            <div class="direct-chat-text">
                                                                {{ $message->message }}
                                                            </div>

                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>

                                        </div>

                                        <div class="box-footer">
                                            <form action="#" method="post">
                                                <div class="input-group">
                                                    <input type="text" id="message" placeholder="Type Message ..." class="form-control">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning btn-flat" id="send">Send</button>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
        $("#send").click(function() {
            $.post("/chat/{{ $user->id }}", {
                    message: $("#message").val(),
                },
                function(data, status) {
                    console.log("Data: " + data + "\nStatus" + status);
                    if (status == 'success') {
                        let senderMessage = `
                        <div class="direct-chat-msg right">
                            <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-right">Sarah Bullock</span>
                                <span class="direct-chat-timestamp pull-left">now</span>
                            </div>

                            <img class="direct-chat-img" src="https://img.icons8.com/office/36/000000/person-female.png" alt="message user image">

                            <div class="direct-chat-text">
                                ` + $("#message").val() + `
                            </div>

                        </div>`;
                        $("#chat_area").append(senderMessage);
                    }
                }
            ).fail(function(xhr, status, error) {
                console.error("Error: " + error);
                console.error("Status: " + status);
                console.error("Response: " + xhr.responseText);
            });;
        });

        Pusher.logToConsole = true;

        var pusher = new Pusher('4a9b7206edae6e019bca', {
            cluster: 'eu'
        });
        var channel = pusher.subscribe('test_channel{{ $user->id }}');

        channel.bind('chatSender', function(data) {
            let receiverMessage = `
                <div class="direct-chat-msg">
                    <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left">Timona Siera</span>
                        <span class="direct-chat-timestamp pull-right">now</span>
                    </div>
                    <img class="direct-chat-img" src="https://img.icons8.com/color/36/000000/administrator-male.png" alt="message user image">
                    <div class="direct-chat-text">
                        ` + data.message + `
                    </div>
                </div>`;
            $("#chat_area").append(receiverMessage);
        });
    </script>

    {{-- var channel = pusher.subscribe('chat{{ auth()->id() }}');
        channel.bind('chatSender', function(data) {
            let message = JSON.stringify(data);

            let div = 

                $("#chat_area").append(div);
        });

        console.log('asdf');
    </script> --}}
</x-app-layout>
