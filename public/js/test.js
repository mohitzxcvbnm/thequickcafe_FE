function test() {
    // alert('test'); 
    window.location.href = "http://localhost:8080/Test";
}

var cart = { total: 0, uniqueitemcount: 0 };

if (localStorage.getItem('cart') != null && localStorage.getItem('cart') != "") {
    cart = JSON.parse(localStorage.getItem('cart')); //('cart', cart);
    console.log(cart);
}

function getCartDataFromLocal() {
    return localStorage.getItem("cart")
}


function interactWithUiElem(pagename,uiWlemName){
if(pagename == "indexsearch"){
    return false
}
return true
} 


function resetSelection(pagename) {
    let cartData = localStorage.getItem("cart")

    if (cartData == null) {
        return
    }

    let cartlocal = JSON.parse(cartData)
    Object.keys(cartlocal).forEach((item) => {
            // item
            if (item != null && item != "total" && item != "uniqueitemcount") {
                var uielem = document.getElementById("vm-page-item-count-" + item)
               if (interactWithUiElem(pagename,"")){
                uielem.textContent = "0";
               }
            }
        })
        // console.log(cart)
        if (interactWithUiElem(pagename,"")){
            document.getElementById("vm-page-cart-summary").textContent = "";
             document.getElementById("checkout-form-submit-btn").disabled = true;
        }
    cart = { total: 0, uniqueitemcount: 0 };
    localStorage.removeItem("cart")
}

function Add(uiselectorid, itemid, count, productName, price) {
    if (cart[itemid] == null) {
        cart[itemid] = { count: 0, itemName: productName, price: price, subtotal: 0 };
        cart.uniqueitemcount++;
    }

    if (cart[itemid].count == 0 && count < 0) {
        return
    }

    console.log(cart[itemid]);

    cart[itemid].count = cart[itemid].count + count;
    cart[itemid].itemName = productName;
    cart[itemid].price = price;
    cart[itemid].subtotal = cart[itemid].count * price;

    var uielem = document.getElementById(uiselectorid)
    uielem.textContent = cart[itemid].count;

    if (cart[itemid].count == 0) {
        cart.uniqueitemcount--;
        delete cart[itemid];
    }

    cart.total += count * price;

    jsondata = JSON.stringify(cart)

    document.getElementById("checkout-form-data").setAttribute('value', jsondata);
    if (cart.uniqueitemcount == 0 && cart.total == 0) {
        document.getElementById("vm-page-cart-summary").textContent = "";
        document.getElementById("checkout-form-submit-btn").disabled = true;
    } else {
        document.getElementById("vm-page-cart-summary").textContent = cart.uniqueitemcount + " | " + cart.total;
        document.getElementById("checkout-form-submit-btn").disabled = false;

    }
    localStorage.setItem('cart', jsondata);
    //document.cookie = 'cart='+JSON.stringify(cart)+";"
    console.log(cart);
}

function SearchVm(inputid, func,apiurl,baseurl) {
    ele = document.getElementById(inputid);

    const xhr = new XMLHttpRequest();
    console.log(ele.value);
    xhr.open("GET",
        apiurl+"/api/v1/vm/search?phrase=" + ele.value, false);

    xhr.onload = function() {
        console.log("ok");
        if (this.status === 200) {
            obj = JSON.parse(this.responseText);
            func(obj.Data,baseurl)
        } else {
            console.log("search failed");
        }
    }
    xhr.send();
}


fillSearchResult = (data,baseurl) => {
    document.getElementById('search-result-ul').innerHTML = "";
    for (let i = 0; i < data.length; i++) {
        var e = document.createElement("li");
        e.classList.add("search-li");
        if (i == data.length - 1) {
            // .search-last
            e.classList.add("search-li-last");
        } else if (i == 0) {
            e.classList.add("search-li-first");
        }
        a = document.createElement("a");

        a.appendChild(document.createTextNode(data[i].name));
        a.href = baseurl+'/VendingMachine/' + data[i].id;

        e.appendChild(a);
        document.getElementById('search-result-ul').appendChild(e)
    }
}


function setOrderForCheck(orderId,vmId){
    localStorage.setItem("lastorder",orderId+"_"+vmId)
}