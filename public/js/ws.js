let ws;

function wsConnect() {
    ws = new WebSocket("ws://localhost:1323/api/v1/ws/user");
}

function getOrderStatus(orderid, vmid) {
    console.log(orderid);
    const paylod = '{"event":"get-order","payload":{"orderid":' + orderid + ',"vmid":' + vmid + '}}'
    setInterval(function() {
        ws.send(paylod);
    }, 10000);
}

function startDespensing(orderid) {
    console.log(orderid)
    const paylod = '{"event":"start-despensing-order","payload":{"orderid":' + orderid + '}}'
    ws.send(paylod);
}

wsConnect();

ws.onmessage = function(evt) {
    obj = JSON.parse(evt.data)
    console.log(obj)
    switch (obj.Event) {
        case "get-order":
            var out = document.getElementById('your-position-inqueue');
            var outbtn = document.getElementById('start-despense-btn');
            // console.log(out);
            if (obj.Payload.quque_position == -5 && obj.Payload.total_user_queue == -5) {
                // var out = document.getElementById('your-position-inqueue');
                out.textContent = "-/-";
                outbtn.innerText = "completed"
                outbtn.disabled = true
            } else if (obj.Payload.quque_position == 1) {
                if (obj.Payload.exe_status == "executing") {
                    outbtn.innerText = "despensing..."
                    outbtn.disabled = true
                    out.textContent = obj.Payload.quque_position + "/" + obj.Payload.total_user_queue;
                } else {
                    out.textContent = obj.Payload.quque_position + "/" + obj.Payload.total_user_queue;
                    outbtn.innerText = "Start Despensing"
                    outbtn.disabled = false
                }
            } else {

                outbtn.innerText = "Waiting For Your Turn"
                outbtn.disabled = true
                out.textContent = obj.Payload.quque_position + "/" + obj.Payload.total_user_queue;

            }
            break;
        case "start-despensing-order":
            if (obj.error == null) {
                var out = document.getElementById('start-despense-btn');
                out.innerText = "despensing..."
                out.disabled = true
            }
            break;
    }
}