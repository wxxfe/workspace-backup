define(function(){

    var BfWebSocket = function(config){

        this.url = config.url || '';

        this.onOpen = config.onOpen || function(){};

        this.onError = config.onError || function(){};

        this.onClose = config.onClose || function(){};

        this.onMessage = config.onMessage || function(){};

        this.socket = null;

        this.init();

    }

    BfWebSocket.prototype.init = function(){

        this.connect();
        this.socket.addEventListener('open',this.onOpen);
        this.socket.addEventListener('error',this.onError);
        this.socket.addEventListener('close',this.onClose);
        this.socket.addEventListener('message',this.onMessage);

    }

    BfWebSocket.prototype.connect = function(){

        this.socket = new WebSocket(this.url);

    }

    BfWebSocket.prototype.send = function(msg){
        
        this.socket.send(msg);

    }

    BfWebSocket.prototype.close = function(){
        
        this.socket.close();

    }

    return BfWebSocket;

});
