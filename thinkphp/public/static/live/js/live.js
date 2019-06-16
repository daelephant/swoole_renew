var wsUrl = "ws://119.28.137.51:8811";

var websocket = new WebSocket(wsUrl);

//实例对象的onopen属性
websocket.onopen = function(evt) {
    // websocket.send("hello-wesocketJs-success");
}

// 实例化 onmessage
websocket.onmessage = function(evt) {
    push(evt.data);
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

function push(data) {
    data = JSON.parse(data);
    html = '<div class="frame">\n' +
        '\t\t\t\t\t<h3 class="frame-header">\n' +
        '\t\t\t\t\t\t<i class="icon iconfont icon-shijian"></i>第'+data.type+'节 09：30\n' +
        '\t\t\t\t\t</h3>\n' +
        '\t\t\t\t\t<div class="frame-item">\n' +
        '\t\t\t\t\t\t<span class="frame-dot"></span>\n' +
        '\t\t\t\t\t\t<div class="frame-item-author">\n' ;
    if(data.logo){
        html += '\t\\t\\t\\t\\t\\t\\t<img src="+data.logo+" width="20px" height="20px"> ';
    }
    html += '\t\t\t\t\t\t\t '+data.title+'\n' +
        '\t\t\t\t\t\t</div>\n' +
        '\t\t\t\t\t\t<p>'+data.content+'</p>\n' +
        '\t\t\t\t\t</div>\n' +
        '\t\t\t\t</div>';
    $('#match-result').prepend(html);
}
