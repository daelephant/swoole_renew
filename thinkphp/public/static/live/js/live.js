var wsUrl = "ws://119.28.137.51:8811";

var websocket = new WebSocket(wsUrl);

//实例对象的onopen属性
websocket.onopen = function(evt) {
    // websocket.send("live-wesocketJs-success");
}
function push(data) {
    data = JSON.parse(data);
    html = '<div class="frame">';
    html += '<h3 class="frame-header">';
    html += '<i class="icon iconfont icon-shijian"></i>第'+data.type+'节 09：30' ;
    html += '</h3>' ;
    html += '<div class="frame-item">';
    html += '<span class="frame-dot"></span>';
    html += '<div class="frame-item-author">' ;
    if(data.logo){
        html += '<img src="'+data.logo+'" width="20px" height="20px" /> ';
    }
    html += data.title;
    html += '</div>';
    html += '<p>'+data.content+'</p>';
    if(data.image){
        html += '<p><img src="'+data.image+'" width="40%"></p>';
    }
    html += '</div>';
    html += '</div>';
    $('#match-result').prepend(html);
    console.log("into:" + data.content);
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
    html = '<div class="frame">';
    html += '<h3 class="frame-header">';
    html += '<i class="icon iconfont icon-shijian"></i>第'+data.type+'节 09：30' ;
    html += '</h3>' ;
    html += '<div class="frame-item">';
    html += '<span class="frame-dot"></span>';
    html += '<div class="frame-item-author">' ;
    if(data.logo){
        html += '<img src="'+data.logo+'" width="20px" height="20px" /> ';
    }
    html += data.title;
    html += '</div>';
    html += '<p>'+data.content+'</p>';
    if(data.image){
        html += '<p><img src="'+data.image+'" width="40%"></p>';
    }
    html += '</div>';
    html += '</div>';
    $('#match-result').prepend(html);
    console.log("into:" + data.content);
}
