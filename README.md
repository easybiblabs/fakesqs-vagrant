# opsworks-sample-php

A simple exploration tool for fakesqs and by proxy, Amazon AWS SQS

## Building
check chef recipe for the "fakesqs" repo (currently not in master but at t/fakesqs)
vagrant up --provision

## Connecting

Once vagrant is up: http://33.33.33.42/


## A quick overview of my notes


**Terms**

- **Inflight**
    A message has been "received" and is being worked on.  A message is inflight while the visibility timeout has not expired or has not been deleted.

- **Message Handle**
    When you receive a message, you get an ID and a Handle.  The ID is static identifier and does not change of the message you may use for internal purposes.  The Handle is the "_inflight_" ID and how you communicate with the message before the visibility time out expires.  For example, you use the message handle to delete or change the visibility time out.

- **Visibility Timeout**
    How long the message will remain "_inflight_" before it returns to the queue.  If work will take longer than the timeout, your worker should update the visibility timeout for the message.  This will restart the clock from that point for that many more seconds.  For example, if the queue timeout is 60 seconds and you update the timeout to 120 seconds, the clock is restarted for another 120 seconds.  Maximum timeout is 12 hours.
   

**Life cycle of a message**

1. Message sent to queue
    - Message order is not guaranteed to be FIFO
2. Message read from queue
    Messages can be read either with a simple call to _receiveMessage_ or a "long poll" using the "_WaitTimeSeconds_" parameter for the _receiveMessage_ call.

    - visibility timeout clock starts
    - work on message begins
    - IF timeout expires, message handle is invalidated and message is returned to queue
3. Work complete
    - delete message handle 


**EXAMPLE FLOW**

We have a queue named "pool_queue" with a visibility timeout of 30 seconds.

We have a polling worker reading from this queue.

A process sends message "8-ball" to the queue.

A process sends message "4-ball" to the queue.

A process sends message "6-ball" to the queue.

Queue looks like:

    - "8-ball"
    - "4-ball" 
    - "6-ball"


A polling worker receives message "4-ball" (remember FIFO is not guaranteed) with a message handle of "foobar1" (obviously fake!) at time 0

    - the visibility timeout starts
    - work starts at time 0+1
    - work finishes at time 0+20
    - worker deletes message handle "foobar1"


Queue now looks like:

    - "8-ball"
    - "6-ball"

(example of a timeout process)

A polling worker receives message "8-ball" with a message handle of "foobar2" at time 1

    - the visibility timeout starts
    - work starts at time 1+1
    - visibility timeout expired at time 1+30
        - message handle "foobar2" is invalidated
        - message "6-ball" returned to queue
    - work finishes at time 1+70
    - worker deletes message handle "foobar2"
    - nothing happens to queue

(example of extended visibility timeout process)

Queue now looks like:

    - "8-ball"
    - "6-ball"

A polling worker receives message "6-ball" with a message handle of "foobar3" at time 2

    - the visibility timeout starts
    - work starts at time 2+1
    - worker does work for time 2+10 and decides that more time is needed
        - worker steps up the visibility timeout of "foobar3" (handle of 6-ball) to 60 seconds
    - work finishes at time 1+60
    - worker deletes message handle "foobar3"
    - "6-ball" removed from queue






