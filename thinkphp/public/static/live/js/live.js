var wsUrl = "ws://119.28.137.51:8811";

var websocket = new WebSocket(wsUrl);

//实例对象的onopen属性
websocket.onopen = function(evt) {
    websocket.send("hello-wesocketJs-success");
}

// 实例化 onmessage
websocket.onmessage = function(evt) {
    console.log("ws-server-return-data:" + evt.data);
}

//onclose
websocket.onclose = function(evt) {
    console.log("close");
}
//onerror

websocket.onerror = function(evt, e) {
    console.log("error:" + evt.data);
}
