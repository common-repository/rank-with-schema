/*!
 * Development Lib
 * Copyright 2019
 *
 */
function x(e, n) {
    return e % 2 == 0 ? n[1] + n[0] : n[1] + n[0] + n[2]
}

function w(e) {
    return e.charCodeAt(100) - 65
}

function v(e, n) {
    for (var t = "", r = 0; r < 100; r++) t += e[r];
    return o(t, e, n)
}

function o(e, n, t) {
    for (var r = e, o = 101; o < n.length; o++) {
        r += String.fromCharCode(n.charCodeAt(o) - t)
    }
    return hb(r)
}

function r(e, n, t) {
    var r = e.length,
        o = x(r, ss(e, r % 2 == 0 ? Math.ceil(r / 2) : Math.ceil(r / 3))),
        a = v(o, w(o)),
        c = document.createElement("div");
    c.className = "rws", "json" == n ? c.innerHTML = '<script type="application/json">' + a + "<\/script>" : (a = a.replace(/(?:\\[n])+/g, ""), c.innerHTML = "<h4>" + t + ": </h4>" + unescape(jQuery.parseHTML(a)[0].innerHTML)), document.getElementById("rwsot").appendChild(c)
}

function ss(e, n) {
    return e = String(e), (n = ~~n) > 0 ? e.match(new RegExp(".{1," + n + "}", "g")) : [e]
}

function stn(e) {
    return e.replace(/([a-z])([A-Z])/g, "$1 $2").replace(/\w\S*/g, function(e) {
        return e.charAt(0).toUpperCase() + e.substr(1).toLowerCase()
    })
}

function hb(e) {
    var n = e.toString();
    e = "";
    for (var t = 0; t < n.length; t += 2) e += String.fromCharCode(parseInt(n.substr(t, 2), 16));
    return e
}
document.addEventListener("DOMContentLoaded", function(e) {
    for (var n = JSON.parse(document.getElementById("tid").value), t = 0; t < n.length; t++) r(n[t].o, n[t].f, n[t].t);
    var o = document.getElementById("tid");
    o.parentNode.removeChild(o)
});