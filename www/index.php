<!DOCTYPE html>
<html>
    <head>
        <title>fakesqs test</title>
        <link rel="stylesheet" type="css/text" href="test.css"/>
        <script src="https://code.jquery.com/jquery-2.2.4.min.js" crossorigin="anonymous"></script>
        <style>
body {
    padding: 4px;
    margin: 4px;
    background-color: lavender;
    font-size: 14px;
    line-height: 21px;
    font-family: Garuda,Arial,sans-serif;  
}

h1 {
    margin: 0 0 8px 0;
    padding: 0;
    font-size: 20px;
    color: cornflowerblue;
}

ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

li { 
    display: block;
    margin: 0;
    padding: 4px 0px 2px;
    text-decoration: none;
}

.box {
    border: 1px solid blue;
    padding: 8px;
    margin: 4px;
    background-color: white;
    border-radius: 8px;
    -moz-border-radius: 8px;
    -webkit-border-radius: 8px;
}

.button {
    -moz-box-shadow:inset 0px 1px 0px 0px #efdcfb;
    -webkit-box-shadow:inset 0px 1px 0px 0px #efdcfb;
    box-shadow:inset 0px 1px 0px 0px #efdcfb;
    background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #dfbdfa), color-stop(1, #bc80ea));
    background:-moz-linear-gradient(top, #dfbdfa 5%, #bc80ea 100%);
    background:-webkit-linear-gradient(top, #dfbdfa 5%, #bc80ea 100%);
    background:-o-linear-gradient(top, #dfbdfa 5%, #bc80ea 100%);
    background:-ms-linear-gradient(top, #dfbdfa 5%, #bc80ea 100%);
    background:linear-gradient(to bottom, #dfbdfa 5%, #bc80ea 100%);
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfbdfa', endColorstr='#bc80ea',GradientType=0);
    background-color:#dfbdfa;
    -moz-border-radius:6px;
    -webkit-border-radius:6px;
    border-radius:6px;
    border:1px solid #c584f3;
    display:inline-block;
    cursor:pointer;
    color:#ffffff;
    font-family:Arial;
    font-size:13px;
    font-weight:bold;
    padding:4px 8px;
    text-decoration:none;
    text-shadow:0px 1px 0px #9752cc;
    margin: 0px 8px;
}

.button:hover {
    background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #bc80ea), color-stop(1, #dfbdfa));
    background:-moz-linear-gradient(top, #bc80ea 5%, #dfbdfa 100%);
    background:-webkit-linear-gradient(top, #bc80ea 5%, #dfbdfa 100%);
    background:-o-linear-gradient(top, #bc80ea 5%, #dfbdfa 100%);
    background:-ms-linear-gradient(top, #bc80ea 5%, #dfbdfa 100%);
    background:linear-gradient(to bottom, #bc80ea 5%, #dfbdfa 100%);
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#bc80ea', endColorstr='#dfbdfa',GradientType=0);
    background-color:#bc80ea;
}

.button:active {
    position:relative;
    top:1px;
}

</style>
    </head>
    <body>
        <div class="box" id="queue">
            <h1> create queue </h1>
            <form id="create_queue_form">
                <input type="text" name="new_queue_name" id="new_queue_name" placeholder="queue name"/>
                <a class="button" onclick="do_create_queue();">create queue</a>
            </form>
        </div>
        <div class="box" id="container_queue_list">
            <h1> queue list </h1>
            <ul id="queue_list">
            </ul>
        </div>
    </body>
<script>
    function get_queues() {
        $('#queue_list').empty();
        var queues = $.getJSON( "api_list_queues.php", function(data) {
            $.each(data['QueueUrls'], function( index, value ) {
                queue_name = basename(value);
                $('#queue_list').append(
                    $('<li>').append(
                        $('<strong>').text(queue_name)
                    ).append(
                        $('<button>').attr('class', 'button purge').data('queue_name',queue_name).append('purge').click(function(){do_purge_queue($(this))})
                    ).append(
                        $('<button>').attr('class', 'button delete').data('queue_name',queue_name).append('delete').click(function(){do_delete_queue($(this))})
                    ).append(
                        $('<input>').attr('id', queue_name + '_new_message').attr('placeholder', 'message to send').data('queue_name',queue_name)
                    ).append(
                        $('<button>').attr('class', 'button send').data('queue_name',queue_name).append('send msg').click(function(){do_send_message($(this))})
                    )
                );
            });
        });
    }

    function do_purge_queue(object) {
        queue_name = object.data('queue_name');
        $.getJSON( "api_purge_queue.php?queue_name=" + queue_name, function(data) { get_queues(); });
    }

    function do_delete_queue(object) {
        queue_name = object.data('queue_name');
        $.getJSON( "api_delete_queue.php?queue_name=" + queue_name, function(data) { get_queues(); });
    }

    function do_create_queue() {
        queue_name = $("#new_queue_name").val();
        $.getJSON( "api_create_queue.php?queue_name=" + queue_name, function(data) { get_queues(); });
    }

    function do_send_message(object) {
        queue_name = object.data('queue_name');
        message_content = encodeURI($("#"+queue_name+"_new_message").val());
        $.getJSON( "api_send_message.php?queue_name=" + queue_name +"&body=" + message_content, function(data) { get_queues(); });
    }


    function basename(path) {
        return path.split('/').reverse()[0];
    }



    $( document ).ready(function() {
        get_queues();
    });

</script>
</html>