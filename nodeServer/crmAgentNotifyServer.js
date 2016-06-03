//Create context using rabbit.js, io and the subscriber socket.
var context = require('/home/developer/node_modules/rabbit.js').createContext(),
    io = require('/home/developer/node_modules/socket.io').listen("8081"),
    sub = context.socket('PULL');

//array for clients in rooms(stack)
var clients=[];

//array of rooms
var rooms=[];

//Set correct encoding
sub.setEncoding('utf8');

// Connect socket to queue
sub.connect('AgentsNotificationsQueue');

// Register handler to handle incoming data when the socket detects new data on our queues.
// When receiving data, it gets pushed to the connected websocket in room specified in message.
sub.on('data', function(data) {
    var message = JSON.parse(data);
    //get listener id on top of stack(clients[room])
    if(clients[message.data.body.AGENT])
    {
        var topSocket = clients[message.data.body.AGENT][(clients[message.data.body.AGENT]).length-1];
        if(topSocket)
        {
            io.sockets.connected[topSocket].emit(message.data.type,message.data.body);
        }
    }
});

//on connection to web socket
io.sockets.on('connection', function(socket) {

    //on disconnection of specific socket
    socket.on('disconnect', function(data) { 
        for(var i=0;i<rooms.length;i++)
        {
            var index = clients[rooms[i]].indexOf(socket.id);
            if (index > -1) {
                clients[rooms[i]].splice(index, 1); 
            }
        }
        socket.disconnect('unauthorized');  
    });

    //Register handler that handles subscription to agent room(channel)
    socket.on('subscribe', function(room) { 
        if(room!=null || typeof room!="undefined")
        {
            //create room if not exists
            if(rooms.indexOf(room)==-1)
            {
                clients[room]=new Array();
                rooms.push(room);
            }
            //join room
            socket.join(room);
            clients[room].push(socket.id);
        }
    });
       
});