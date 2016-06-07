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

input {
    font-size: 13px;
    height: 21px;
    padding: 0 4px;
    margin: 0 8px 0 0;
}

#message_list li {
    padding: 8px;
    border: 1px dotted grey;
    margin: 2px 0px;
}

#message_list .new_message {
    background-color: #FEFFD5;
}

.message_body {
    margin-bottom: 4px;
    display: inline-block;
}

.message_source {
    font-style: oblique;
    font-weight: 600;
    font-size: smaller;
    color: #888888;
}

#queue_list li { 
    display: block;
    margin: 0;
    padding: 4px 0px 2px;
    text-decoration: none;
    border-bottom: 1px solid #FBF5FF;
    padding-bottom: 4px;
}

#queue_list li strong {
    border: 1px solid #F3E1FF;
    background: #FBF5FF;
    width: 230px;
    display: inline-block;
    padding: 0 4px;
    margin-right: 8px;  
}

.queue_box {
    width: 800px;
    margin: auto;
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
    margin: 0px 8px 0px 0px;
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
        <div class="queue_box">
            <div class="box" id="queue">
                <h1> create queue </h1>
                <div id="create_queue_form">
                    <input type="text" name="new_queue_name" id="new_queue_name" placeholder="queue name"/>
                    <a class="button" onclick="do_create_queue();">create queue</a>
                    <a class="button" onclick="get_queues();">list all queues</a>
                    <a class="button" onclick="purge_fakesqs();">PURGE FAKESQS</a>
                </div>
            </div>
            <div class="box" id="container_queue_list">
                <h1> queue list </h1>
                <ul id="queue_list">
                </ul>
            </div>
            <div class="box" id="message_results_box">
                <h1> messages <span id="messages_received"></span></h1>
                <ul id="message_list">
                </ul>
            </div>
        </div>
    </body>
<script>
    function get_queues() {
        // request the list of queues from this machine
        $('#queue_list').empty();
        var queues = $.getJSON( "api_list_queues.php", function(data) {
            $.each(data['QueueUrls'], function( index, value ) {
                // process the queue list if any
                queue_name = basename(value);
                queue_name_id = queue_name.replace(/\s+/g, '_');
                $('#queue_list').append(
                    $('<li>').append(
                        $('<strong>').text(queue_name)
                    ).append(
                        $('<button>').attr('class', 'button delete').data('queue_name',queue_name).append('delete queue').click(function(){do_delete_queue($(this))})
                    ).append(
                        $('<button>').attr('class', 'button purge').data('queue_name',queue_name).append('purge msgs').click(function(){do_purge_queue($(this))})
                    ).append(
                        $('<button>').attr('class', 'button read').data('queue_name',queue_name).append('read a msg').click(function(){do_read_message($(this))})
                    ).append(
                        $('<input>').attr('id', queue_name_id + '_new_message').attr('placeholder', 'message to send').data('queue_name',queue_name)
                    ).append(
                        $('<button>').attr('class', 'button send').data('queue_name',queue_name).append('send').click(function(){do_send_message($(this))})
                    )
                );
            });
        });
    }

    function do_purge_queue(object) {
        // purge a named queue
        queue_name = object.data('queue_name');
        $.getJSON( "api_purge_queue.php?queue_name=" + queue_name, function(data) { get_queues(); });
    }

    function do_delete_queue(object) {
        // delete a named queue
        queue_name = object.data('queue_name');
        $.getJSON( "api_delete_queue.php?queue_name=" + queue_name, function(data) { get_queues(); });
    }

    function do_create_queue() {
        // create a new named queue
        queue_name = $("#new_queue_name").val().toLowerCase().replace(/\s+/g, '_');
        if(queue_name != '' ) $.getJSON( "api_create_queue.php?queue_name=" + queue_name, function(data) { get_queues(); });
    }

    function do_send_message(object) {
        // send a message to a named queue
        queue_name = object.data('queue_name');
        queue_name_id = queue_name.replace(/\s+/g, '_');
        if(queue_name != '' ) {
            message_content = encodeURI($("#"+queue_name_id+"_new_message").val());
            if(message_content != '' ) {
                $.getJSON( "api_send_message.php?queue_name=" + queue_name +"&body=" + message_content, function(data) { get_queues(); });
            }
        }
    }

    function do_read_message(object) {
        // read a message from a named queue
        queue_name = object.data('queue_name');
        if(queue_name != '' ) {
            $.getJSON( "api_receive_message.php?queue_name=" + queue_name, function(data) { 
                count = 0;
                $('#message_list .new_message').removeClass('new_message');
                $.each(data['Messages'], function( index, value ) {
                    count++;
                    msg_id = value['MessageId'];
                    msg_handle = value['ReceiptHandle'];
                    msg_md5 = value['MD5OfBody'];
                    msg_body = value['Body'];
                    if ( ! $('#' + msg_handle).length ) {
                        $('#message_list').prepend(
                            $('<li>').attr('class', 'new_message').attr('id', msg_handle)
                            .append(
                                $('<span>').attr('class','message_source').text(queue_name)
                            ).append(
                                $('<br>')
                            ).append(
                                $('<strong>').text(msg_handle)
                            ).append(
                                $('<br>')
                            ).append(
                                $('<strong>').text(msg_id)
                            ).append(
                                $('<br>')
                            ).append(
                                $('<span>').attr('class','message_body').text(msg_body)
                            ).append(
                                $('<br>')
                            ).append(
                                $('<button>').attr('class', 'button delete').data('queue_name',queue_name).data('message_handle',msg_handle).append('delete').click(function(){do_delete_message($(this))})
                            ).append(
                                $('<button>').attr('class', 'button purge').data('queue_name',queue_name).data('message_handle',msg_handle).append('change visibility timeout').click(function(){do_change_visibility_timeout($(this))})
                            )
                        );
                    } else {
                        $('#' + msg_handle).addClass('new_message').parent().prepend($('#' + msg_handle));
                    }
                });
                $('#messages_received').text(count + ' received');
            });
        }
    }

    function do_delete_message(object) {
        // delete a message by ID
        msg_handle = object.data('message_handle');
        queue_name = object.data('queue_name');
        $.getJSON( "api_delete_message.php?queue_name=" + queue_name + "&message_handle=" + msg_handle, function(data) { 
            $('#message_list .new_message').removeClass('new_message');
            $('#' + msg_handle).remove() 
        });
    }

    function do_change_visibility_timeout(object) {
        // change a message timeout
        msg_handle = object.data('message_handle');
        queue_name = object.data('queue_name');
        $.getJSON( "api_visibility_timeout.php?queue_name=" + queue_name + "&message_handle=" + msg_handle, function(data) { 
            $('#message_list .new_message').removeClass('new_message');
            $('#' + msg_handle).addClass('new_message').parent().prepend($('#' + msg_handle)) 
        });
    }

    function purge_fakesqs(object) {
        $.getJSON( "api_purge_fakesqs.php", function(data) { get_queues(); });
    }

    function basename(path) {
        return path.split('/').reverse()[0];
    }



    $( document ).ready(function() {
        get_queues();
    });

</script>
</html>