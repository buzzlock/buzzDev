/* (c) 2008, 2009, 2010 Add This, LLC */

if (!window._ate) {
    var _atd = "www.addthis.com/",
        _atr = "//s7.addthis.com/",
        _atn = "//l.addthiscdn.com/",
        _euc = encodeURIComponent,
        _duc = decodeURIComponent,
        _atc = {
            dr: 0,
            ver: 250,
            loc: 0,
            enote: "",
            cwait: 500,
            tamp: 0.5,
            xamp: 0,
            camp: 1,
            vamp: 1,
            famp: 0.02,
            pamp: 0.2,
            damp: 1,
            abf: !! window.addthis_do_ab,
            unt: 1
        };
    (function () {
        var y;
        try {
            y = window.location;
            if (y.protocol.indexOf("file") === 0 || y.protocol.indexOf("safari-extension") === 0 || y.protocol.indexOf("chrome-extension") === 0) {
                _atr = "http:" + _atr
            }
            if (y.hostname.indexOf("localhost") != -1) {
                _atc.loc = 1
            }
        } catch (D) {}
        var B = navigator.userAgent.toLowerCase(),
            E = document,
            p = window,
            A = E.location,
            H = {
                win: /windows/.test(B),
                xp: /windows nt 5.1/.test(B) || /windows nt 5.2/.test(B),
                osx: /os x/.test(B),
                chr: /chrome/.test(B),
                iph: /iphone/.test(B),
                dro: /android/.test(B),
                ipa: /ipad/.test(B),
                saf: /safari/.test(B),
                sf3: /safari 3/.test(B),
                web: /webkit/.test(B),
                opr: /opera/.test(B),
                msi: (/msie/.test(B)) && !(/opera/.test(B)),
                ffx: /firefox/.test(B),
                ff2: /firefox\/2/.test(B),
                ffn: /firefox\/((3.[6789][0-9a-z]*)|(4.[0-9a-z]*))/.test(B),
                ie6: /msie 6.0/.test(B),
                ie7: /msie 7.0/.test(B),
                mod: -1
            },
            f = {
                rev: "88088",
                bro: H,
                wlp: (y || {}).protocol,
                show: 1,
                dl: A,
                upm: !! p.postMessage && ("" + p.postMessage).toLowerCase().indexOf("[native code]") !== -1,
                camp: _atc.camp - Math.random(),
                xamp: _atc.xamp - Math.random(),
                vamp: _atc.vamp - Math.random(),
                tamp: _atc.tamp - Math.random(),
                pamp: _atc.pamp - Math.random(),
                ab: "-",
                inst: 1,
                wait: 500,
                tmo: null,
                sub: !! window.at_sub,
                dbm: 0,
                uid: null,
                spt: "static/r07/widget26.png",
                api: {},
                imgz: [],
                hash: window.location.hash
            };
        E.ce = E.createElement;
        E.gn = E.getElementsByTagName;
        window._ate = f;
        var q = function (L, r, w, d) {
            if (!L) {
                return w
            }
            if (L instanceof Array || (L.length && (typeof L !== "function"))) {
                for (var l = 0, a = L.length, b = L[0]; l < a; b = L[++l]) {
                    w = r.call(d || L, w, b, l, L)
                }
            } else {
                for (var e in L) {
                    w = r.call(d || L, w, L[e], e, L)
                }
            }
            return w
        },
            t = Array.prototype.slice,
            v = function (b) {
                return t.apply(b, t.call(arguments, 1))
            },
            u = function (a) {
                return ("" + a).replace(/(^\s+|\s+$)/g, "")
            },
            C = function (a, b) {
                return q(v(arguments, 1), function (e, d) {
                    return q(d, function (w, r, l) {
                        if (w) {
                            w[l] = r
                        }
                        return w
                    }, e)
                }, a)
            },
            m = function (b, a) {
                return q(b, function (l, e, d) {
                    d = u(d);
                    if (d) {
                        l.push(_euc(d) + "=" + _euc(u(e)))
                    }
                    return l
                }, []).join(a || "&")
            },
            j = function (b, a) {
                return q((b || "").split(a || "&"), function (w, M) {
                    try {
                        var r = M.split("="),
                            l = u(_duc(r[0])),
                            d = u(_duc(r.slice(1).join("=")));
                        if (l) {
                            w[l] = d
                        }
                    } catch (L) {}
                    return w
                }, {})
            },
            K = function () {
                var a = v(arguments, 0),
                    d = a.shift(),
                    b = a.shift();
                return function () {
                    return d.apply(b, a.concat(v(arguments, 0)))
                }
            },
            z = function (b, e, a, d) {
                if (!e) {
                    return
                }
                if (we) {
                    e[(b ? "detach" : "attach") + "Event"]("on" + a, d)
                } else {
                    e[(b ? "remove" : "add") + "EventListener"](a, d, false)
                }
            },
            k = function (d, a, b) {
                z(0, d, a, b)
            },
            g = function (d, a, b) {
                z(1, d, a, b)
            },
            c = {
                reduce: q,
                slice: v,
                strip: u,
                extend: C,
                toKV: m,
                fromKV: j,
                bind: K,
                listen: k,
                unlisten: g
            };
        f.util = c;
        C(f, c);
        (function (M, O, P) {
            var w, R = M.util;

            function N(U, T, W, S, V) {
                this.type = U;
                this.triggerType = T || U;
                this.target = W || S;
                this.triggerTarget = S || W;
                this.data = V || {}
            }
            R.extend(N.prototype, {
                constructor: N,
                bubbles: false,
                preventDefault: R.noop,
                stopPropagation: R.noop,
                clone: function () {
                    return new this.constructor(this.type, this.triggerType, this.target, this.triggerTarget, R.extend({}, this.data))
                }
            });

            function l(S, T) {
                this.target = S;
                this.queues = {};
                this.defaultEventType = T || N
            }
            function a(S) {
                var T = this.queues;
                if (!T[S]) {
                    T[S] = []
                }
                return T[S]
            }
            function L(S, T) {
                this.getQueue(S).push(T)
            }
            function e(T, U) {
                var V = this.getQueue(T),
                    S = V.indexOf(U);
                if (S !== -1) {
                    V.splice(S, 1)
                }
            }
            function b(S, W, V, U) {
                var T = this;
                if (!U) {
                    setTimeout(function () {
                        T.dispatchEvent(new T.defaultEventType(S, S, W, T.target, V))
                    }, 10)
                } else {
                    T.dispatchEvent(new T.defaultEventType(S, S, W, T.target, V))
                }
            }
            function Q(T) {
                for (var U = 0, W = T.target, V = this.getQueue(T.type), S = V.length; U < S; U++) {
                    V[U].call(W, T.clone())
                }
            }
            function d(T) {
                if (!T) {
                    return
                }
                for (var S in r) {
                    T[S] = R.bind(r[S], this)
                }
                return T
            }
            var r = {
                constructor: l,
                getQueue: a,
                addEventListener: L,
                removeEventListener: e,
                dispatchEvent: Q,
                fire: b,
                decorate: d
            };
            R.extend(l.prototype, r);
            M.event = {
                PolyEvent: N,
                EventDispatcher: l
            }
        })(f, f.api, f);
        f.ed = new f.event.EventDispatcher(f);
        var n = {
            isBound: 0,
            isReady: 0,
            readyList: [],
            onReady: function () {
                if (!n.isReady) {
                    n.isReady = 1;
                    var a = n.readyList.concat(window.addthis_onload || []);
                    for (var b = 0; b < a.length; b++) {
                        a[b].call(window)
                    }
                    n.readyList = []
                }
            },
            addLoad: function (a) {
                var b = p.onload;
                if (typeof p.onload != "function") {
                    p.onload = a
                } else {
                    p.onload = function () {
                        if (b) {
                            b()
                        }
                        a()
                    }
                }
            },
            bindReady: function () {
                if (s.isBound || _atc.xol) {
                    return
                }
                s.isBound = 1;
                if (E.addEventListener && !H.opr) {
                    E.addEventListener("DOMContentLoaded", s.onReady, false)
                }
                var a = window.addthis_product;
                if (a && a.indexOf("f") > -1) {
                    s.onReady();
                    return
                }
                if (H.msi && window == top) {
                    (function () {
                        if (s.isReady) {
                            return
                        }
                        try {
                            E.documentElement.doScroll("left")
                        } catch (d) {
                            setTimeout(arguments.callee, 0);
                            return
                        }
                        s.onReady()
                    })()
                }
                if (H.opr) {
                    E.addEventListener("DOMContentLoaded", function () {
                        if (s.isReady) {
                            return
                        }
                        for (var d = 0; d < E.styleSheets.length; d++) {
                            if (E.styleSheets[d].disabled) {
                                setTimeout(arguments.callee, 0);
                                return
                            }
                        }
                        s.onReady()
                    }, false)
                }
                if (H.saf) {
                    var b;
                    (function () {
                        if (s.isReady) {
                            return
                        }
                        if (E.readyState != "loaded" && E.readyState != "complete") {
                            setTimeout(arguments.callee, 0);
                            return
                        }
                        if (b === undefined) {
                            var d = E.gn("link");
                            for (var e = 0; e < d.length; e++) {
                                if (d[e].getAttribute("rel") == "stylesheet") {
                                    b++
                                }
                            }
                            var l = E.gn("style");
                            b += l.length
                        }
                        if (E.styleSheets.length != b) {
                            setTimeout(arguments.callee, 0);
                            return
                        }
                        s.onReady()
                    })()
                }
                s.addLoad(s.onReady)
            },
            append: function (b, a) {
                s.bindReady();
                if (s.isReady) {
                    b.call(window, [])
                } else {
                    s.readyList.push(function () {
                        return b.call(window, [])
                    })
                }
            }
        },
            s = n,
            J = f;
        C(f, {
            plo: [],
            lad: function (a) {
                f.plo.push(a)
            }
        });
        (function (d, l, e) {
            var a = window;
            d.pub = function () {
                return _euc((window.addthis_config || {}).username || window.addthis_pub || "")
            };
            d.usu = function (r, w) {
                if (!a.addthis_share) {
                    a.addthis_share = {}
                }
                if (w || r != addthis_share.url) {
                    addthis_share.imp_url = 0
                }
            };
            d.rsu = function () {
                var L = document,
                    w = L.title,
                    r = L.location ? L.location.href : "";
                if (_atc.ver >= 250 && addthis_share.imp_url && r && r != a.addthis_share.url && !(f.util.ivc((L.location.hash || "").substr(1).split(",").shift()))) {
                    a.addthis_share.url = a.addthis_url = r;
                    a.addthis_share.title = a.addthis_title = w;
                    return 1
                }
                return 0
            };
            d.igv = function (r, w) {
                if (!a.addthis_config) {
                    a.addthis_config = {
                        username: a.addthis_pub
                    }
                } else {
                    if (addthis_config.data_use_cookies === false) {
                        _atc.xck = 1
                    }
                }
                if (!a.addthis_share) {
                    a.addthis_share = {}
                }
                if (!addthis_share.url) {
                    if (!a.addthis_url && addthis_share.imp_url === undefined) {
                        addthis_share.imp_url = 1
                    }
                    addthis_share.url = (a.addthis_url || r || "").split("#{").shift()
                }
                if (!addthis_share.title) {
                    addthis_share.title = (a.addthis_title || w || "").split("#{").shift()
                }
            };
            if (!_atc.ost) {
                if (!a.addthis_conf) {
                    a.addthis_conf = {}
                }
                for (var b in addthis_conf) {
                    _atc[b] = addthis_conf[b]
                }
                _atc.ost = 1
            }
        })(f, f.api, f);
        (function (b, r, e) {
            var L, w = document,
                a = b.util;
            b.ckv = a.fromKV(w.cookie, ";");

            function l(d) {
                return a.fromKV(w.cookie, ";")[d]
            }
            if (!b.cookie) {
                b.cookie = {}
            }
            b.cookie.rck = l
        })(f, f.api, f);
        (function (b, e, l) {
            var a, L = document,
                w = 0,
                P = b.util;

            function M() {
                if (w) {
                    return 1
                }
                N("xtc", 1);
                if (1 == b.cookie.rck("xtc")) {
                    w = 1
                }
                r("xtc", 1);
                return w
            }
            function O(R) {
                if (_atc.xck) {
                    return
                }
                var Q = R || f.dh || f.du || (f.dl ? f.dl.hostname : "");
                if (Q.indexOf(".gov") > -1 || Q.indexOf(".mil") > -1) {
                    _atc.xck = 1
                }
                var S = typeof(b.pub) === "function" ? b.pub() : b.pub,
                    d = ["usarmymedia", "govdelivery"];
                for (i in d) {
                    if (S == d[i]) {
                        _atc.xck = 1;
                        break
                    }
                }
            }
            function r(Q, d) {
                if (L.cookie) {
                    L.cookie = Q + "=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/" + (d ? "; domain=" + (b.bro.msi ? "" : ".") + "addthis.com" : "")
                }
            }
            function N(R, Q, S, T) {
                O();
                if (!_atc.xck) {
                    var d = new Date();
                    d.setYear(d.getFullYear() + 2);
                    document.cookie = R + "=" + Q + (!S ? "; expires=" + d.toUTCString() : "") + "; path=/;" + (!T ? " domain=" + (b.bro.msi ? "" : ".") + "addthis.com" : "")
                }
            }
            if (!b.cookie) {
                b.cookie = {}
            }
            b.cookie.sck = N;
            b.cookie.kck = r;
            b.cookie.cww = M;
            b.cookie.gov = O
        })(f, f.api, f);
        (function (b, e, d) {
            function a(w) {
                var l = 291;
                if (w) {
                    for (var r = 0; r < w.length; r++) {
                        l = (l * (w.charCodeAt(r) + r) + 3) & 1048575
                    }
                }
                return (l & 16777215).toString(32)
            }
            b.mun = a
        })(f, f.api, f);
        (function (e, l, w) {
            var d, O = e.util,
                M = 4294967295,
                b = new Date().getTime();

            function L() {
                return ((b / 1000) & M).toString(16) + ("00000000" + (Math.floor(Math.random() * (M + 1))).toString(16)).slice(-8)
            }
            function a(P) {
                return N(P) ? (new Date((parseInt(P.substr(0, 8), 16) * 1000))) : new Date()
            }
            function r(P, R) {
                var Q = a(P);
                return (((new Date()).getTime() - Q.getTime()) > R * 1000)
            }
            function N(P) {
                return P && P.match(/^[0-9a-f]{16}$/)
            }
            O.cuid = L;
            O.ivc = N;
            O.ioc = r
        })(f, f.api, f);
        (function (a, e, d) {
            function l(r) {
                var w = r.src.indexOf("#") > -1 ? r.src.replace(/^[^\#]+\#?/, "") : r.src.replace(/^[^\?]+\??/, ""),
                    L = j(w);
                return L
            }
            function b(N) {
                var w = E.gn("script"),
                    O = w.length,
                    L = w[O - 1],
                    M = l(L);
                if (N || (L.src && L.src.indexOf("addthis") == -1)) {
                    for (var r = 0; r < O; r++) {
                        if ((w[r].src || "").indexOf(N || "addthis.com") > -1) {
                            M = l(w[r]);
                            break
                        }
                    }
                }
                return M
            }
            if (!a.util) {
                a.util = {}
            }
            a.util.gsp = b
        })(f, f.api, f);
        (function (r, X, Z) {
            var aa = r,
                O = new Date().getTime(),
                W = function () {
                    return Math.floor(Math.random() * 4294967295).toString(36)
                },
                ab = function () {
                    return Math.floor((new Date().getTime() - O) / 100).toString(16)
                },
                N = function (a) {
                    return "CXNID=2000001.521545608054043907" + (a || 2) + "NXC"
                },
                w = 0,
                M = function (a) {
                    if (w === 0) {
                        aa.sid = w = (a || aa.util.cuid())
                    }
                    return w
                },
                e = null,
                d = function (a, ac) {
                    if (e !== null) {
                        clearTimeout(e)
                    }
                    if (a) {
                        e = f.sto(function () {
                            ac(false)
                        }, f.wait)
                    }
                },
                T = function (ac, a) {
                    return _euc(ac) + "=" + _euc(a) + ";" + ab()
                },
                S = 1,
                L = function (ac, ae) {
                    var a = (ac || "").split("?"),
                        ac = a.shift(),
                        ad = (a.pop() || "").split("&");
                    return ae(ac, ad)
                },
                P = function (a, ac, ae, ad) {
                    if (!ac) {
                        ac = {}
                    }
                    if (!ac.remove) {
                        ac.remove = []
                    }
                    ac.remove.push("sms_ss");
                    ac.remove.push("at_xt");
                    if (ac.remove) {
                        a = Y(a, ac.remove)
                    }
                    if (ac.clean) {
                        a = Q(a)
                    }
                    if (ac.defrag) {
                        a = l(a)
                    }
                    if (ac.add) {
                        a = R(a, ac.add, ae, ad)
                    }
                    return a
                },
                R = function (ae, ag, af, ac) {
                    var a = {};
                    if (ag) {
                        for (var ad in ag) {
                            if (ae.indexOf(ad + "=") > -1) {
                                continue
                            }
                            a[ad] = U(ag[ad], ae, af, ac)
                        }
                        ag = f.util.toKV(a)
                    }
                    return ae + (ag.length ? ((ae.indexOf("?") > -1 ? "&" : "?") + ag) : "")
                },
                U = function (ad, ac, ae, a) {
                    var ae = ae || addthis_share;
                    return ad.replace(/{{service}}/g, _euc(a || "")).replace(/{{code}}/g, _euc(a || "")).replace(/{{title}}/g, _euc(ae.title)).replace(/{{url}}/g, _euc(ac))
                },
                Y = function (ac, ae) {
                    var a = {},
                        ae = ae || [];
                    for (var ad = 0; ad < ae.length; ad++) {
                        a[ae[ad]] = 1
                    }
                    return L(ac, function (af, ai) {
                        var aj = [];
                        if (ai) {
                            for (var ag in ai) {
                                if (typeof(ai[ag]) == "string") {
                                    var ah = (ai[ag] || "").split("=");
                                    if (ah.length != 2 && ai[ag]) {
                                        aj.push(ai[ag])
                                    } else {
                                        if (a[ah[0]]) {
                                            continue
                                        } else {
                                            if (ai[ag]) {
                                                aj.push(ai[ag])
                                            }
                                        }
                                    }
                                }
                            }
                            af += (aj.length ? ("?" + aj.join("&")) : "")
                        }
                        return af
                    })
                },
                V = function (a) {
                    var ac = a.split("#").pop().split(",").shift().split("=").pop();
                    if (f.util.ivc(ac)) {
                        return a.split("#").pop().split(",")
                    }
                    return [""]
                },
                l = function (a) {
                    var ac = V(a).shift().split("=").pop();
                    if (f.util.ivc(ac)) {
                        return a.split("#").shift()
                    }
                    return a
                },
                Q = function (a) {
                    return L(a, function (ad, ag) {
                        var ac = ad.indexOf(";jsessionid"),
                            ah = [];
                        if (ac > -1) {
                            ad = ad.substr(0, ac)
                        }
                        if (ag) {
                            for (var ae in ag) {
                                if (typeof(ag[ae]) == "string") {
                                    var af = (ag[ae] || "").split("=");
                                    if (af.length == 2) {
                                        if (af[0].indexOf("utm_") === 0 || af[0] == "gclid" || af[0] == "sms_ss" || af[0] == "at_xt") {
                                            continue
                                        }
                                    }
                                    if (ag[ae]) {
                                        ah.push(ag[ae])
                                    }
                                }
                            }
                            ad += (ah.length ? ("?" + ah.join("&")) : "")
                        }
                        return ad
                    })
                },
                b = function () {
                    var a = (typeof(aa.pub || "") == "function" ? aa.pub() : aa.pub) || "unknown";
                    return "AT-" + a + "/-/" + aa.ab + "/" + M() + "/" + (S++) + (aa.uid !== null ? "/" + aa.uid : "")
                };
            if (!f.track) {
                f.track = {}
            }
            r.util.extend(f.track, {
                cst: N,
                fcv: T,
                ran: W,
                rup: Y,
                aup: R,
                cof: l,
                gof: V,
                clu: Q,
                mgu: P,
                ssid: M,
                sta: b,
                sxm: d
            })
        })(f, f.api, f);
        (function () {
            var V = document,
                W = f,
                M = [],
                L = null,
                Q = [],
                N = function () {
                    var a;
                    while (a = Q.pop()) {
                        U(a)
                    }
                },
                l = [],
                S = null,
                T = function (d) {
                    d = d.split("-").shift();
                    for (var a = 0; a < l.length; a++) {
                        if (l[a] == d) {
                            return
                        }
                    }
                    l.push(d)
                },
                O = function () {},
                X = null,
                w = function () {
                    var a = V.getElementById("_atssh");
                    if (!a) {
                        a = V.ce("div");
                        a.style.visibility = "hidden";
                        a.id = "_atssh";
                        W.opp(a.style);
                        V.body.insertBefore(a, V.body.firstChild)
                    }
                    return a
                },
                b = function (a) {
                    var aa, d = Math.floor(Math.random() * 1000),
                        Z = w();
                    if (!W.bro.msi) {
                        aa = V.ce("iframe");
                        aa.id = "_atssh" + d
                    } else {
                        if (W.bro.ie6 && !a && V.location.protocol.indexOf("https") == 0) {
                            a = "javascript:''"
                        }
                        Z.innerHTML = '<iframe id="_atssh' + d + '" width="1" height="1" name="_atssh' + d + '" ' + (a ? 'src="' + a + '"' : "") + ">";
                        aa = V.getElementById("_atssh" + d)
                    }
                    W.opp(aa.style);
                    aa.frameborder = aa.style.border = 0;
                    aa.style.top = aa.style.left = 0;
                    return aa
                },
                P = function (a) {
                    if (a && a.data && a.data.service) {
                        if (!W.upm) {
                            if (W.dcp) {
                                return
                            }
                            W.dcp = 1
                        }
                        U({
                            gen: 300,
                            sh: a.data.service
                        })
                    }
                },
                e = function (d) {
                    var aa = {},
                        ad = d.data || {},
                        ac = ad.svc,
                        a = ad.pco,
                        ae = ad.cmo,
                        ab = ad.crs,
                        Z = ad.cso;
                    if (ac) {
                        aa.sh = ac
                    }
                    if (ae) {
                        aa.cm = ae
                    }
                    if (Z) {
                        aa.cs = 1
                    }
                    if (ab) {
                        aa.cr = 1
                    }
                    if (a) {
                        aa.spc = a
                    }
                    Y("sh", "3", null, aa)
                },
                U = function (d) {
                    var aa = W.dr,
                        a = (W.rev || "");
                    if (!d) {
                        return
                    }
                    d.xck = _atc.xck ? 1 : 0;
                    d.xxl = 1;
                    d.sid = W.track.ssid();
                    d.pub = W.pub();
                    d.ssl = W.ssl || 0;
                    d.du = W.tru(W.du || W.dl.href);
                    if (W.dt) {
                        d.dt = W.dt
                    }
                    if (W.cb) {
                        d.cb = W.cb
                    }
                    d.lng = W.lng();
                    d.ver = _atc.ver;
                    if (!W.upm && W.uid) {
                        d.uid = W.uid
                    }
                    d.pc = d.spc || l.join(",");
                    if (aa) {
                        d.dr = W.tru(aa)
                    }
                    if (W.dh) {
                        d.dh = W.dh
                    }
                    if (a) {
                        d.rev = a
                    }
                    if (W.xfr) {
                        if (W.upm) {
                            if (X) {
                                X.contentWindow.postMessage(m(d), "*")
                            }
                        } else {
                            var ab = w(),
                                Z = "static/r07/sh30.html" + (false ? "?t=" + new Date().getTime() : "");
                            if (X) {
                                ab.removeChild(ab.firstChild)
                            }
                            X = b();
                            X.src = _atr + Z + "#" + m(d);
                            ab.appendChild(X)
                        }
                    } else {
                        Q.push(d)
                    }
                },
                Y = function (Z, ac, a, aa, ab) {
                    if (!window.at_sub && !_atc.xtr) {
                        var d = aa || {};
                        d.evt = Z;
                        if (a) {
                            d.ext = a
                        }
                        L = d;
                        if (ab === 1) {
                            r(true)
                        } else {
                            W.track.sxm(true, r)
                        }
                    }
                },
                R = function (d, a) {
                    M.push(W.track.fcv(d, a));
                    W.track.sxm(true, r)
                },
                r = function (aa) {
                    var Z = W.dl ? W.dl.hostname : "";
                    if (M.length > 0 || L) {
                        W.track.sxm(false, r);
                        if (_atc.xtr) {
                            return
                        }
                        var d = L || {};
                        d.ce = M.join(",");
                        M = [];
                        L = null;
                        U(d);
                        if (aa) {
                            var a = V.ce("iframe");
                            a.id = "_atf";
                            f.opp(a.style);
                            V.body.appendChild(a);
                            a = V.getElementById("_atf")
                        }
                    }
                };
            W.ed.addEventListener("addthis-internal.compact", e);
            W.ed.addEventListener("addthis.menu.share", P);
            if (!W.track) {
                W.track = {}
            }
            W.util.extend(W.track, {
                pcs: l,
                apc: T,
                cev: R,
                ctf: b,
                gtf: w,
                qtp: function (a) {
                    Q.push(a)
                },
                stf: function (a) {
                    X = a
                },
                trk: U,
                xtp: N
            })
        })();
        C(f, {
            _rec: [],
            xfr: !f.upm || !f.bro.ffx,
            pmh: function (d) {
                if (d.origin.slice(-12) == ".addthis.com") {
                    if (!d.data) {
                        return
                    }
                    var b = j(d.data),
                        a = f._rec;
                    for (var l = 0; l < a.length; l++) {
                        a[l](b)
                    }
                }
            }
        });
        C(f, {
            lng: function () {
                return window.addthis_language || (window.addthis_config || {}).ui_language || (f.bro.msi ? navigator.userLanguage : navigator.language) || "en"
            },
            iwb: function (a) {
                var b = {
                    th: 1,
                    pl: 1,
                    sl: 1,
                    gl: 1,
                    hu: 1,
                    is: 1,
                    nb: 1,
                    se: 1,
                    su: 1,
                    sw: 1
                };
                return !!b[a]
            },
            ivl: function (a) {
                var b = {
                    af: 1,
                    afr: "af",
                    ar: 1,
                    ara: "ar",
                    az: 1,
                    aze: "az",
                    be: 1,
                    bye: "be",
                    bg: 1,
                    bul: "bg",
                    bn: 1,
                    ben: "bn",
                    bs: 1,
                    bos: "bs",
                    ca: 1,
                    cat: "ca",
                    cs: 1,
                    ces: "cs",
                    cze: "cs",
                    cy: 1,
                    cym: "cy",
                    da: 1,
                    dan: "da",
                    de: 1,
                    deu: "de",
                    ger: "de",
                    el: 1,
                    gre: "el",
                    ell: "ell",
                    en: 1,
                    eo: 1,
                    es: 1,
                    esl: "es",
                    spa: "spa",
                    et: 1,
                    est: "et",
                    eu: 1,
                    fa: 1,
                    fas: "fa",
                    per: "fa",
                    fi: 1,
                    fin: "fi",
                    fo: 1,
                    fao: "fo",
                    fr: 1,
                    fra: "fr",
                    fre: "fr",
                    ga: 1,
                    gae: "ga",
                    gdh: "ga",
                    gl: 1,
                    glg: "gl",
                    gu: 1,
                    he: 1,
                    heb: "he",
                    hi: 1,
                    hin: "hin",
                    hr: 1,
                    ht: 1,
                    cro: "hr",
                    hu: 1,
                    hun: "hu",
                    id: 1,
                    ind: "id",
                    is: 1,
                    ice: "is",
                    it: 1,
                    ita: "it",
                    ja: 1,
                    jpn: "ja",
                    ko: 1,
                    kor: "ko",
                    ku: 1,
                    lb: 1,
                    ltz: "lb",
                    lt: 1,
                    lit: "lt",
                    lv: 1,
                    lav: "lv",
                    mk: 1,
                    mac: "mk",
                    mak: "mk",
                    ml: 1,
                    mn: 1,
                    ms: 1,
                    msa: "ms",
                    may: "ms",
                    nb: 1,
                    nl: 1,
                    nla: "nl",
                    dut: "nl",
                    no: 1,
                    nds: 1,
                    nn: 1,
                    nno: "no",
                    oc: 1,
                    oci: "oc",
                    pl: 1,
                    pol: "pl",
                    ps: 1,
                    pt: 1,
                    por: "pt",
                    ro: 1,
                    ron: "ro",
                    rum: "ro",
                    ru: 1,
                    rus: "ru",
                    sk: 1,
                    slk: "sk",
                    slo: "sk",
                    sl: 1,
                    slv: "sl",
                    sq: 1,
                    alb: "sq",
                    sr: 1,
                    se: 1,
                    si: 1,
                    ser: "sr",
                    su: 1,
                    sv: 1,
                    sve: "sv",
                    sw: 1,
                    swe: "sv",
                    ta: 1,
                    tam: "ta",
                    te: 1,
                    teg: "te",
                    th: 1,
                    tha: "th",
                    tl: 1,
                    tgl: "tl",
                    tn: 1,
                    tr: 1,
                    tur: "tr",
                    tt: 1,
                    uk: 1,
                    ukr: "uk",
                    ur: 1,
                    urd: "ur",
                    vi: 1,
                    vec: 1,
                    vie: "vi",
                    "zh-hk": 1,
                    "chi-hk": "zh-hk",
                    "zho-hk": "zh-hk",
                    "zh-tr": 1,
                    "chi-tr": "zh-tr",
                    "zho-tr": "zh-tr",
                    "zh-tw": 1,
                    "chi-tw": "zh-tw",
                    "zho-tw": "zh-tw",
                    zh: 1,
                    chi: "zh",
                    zho: "zh"
                };
                if (b[a]) {
                    return b[a]
                }
                a = a.split("-").shift();
                if (b[a]) {
                    if (b[a] === 1) {
                        return a
                    } else {
                        return b[a]
                    }
                }
                return 0
            },
            gvl: function (a) {
                var b = f.ivl(a) || "en";
                if (b === 1) {
                    b = a
                }
                return b
            },
            alg: function (d, b) {
                var a = f.gvl((d || f.lng()).toLowerCase());
                if (a.indexOf("en") !== 0 && (!f.pll || b)) {
                    f.pll = f.ajs("static/r07/lang09/" + a + ".js")
                }
            }
        });
        C(f, {
            trim: function (a, b) {
                try {
                    a = a.replace(/^[\s\u3000]+|[\s\u3000]+$/g, "");
                    if (b) {
                        a = _euc(a)
                    }
                } catch (b) {}
                return a || ""
            },
            trl: [],
            tru: function (b, a) {
                var r = "",
                    e = 0,
                    l = -1;
                if (b) {
                    r = b.substr(0, 300);
                    if (r !== b) {
                        if ((l = r.lastIndexOf("%")) >= r.length - 4) {
                            r = r.substr(0, l)
                        }
                        if (r != b) {
                            for (var d in f.trl) {
                                if (f.trl[d] == a) {
                                    e = 1
                                }
                            }
                            if (!e) {
                                f.trl.push(a)
                            }
                        }
                    }
                }
                return r
            },
            sto: function (b, a) {
                return setTimeout(b, a)
            },
            opp: function (a) {
                a.width = a.height = "1px";
                a.position = "absolute";
                a.zIndex = 100000
            },
            jlr: {},
            ajs: function (b, a) {
                if (!f.jlr[b]) {
                    var e = E.ce("script"),
                        d = E.gn("head")[0] || E.documentElement;
                    e.src = (a ? "" : _atr) + b;
                    d.insertBefore(e, d.firstChild);
                    f.jlr[b] = 1;
                    return e
                }
                return 1
            },
            jlo: function () {
                try {
                    var L = document,
                        b = f,
                        w = b.lng(),
                        l = function (d) {
                            var a = new Image();
                            f.imgz.push(a);
                            a.src = d
                        };
                    b.alg(w);
                    if (!b.pld) {
                        if (b.bro.ie6) {
                            l(_atr + b.spt);
                            l(_atr + "static/t00/logo1414.gif");
                            l(_atr + "static/t00/logo88.gif");
                            if (window.addthis_feed) {
                                l("static/r05/feed00.gif", 1)
                            }
                        }
                        if (b.pll && !window.addthis_translations) {
                            b.sto(function () {
                                b.pld = b.ajs("static/r07/menu66.js")
                            }, 10)
                        } else {
                            b.pld = b.ajs("static/r07/menu66.js")
                        }
                    }
                } catch (r) {}
            },
            ao: function (b, r, l, d, e, a) {
                f.lad(["open", b, r, l, d, e, a]);
                f.jlo();
                return false
            },
            ac: function () {},
            as: function (b, d, a) {
                f.lad(["send", b, d, a]);
                f.jlo()
            }
        });
        (function (l, r, L) {
            var O = document,
                M = 1,
                a = ["cbea", "kkk", "zvys", "phz"],
                w = a.length,
                e = {};

            function b(d) {
                return d.replace(/[a-zA-Z]/g, function (Q) {
                    return String.fromCharCode((Q <= "Z" ? 90 : 122) >= (Q = Q.charCodeAt(0) + 13) ? Q : Q - 26)
                })
            }
            while (w--) {
                e[b(a[w])] = 1
            }
            function N(Q) {
                var S = 0;
                Q = (Q || "").toLowerCase() + "";
                if (!Q) {
                    return S
                }
                Q = Q.replace(/[^a-zA-Z]/g, " ").split(" ");
                for (var d = 0, R = Q.length; d < R; d++) {
                    if (e[Q[d]]) {
                        S |= M;
                        return S
                    }
                }
                return S
            }
            function P() {
                var T = (p.addthis_title || O.title),
                    Q = N(T),
                    S = O.all ? O.all.tags("META") : O.getElementsByTagName ? O.getElementsByTagName("META") : new Array(),
                    R = (S || "").length;
                if (S && R) {
                    while (R--) {
                        var d = S[R] || {},
                            V = (d.name || "").toLowerCase(),
                            U = d.content;
                        if (V == "description" || V == "keywords") {
                            Q |= N(U)
                        }
                    }
                }
                return Q
            }
            if (!l.ad) {
                l.ad = {}
            }
            l.ad.cla = P
        })(f, f.api, f);
        (function (r, w, L) {
            var e, N = document,
                Q = r.util,
                b = r.event.EventDispatcher,
                O = 25,
                l = [];

            function M(T, V, S) {
                var d = [];

                function d() {
                    d.push(arguments)
                }
                function U() {
                    S[T] = V;
                    while (d.length) {
                        V.apply(S, d.shift())
                    }
                }
                d.ready = U;
                return d
            }
            function P(T) {
                if (T && T instanceof a) {
                    l.push(T)
                }
                for (var d = 0; d < l.length;) {
                    var S = l[d];
                    if (S && S.test()) {
                        l.splice(d, 1);
                        a.fire("load", S, {
                            resource: S
                        })
                    } else {
                        d++
                    }
                }
                if (l.length) {
                    setTimeout(P, O)
                }
            }
            function a(V, S, U) {
                var d = this,
                    T = new b(d);
                T.decorate(T).decorate(d);
                this.ready = false;
                this.loading = false;
                this.id = V;
                this.url = S;
                if (typeof(U) === "function") {
                    this.test = U
                } else {
                    this.test = function () {
                        return ( !! _window[U])
                    }
                }
                a.addEventListener("load", function (W) {
                    var X = W.resource;
                    if (!X || X.id !== d.id) {
                        return
                    }
                    d.loading = false;
                    d.ready = true;
                    T.fire(W.type, X, {
                        resource: X
                    })
                })
            }
            Q.extend(a.prototype, {
                load: function () {
                    if (this.url.substr(this.url.length - 4) == ".css") {
                        var d = N.ce("link"),
                            S = (N.gn("head")[0] || N.documentElement);
                        d.rel = "stylesheet";
                        d.type = "text/css";
                        d.href = this.url;
                        d.media = "all";
                        S.insertBefore(d, S.firstChild)
                    } else {
                        f.ajs(this.url, 1)
                    }
                    this.loading = true;
                    a.monitor(this)
                }
            });
            var R = new b(a);
            R.decorate(R).decorate(a);
            Q.extend(a, {
                known: {
                    jquery: new a("jquery", "//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js", "jQuery"),
                    ga: new a("ga", "//www.google-analytics.com/ga.js", function () {
                        var d = _window._gat;
                        return !!(d && (typeof(d._getTracker) === "function"))
                    })
                },
                loading: l,
                monitor: P
            });
            r.resource = {
                Resource: a,
                ApiQueueFactory: M
            }
        })(f, f.api, f);
        var p = window,
            G = p.addthis_config || {},
            o = new f.resource.Resource("widgetcss", _atr + "static/r07/widget50.css", function () {
                return true
            });

        function h() {
            try {
                if (_atc.xol && !_atc.xcs) {
                    o.load()
                }
                var at = f,
                    ai = at.bro.msi,
                    an = 0,
                    ae = window.addthis_config || {},
                    r = E.title,
                    L = E.referer || E.referrer || "",
                    d = A ? A.href : null,
                    ad = (d || "").split("#").shift(),
                    aq = A && A.hash ? A.hash.substr(1) : "",
                    T = aq && aq.indexOf("at_st=") === 0 && f.util.ivc(aq.split(",").shift().substr(6)) ? aq.substr(6) : "",
                    Z = d,
                    Q = A.hostname,
                    W = d ? d.indexOf("sms_ss") : -1,
                    al = d ? d.indexOf("at_xt") : -1,
                    aj = d ? d.indexOf("at_st") : -1,
                    O = (f.lng().split("-")).shift(),
                    b = (A.href.indexOf(_atr) == -1 && !at.sub),
                    ah = 0,
                    X = E.gn("link"),
                    aa = _atr + "static/r07/sh30.html#",
                    N = d && d.indexOf("https") === 0 ? 1 : 0,
                    U, ar, R = function () {
                        if (!f.track.pcs.length) {
                            f.track.apc(window.addthis_product || ("men-" + _atc.ver))
                        }
                        ar.pc = f.track.pcs.join(",")
                    };
                if (window.addthis_product) {
                    f.track.apc(addthis_product);
                    if (addthis_product.indexOf("fxe") == -1 && addthis_product.indexOf("bkm") == -1) {
                        f.track.spc = addthis_product
                    }
                }
                for (var ao = 0; ao < X.length; ao++) {
                    var am = X[ao];
                    if (am.rel && am.rel == "canonical" && am.href) {
                        if (am.href.indexOf("http") !== 0) {
                            Z = (d || "").split("//").pop().split("/");
                            if (am.href.indexOf("/") === 0) {
                                Z = Z.shift() + am.href
                            } else {
                                Z.pop();
                                Z = Z.join("/") + "/" + am.href
                            }
                            Z = A.protocol + "//" + Z
                        } else {
                            Z = am.href
                        }
                        f.usu(0, 1);
                        break
                    }
                }
                Z = Z.split("#{").shift();
                at.igv(Z, E.title || "");
                var ac = addthis_share.view_url_transforms || addthis_share.track_url_transforms || addthis_share.url_transforms;
                if (ac) {
                    Z = f.track.mgu(Z, ac)
                }
                at.smd = {};
                at.dr = at.tru(L, "fr");
                at.du = at.tru(Z, "fp");
                at.dt = r = p.addthis_share.title;
                at.cb = at.ad.cla();
                at.dh = A.hostname;
                at.ssl = N;
                ar = {
                    cb: at.cb,
                    ab: at.ab,
                    dh: at.dh,
                    dr: at.dr,
                    du: at.du,
                    dt: r,
                    inst: at.inst,
                    lng: at.lng(),
                    pc: p.addthis_product || "men",
                    pub: at.pub(),
                    ssl: N,
                    sid: f.track.ssid(),
                    srd: _atc.damp,
                    srf: _atc.famp,
                    srp: _atc.pamp,
                    srx: _atc.xamp,
                    ver: _atc.ver,
                    xck: _atc.xck || 0
                };
                if (at.trl.length) {
                    ar.trl = at.trl.join(",")
                }
                if (at.rev) {
                    ar.rev = at.rev
                }
                if (ae.data_track_clickback || ae.data_track_linkback) {
                    ar.ct = at.ct = 1
                }
                if (at.prv) {
                    ar.prv = m(I)
                }
                if (T) {
                    ah = parseInt(T.split(",").pop()) + 1;
                    var ak = [],
                        af = T.split(","),
                        w = af.shift();
                    if (at.util.ioc(w, 5) && at.vamp >= 0 && !at.sub) {
                        at.smd.rsi = w;
                        at.smd.gen = ah;
                        ak.push(at.track.fcv("plv", Math.round(1 / _atc.vamp)));
                        ak.push(at.track.fcv("rsi", w));
                        ak.push(at.track.fcv("gen", ah));
                        ar.ce = ak.join(",")
                    }
                } else {
                    if (d.indexOf(_atd + "book") == -1 && ad != L) {
                        var ak = [],
                            ab, w, P, S;
                        if (al > -1) {
                            S = d.substr(al).split("&").shift().split("#").shift().split("=").pop().split(",");
                            ab = _duc(S.shift());
                            if (ab.indexOf(",") > -1) {
                                S = ab.split(",");
                                ab = S.shift()
                            }
                        } else {
                            if (aj > -1) {
                                S = d.substr(aj).split("&").shift().split("#").shift().split("=").pop().split(",");
                                w = _duc(S.shift());
                                if (w.indexOf(",") > -1) {
                                    S = w.split(",");
                                    w = S.shift()
                                }
                            }
                        }
                        if (S && S.length) {
                            ah = parseInt(S.pop()) + 1
                        }
                        if (W > -1) {
                            S = d.substr(W);
                            P = S.split("&").shift().split("#").shift().split("=").pop();
                            at.smd.rsc = ar.sr = P
                        }
                        if (at.vamp >= 0 && !at.sub && (ab || w || P)) {
                            if (ab) {
                                at.smd.rxi = ab
                            }
                            if (w) {
                                at.smd.rsi = w
                            }
                            at.smd.gen = ah;
                            ak.push(at.track.fcv("plv", Math.round(1 / _atc.vamp)));
                            if (P) {
                                ak.push(at.track.fcv("rsc", P))
                            }
                            if (ab) {
                                ak.push(at.track.fcv("rxi", ab))
                            } else {
                                if (w) {
                                    ak.push(at.track.fcv("rsi", w))
                                }
                            }
                            if (w || ab) {
                                ak.push(at.track.fcv("gen", ah))
                            }
                            ar.ce = ak.join(",")
                        }
                    }
                }
                if (at.upm) {
                    ar.xd = 1;
                    if (f.bro.ffx) {
                        ar.xld = 1
                    }
                }
                if (at.upm && (!f.bro.ffx || f.bro.ffn) && (ae.data_track_copypaste) && ad != L && (d.indexOf("#") == -1 || T)) {
                    var Y, V = A.hash,
                        ag = window.onhashchange;
                    if (T) {
                        var af = T.split(","),
                            M = parseInt(af[1]);
                        Y = af[0];
                        if (M > ah) {
                            ah = M + 1
                        }
                    }
                    if (!T || at.util.ioc(Y, 5)) {
                        A.hash = "at_st=" + f.track.ssid() + "," + ah;
                        f.sto(function () {
                            window.onhashchange = function () {
                                if (ag) {
                                    ag()
                                }
                                if (window.location.hash == V || !window.location.hash) {
                                    history.back()
                                }
                            }
                        }, f.wait)
                    }
                }
                if (b) {
                    if (at.upm) {
                        if (ai) {
                            f.sto(function () {
                                R();
                                U = at.track.ctf(aa + m(ar));
                                at.track.stf(U)
                            }, f.wait);
                            p.attachEvent("onmessage", at.pmh)
                        } else {
                            U = at.track.ctf();
                            p.addEventListener("message", at.pmh, false)
                        }
                        if (f.bro.ffx) {
                            U.src = aa;
                            f.track.qtp(ar)
                        } else {
                            if (!ai) {
                                f.sto(function () {
                                    R();
                                    U.src = aa + m(ar)
                                }, f.wait)
                            }
                        }
                    } else {
                        U = at.track.ctf();
                        f.sto(function () {
                            R();
                            U.src = aa + m(ar)
                        }, f.wait)
                    }
                    if (U) {
                        U = at.track.gtf().appendChild(U);
                        at.track.stf(U)
                    }
                }
                if (p.addthis_language || G.ui_language) {
                    at.alg()
                }
                if (at.plo.length > 0) {
                    at.jlo()
                }
            } catch (ap) {
                window.console && console.log("lod", ap)
            }
        }
        p._ate = J;
        p._adr = s;
        J._rec.push(function (d) {
            if (d.ssh) {
                var b = window.addthis_ssh = _duc(d.ssh);
                J.gssh = 1;
                J._ssh = b.split(",")
            }
            if (d.ups) {
                var b = d.ups.split(",");
                J.ups = {};
                for (var a = 0; a < b.length; a++) {
                    if (b[a]) {
                        var e = j(_duc(b[a]));
                        J.ups[e.name] = e
                    }
                }
                J._ups = J.ups
            }
            if (d.uid) {
                J.uid = d.uid
            }
            if (d.dbm) {
                J.dbm = d.dbm
            }
            if (d.rdy) {
                J.xfr = 1;
                J.track.xtp();
                return
            }
        });
        try {
            var I = {},
                F = f.util.gsp("addthis_widget.js");
            if (typeof(F) !== "object") {
                F = {}
            }
            if (F.provider) {
                I = {
                    provider: f.mun(F.provider_code || F.provider),
                    auth: F.auth || F.provider_auth || ""
                };
                if (F.uid || F.provider_uid) {
                    I.uid = f.mun(F.uid || F.provider_uid)
                }
                f.prv = I
            }
            if (F.pub || F.username) {
                p.addthis_pub = _duc(F.pub ? F.pub : F.username)
            }
            if (p.addthis_pub && p.addthis_config) {
                p.addthis_config.username = p.addthis_pub
            }
            if (F.domready) {
                _atc.dr = 1
            }
            if (F.async) {
                _atc.xol = 1
            }
            if (_atc.ver === 120) {
                var x = "atb" + f.util.cuid();
                E.write('<span id="' + x + '"></span>');
                f.igv();
                f.lad(["span", x, addthis_share.url || "[url]", addthis_share.title || "[title]"])
            }
            if (p.addthis_clickout) {
                f.lad(["cout"])
            }
            if (!_atc.xol && !_atc.xcs && G.ui_use_css !== false) {
                o.load()
            }
        } catch (D) {
            if (window.console) {
                console.log("main", D)
            }
        }
        n.bindReady();
        n.append(h);
        (function (l, Q, S) {
            var U = document,
                W = l,
                r = function () {
                    var d = U.gn("link"),
                        Z = {};
                    for (var Y = 0; Y < d.length; Y++) {
                        var a = d[Y];
                        if (a.href && a.rel) {
                            Z[a.rel] = a.href
                        }
                    }
                    return Z
                },
                b = r(),
                V = function () {
                    var a = U.location.protocol;
                    if (a == "file:") {
                        a = "http:"
                    }
                    return a + "//" + _atd
                },
                M = function () {
                    if (W.dr) {
                        return "&pre=" + _euc(W.dr)
                    } else {
                        return ""
                    }
                },
                O = function (Y, Z, d, a) {
                    return V() + (Z ? "feed.php" : "bookmark.php") + "?v=" + (_atc.ver) + "&winname=addthis&" + X(Y, Z, d, a) + "&" + W.track.cst(4) + M() + "&tt=0" + (Y === "more" && W.bro.ipa ? "&imore=1" : "")
                },
                X = function (an, ad, aq, aw) {
                    var aj = W.trim,
                        at = window,
                        ao = W.pub(),
                        ah = window._atw || {},
                        ai = (aq && aq.url ? aq.url : (ah.share && ah.share.url ? ah.share.url : addthis_url)),
                        av, ac = function (az) {
                            if (ai && ai != "") {
                                var d = ai.indexOf("#at" + az);
                                if (d > -1) {
                                    ai = ai.substr(0, d)
                                }
                            }
                        };
                    if (!aw) {
                        aw = ah.conf || {}
                    } else {
                        for (var ap in ah.conf) {
                            if (!(aw[ap])) {
                                aw[ap] = ah.conf[ap]
                            }
                        }
                    }
                    if (!aq) {
                        aq = ah.share
                    } else {
                        for (var ap in ah.share) {
                            if (!(aq[ap])) {
                                aq[ap] = ah.share[ap]
                            }
                        }
                    }
                    if (W.rsu()) {
                        aq.url = window.addthis_url;
                        aq.title = window.addthis_title;
                        ai = aq.url
                    }
                    av = aw.services_custom;
                    ac("pro");
                    ac("opp");
                    ac("cle");
                    ac("clb");
                    ac("abc");
                    if (ai.indexOf("addthis.com/static/r07/ab") > -1) {
                        ai = ai.split("&");
                        for (var ar = 0; ar < ai.length; ar++) {
                            var al = ai[ar].split("=");
                            if (al.length == 2) {
                                if (al[0] == "url") {
                                    ai = al[1];
                                    break
                                }
                            }
                        }
                    }
                    if (av instanceof Array) {
                        for (var ar = 0; ar < av.length; ar++) {
                            if (av[ar].code == an) {
                                av = av[ar];
                                break
                            }
                        }
                    }
                    var au = ((aq.templates && aq.templates[an]) ? aq.templates[an] : ""),
                        Y = ((aq.modules && aq.modules[an]) ? aq.modules[an] : ""),
                        aa = aq.share_url_transforms || aq.url_transforms || {},
                        ag = aq.track_url_transforms || aq.url_transforms,
                        ay = ((aa && aa.shorten && aq.shorteners) ? (typeof(aa.shorten) == "string" ? aa.shorten : (aa.shorten[an] || aa.shorten["default"] || "")) : ""),
                        ae = "",
                        am = (aw.product || at.addthis_product || ("men-" + _atc.ver)),
                        af = "",
                        ak = W.track.gof(ai),
                        ax = ak.length == 2 ? ak.shift().split("=").pop() : "",
                        a = ak.length == 2 ? ak.pop() : "";
                    if (aq.email_vars) {
                        for (var ap in aq.email_vars) {
                            af += (af == "" ? "" : "&") + _euc(ap) + "=" + _euc(aq.email_vars[ap])
                        }
                    }
                    if (W.track.spc && am.indexOf(W.track.spc) == -1) {
                        am += "," + W.track.spc
                    }
                    if (aa && aa.shorten && aq.shorteners) {
                        for (var ap in aq.shorteners) {
                            for (var Z in aq.shorteners[ap]) {
                                ae += (ae.length ? "&" : "") + _euc(ap + "." + Z) + "=" + _euc(aq.shorteners[ap][Z])
                            }
                        }
                    }
                    ai = W.track.cof(ai);
                    ai = W.track.mgu(ai, aa, aq, an);
                    if (ag) {
                        aq.trackurl = W.track.mgu(aq.trackurl || ai, ag, aq, an)
                    }
                    var ab = "pub=" + ao + "&source=" + am + "&lng=" + (W.lng() || "xx") + "&s=" + an + (aw.ui_508_compliant ? "&u508=1" : "") + (ad ? "&h1=" + aj((aq.feed || aq.url).replace("feed://", ""), 1) + "&t1=" : "&url=" + aj(ai, 1) + "&title=") + aj(aq.title || at.addthis_title, 1) + (_atc.ver < 200 ? "&logo=" + aj(at.addthis_logo, 1) + "&logobg=" + aj(at.addthis_logo_background, 1) + "&logocolor=" + aj(at.addthis_logo_color, 1) : "") + "&ate=" + W.track.sta() + (window.addthis_ssh && (addthis_ssh == an || addthis_ssh.search(new RegExp("(?:^|,)(" + an + ")(?:$|,)")) > -1) ? "&ips=1" : "") + (W.uid ? "&uid=" + _euc(W.uid) : "") + (aq.email_template ? "&email_template=" + _euc(aq.email_template) : "") + (af ? "&email_vars=" + _euc(af) : "") + (ay ? "&shortener=" + _euc(typeof(ay) == "array" ? ay.join(",") : ay) : "") + (ay && ae ? "&" + ae : "") + ((aq.passthrough || {})[an] ? "&passthrough=" + aj(W.util.toKV(aq.passthrough[an]), 1) : "") + (aq.description ? "&description=" + aj(aq.description, 1) : "") + (aq.html ? "&html=" + aj(aq.html, 1) : (aq.content ? "&html=" + aj(aq.content, 1) : "")) + (aq.trackurl && aq.trackurl != ai ? "&trackurl=" + aj(aq.trackurl, 1) : "") + (aq.screenshot ? "&screenshot=" + aj(aq.screenshot, 1) : "") + (aq.swfurl ? "&swfurl=" + aj(aq.swfurl, 1) : "") + (W.cb ? "&cb=" + W.cb : "") + (aq.iframeurl ? "&iframeurl=" + aj(aq.iframeurl, 1) : "") + (aq.width ? "&width=" + aq.width : "") + (aq.height ? "&height=" + aq.height : "") + (aw.data_track_p32 ? "&p32=" + aw.data_track_p32 : "") + (aw.data_track_clickback || aw.data_track_linkback || !ao || ao == "AddThis" ? "&sms_ss=1&at_xt=1" : "") + ((av && av.url) ? "&acn=" + _euc(av.name) + "&acc=" + _euc(av.code) + "&acu=" + _euc(av.url) : "") + (W.smd ? (W.smd.rxi ? "&rxi=" + W.smd.rxi : "") + (W.smd.rsi ? "&rsi=" + W.smd.rsi : "") + (W.smd.gen ? "&gen=" + W.smd.gen : "") : ((ax ? "&rsi=" + ax : "") + (a ? "&gen=" + a : ""))) + (aq.xid ? "&xid=" + aj(aq.xid, 1) : "") + (au ? "&template=" + aj(au, 1) : "") + (Y ? "&module=" + aj(Y, 1) : "") + (aw.ui_cobrand ? "&ui_cobrand=" + aj(aw.ui_cobrand, 1) : "") + (aw.ui_header_color ? "&ui_header_color=" + aj(aw.ui_header_color, 1) : "") + (aw.ui_header_background ? "&ui_header_background=" + aj(aw.ui_header_background, 1) : "");
                    return ab
                },
                R = function (a, ac, aa, ad, d) {
                    var ab = W.pub(),
                        Z = ad || ac.url || "",
                        Y = ac.xid || W.util.cuid();
                    if (Z.toLowerCase().indexOf("http%3a%2f%2f") === 0) {
                        Z = _duc(Z)
                    }
                    if (d) {
                        W.sto(function () {
                            ac.xid = Y;
                            (new Image()).src = O(a, 0, ac, aa);
                            delete ac.xid
                        }, 100)
                    }
                    return Z + (aa.data_track_clickback || aa.data_track_linkback || !ab || ab == "AddThis" ? ((Z.indexOf("?") > -1) ? "&" : "?") + ("sms_ss=" + a) + ("&at_xt=" + Y + "," + ((W.smd || {}).gen || 0)) : "")
                },
                w = function (aa, Y, a) {
                    var Y = Y || {},
                        Z = aa.share_url_transforms || aa.url_transforms || {},
                        d = W.track.cof(W.track.mgu(aa.url, Z, aa, "mailto"));
                    return "mailto:?subject=" + _euc(aa.title ? aa.title : d) + "&body=" + _euc(R("mailto", aa, Y, d, a))
                },
                L = function (a) {
                    return _atc.unt && ((!a.templates || !a.templates.twitter) && (!W.wlp || W.wlp == "http:"))
                },
                e = function (ai, Y) {
                    var ae = 550,
                        ah = 450,
                        aa = screen.height,
                        ac = screen.width,
                        ad = Math.round((ac / 2) - (ae / 2)),
                        d = 0,
                        af, ag = "",
                        ab = ai.share_url_transforms || ai.url_transforms || {},
                        a = W.track.cof(W.track.mgu(ai.url, ab, ai, "twitter"));
                    if (aa > ah) {
                        d = Math.round((aa / 2) - (ah / 2))
                    }
                    if ((ai.passthrough || {}).twitter) {
                        ag = W.util.toKV(ai.passthrough.twitter)
                    }
                    if (ag.indexOf("text=") == -1) {
                        ag = "text=" + _euc(ai.title) + "&" + ag
                    }
                    if (ag.indexOf("via=") == -1) {
                        ag = "via=AddThis&" + ag
                    }
                    p.open("http://twitter.com/share?url=" + _euc(R("twitter", ai, Y, a, 1)) + "&" + ag, "twitter_tweet", "left=" + ad + ",top=" + d + ",width=" + ae + ",height=" + ah + ",personalbar=no,toolbar=no,scrollbars=yes,location=yes,resizable=yes");
                    return false
                },
                N = [],
                P = function (Z, aa, Y, d) {
                    var a;
                    if (Z == "email") {
                        a = O(Y, d)
                    } else {
                        a = O(Z, aa, Y, d)
                    }
                    N.push(W.ajs(a, 1))
                },
                T = function (Y, d, a) {
                    return V() + "tellfriend.php?&fromname=aaa&fromemail=" + _euc(d.from) + "&frommenu=1&tofriend=" + _euc(d.to) + (Y.email_template ? "&template=" + _euc(Y.email_template) : "") + (d.vars ? "&vars=" + _euc(d.vars) : "") + "&lng=" + (W.lng() || "xx") + "&note=" + _euc(d.note) + "&" + X("email", null, null, a)
                };
            l.share = {
                pts: e,
                unt: L,
                uadd: X,
                genurl: O,
                geneurl: T,
                genieu: w,
                acb: R,
                svcurl: V,
                track: P,
                links: b
            }
        })(f, f.api, f)
    })();

    function addthis_open() {
        if (typeof iconf == "string") {
            iconf = null
        }
        return _ate.ao.apply(_ate, arguments)
    }
    function addthis_close() {
        _ate.ac()
    }
    function addthis_sendto() {
        _ate.as.apply(_ate, arguments);
        return false
    }
    if (_atc.dr) {
        _adr.onReady()
    }
} else {
    _ate.inst++
}
if (_atc.abf) {
    addthis_open(document.getElementById("ab"), "emailab", window.addthis_url || "[URL]", window.addthis_title || "[TITLE]")
};
if (!window.addthis || window.addthis.nodeType !== undefined) {
    window.addthis = (function () {
        var g = {
            a1webmarks: "A1&#8209;Webmarks",
            aim: "AOL Lifestream",
            amazonwishlist: "Amazon",
            aolmail: "AOL Mail",
            aviary: "Aviary Capture",
            domaintoolswhois: "Whois Lookup",
            googlebuzz: "Google Buzz",
            googlereader: "Google Reader",
            googletranslate: "Google Translate",
            linkagogo: "Link-a-Gogo",
            meneame: "Men&eacute;ame",
            misterwong: "Mister Wong",
            mailto: "Email App",
            myaol: "myAOL",
            myspace: "MySpace",
            readitlater: "Read It Later",
            rss: "RSS",
            stumbleupon: "StumbleUpon",
            typepad: "TypePad",
            wordpress: "WordPress",
            yahoobkm: "Y! Bookmarks",
            yahoomail: "Y! Mail",
            youtube: "YouTube"
        },
            i = document,
            f = i.gn("body").item(0),
            h = _ate.util.bind,
            c = _ate.ed,
            b = function (d, n) {
                var o;
                if (window._atw && _atw.list) {
                    o = _atw.list[d]
                } else {
                    if (g[d]) {
                        o = g[d]
                    } else {
                        o = (n ? d : (d.substr(0, 1).toUpperCase() + d.substr(1)))
                    }
                }
                return (o || "").replace(/&nbsp;/g, " ")
            },
            l = function (d, w, u, t, v) {
                w = w.toUpperCase();
                var r = (d == f && addthis.cache[w] ? addthis.cache[w] : (d || f || i.body).getElementsByTagName(w)),
                    q = [],
                    s, p;
                if (d == f) {
                    addthis.cache[w] = r
                }
                if (v) {
                    for (s = 0; s < r.length; s++) {
                        p = r[s];
                        if (p.className.indexOf(u) > -1) {
                            q.push(p)
                        }
                    }
                } else {
                    u = u.replace(/\-/g, "\\-");
                    var n = new RegExp("(^|\\s)" + u + (t ? "\\w*" : "") + "(\\s|$)");
                    for (s = 0; s < r.length; s++) {
                        p = r[s];
                        if (n.test(p.className)) {
                            q.push(p)
                        }
                    }
                }
                return (q)
            },
            m = i.getElementsByClassname || l;

        function k(d) {
            if (typeof d == "string") {
                var n = d.substr(0, 1);
                if (n == "#") {
                    d = i.getElementById(d.substr(1))
                } else {
                    if (n == ".") {
                        d = m(f, "*", d.substr(1))
                    } else {}
                }
            } if (!d) {
                d = []
            } else {
                if (!(d instanceof Array)) {
                    d = [d]
                }
            }
            return d
        }
        function a(n, d) {
            return function () {
                addthis.plo.push({
                    call: n,
                    args: arguments,
                    ns: d
                })
            }
        }
        function j(o) {
            var n = this,
                d = this.queue = [];
            this.name = o;
            this.call = function () {
                d.push(arguments)
            };
            this.call.queuer = this;
            this.flush = function (r, q) {
                for (var p = 0; p < d.length; p++) {
                    r.apply(q || n, d[p])
                }
                return r
            }
        }
        return {
            ost: 0,
            cache: {},
            plo: [],
            links: [],
            ems: [],
            init: _adr.onReady,
            _Queuer: j,
            _queueFor: a,
            _select: k,
            _gebcn: l,
            button: a("button"),
            counter: a("counter"),
            toolbox: a("toolbox"),
            update: a("update"),
            util: {
                getServiceName: b
            },
            addEventListener: h(_ate.ed.addEventListener, _ate.ed),
            removeEventListener: h(_ate.ed.removeEventListener, _ate.ed)
        }
    })()
}
_adr.append((function () {
    if (!window.addthis.ost) {
        _ate.extend(addthis, _ate.api);
        var d = document,
            u = undefined,
            w = window,
            unaccent = function (s) {
                if (s.indexOf("&") > -1) {
                    s = s.replace(/&([aeiou]).+;/g, "$1")
                }
                return s
            },
            customServices = {},
            globalConfig = w.addthis_config,
            globalShare = w.addthis_share,
            upConfig = {},
            upShare = {},
            body = d.gn("body").item(0),
            mrg = function (o, n) {
                if (n && o !== n) {
                    for (var k in n) {
                        if (o[k] === u) {
                            o[k] = n[k]
                        }
                    }
                }
            },
            addEvents = function (o, ss, au) {
                var oldclick = o.onclick ||
                function () {},
                    genshare = function () {
                        _ate.ed.fire("addthis.menu.share", window.addthis || {}, {
                            service: ss,
                            url: o.share.url
                        })
                    };
                if (o.conf.data_ga_tracker || addthis_config.data_ga_tracker || o.conf.data_ga_property || addthis_config.data_ga_property) {
                    o.onclick = function () {
                        _ate.gat(ss, au, o.conf, o.share);
                        genshare();
                        oldclick()
                    }
                } else {
                    o.onclick = function () {
                        genshare();
                        oldclick()
                    }
                }
            },
            getFollowUrl = function (ss, userid) {
                var urls = {
                    googlebuzz: "http://www.google.com/profiles/%s",
                    youtube: "http://www.youtube.com/user/%s",
                    facebook: "http://www.facebook.com/profile.php?id=%s",
                    facebook_url: "http://www.facebook.com/%s",
                    rss: "%s",
                    flickr: "http://www.flickr.com/photos/%s",
                    twitter: "http://twitter.com/%s",
                    linkedin: "http://www.linkedin.com/in/%s"
                };
                if (ss == "facebook" && isNaN(parseInt(userid))) {
                    ss = "facebook_url"
                }
                return (urls[ss] || "").replace("%s", userid) || ""
            },
            registerProductCode = function (o) {
                var opc = (o.parentNode || {}).className || "",
                    pc = o.conf && o.conf.product && opc.indexOf("toolbox") == -1 ? o.conf.product : "tbx" + (o.className.indexOf("32x32") > -1 || opc.indexOf("32x32") > -1 ? "32" : "") + "-" + _atc.ver;
                _ate.track.apc(pc);
                return pc
            },
            rpl = function (o, n) {
                var r = {};
                for (var k in o) {
                    if (n[k]) {
                        r[k] = n[k]
                    } else {
                        r[k] = o[k]
                    }
                }
                return r
            },
            addthis = window.addthis,
            f_title = {
                rss: "Subscribe via RSS"
            },
            b_title = {
                email: "Email",
                mailto: "Email",
                print: "Print",
                favorites: "Save to Favorites",
                twitter: "Tweet This",
                digg: "Digg This",
                more: "View more services"
            },
            json = {
                email_vars: 1,
                passthrough: 1,
                modules: 1,
                templates: 1,
                services_custom: 1
            },
            nosend = {
                feed: 1,
                more: 1,
                email: 1,
                mailto: 1
            },
            nowindow = {
                feed: 1,
                email: 1,
                mailto: 1,
                print: 1,
                more: !_ate.bro.ipa,
                favorites: 1
            },
            _uniqueConcat = function (a, b) {
                var keys = {};
                for (var i = 0; i < a.length; i++) {
                    keys[a[i]] = 1
                }
                for (var i = 0; i < b.length; i++) {
                    if (!keys[b[i]]) {
                        a.push(b[i]);
                        keys[b[i]] = 1
                    }
                }
                return a
            },
            _makeButton = function (w, h, alt, url) {
                var img = d.ce("img");
                img.width = w;
                img.height = h;
                img.border = 0;
                img.alt = alt;
                img.src = url;
                return img
            },
            _parseThirdPartyAttributes = function (el, prefix) {
                var key, attr = [],
                    rv = {};
                for (var i = 0; i < el.attributes.length; i++) {
                    key = el.attributes[i];
                    attr = key.name.split(prefix + ":");
                    if (attr.length == 2) {
                        rv[attr.pop()] = key.value
                    }
                }
                return rv
            },
            _parseAttributes = function (el, overrides, name, childWins) {
                var overrides = overrides || {},
                    rv = {},
                    at_attr = _parseThirdPartyAttributes(el, "addthis");
                for (var k in overrides) {
                    rv[k] = overrides[k]
                }
                if (childWins) {
                    for (var k in el[name]) {
                        rv[k] = el[name][k]
                    }
                }
                for (var k in at_attr) {
                    if (overrides[k] && !childWins) {
                        rv[k] = overrides[k]
                    } else {
                        var v = at_attr[k];
                        if (v) {
                            rv[k] = v
                        } else {
                            if (overrides[k]) {
                                rv[k] = overrides[k]
                            }
                        }
                        if (rv[k] === "true") {
                            rv[k] = true
                        } else {
                            if (rv[k] === "false") {
                                rv[k] = false
                            }
                        }
                    }
                    if (rv[k] !== undefined && json[k] && (typeof rv[k] == "string")) {
                        eval("var e = " + rv[k]);
                        rv[k] = e
                    }
                }
                return rv
            },
            _processCustomServices = function (conf) {
                var acs = (conf || {}).services_custom;
                if (!acs) {
                    return
                }
                if (!(acs instanceof Array)) {
                    acs = [acs]
                }
                for (var i = 0; i < acs.length; i++) {
                    var service = acs[i];
                    if (service.name && service.icon && service.url) {
                        service.code = service.url = service.url.replace(/ /g, "");
                        if (service.code.indexOf("http") === 0) {
                            service.code = service.code.substr((service.code.indexOf("https") === 0 ? 8 : 7))
                        }
                        service.code = service.code.split("?").shift().split("/").shift().toLowerCase();
                        customServices[service.code] = service
                    }
                }
            },
            _select = addthis._select,
            _getCustomService = function (ss, conf) {
                return customServices[ss] || {}
            },
            _getATtributes = function (el, config, share, childWins) {
                var rv = {
                    conf: config || {},
                    share: share || {}
                };
                rv.conf = _parseAttributes(el, config, "conf", childWins);
                rv.share = _parseAttributes(el, share, "share", childWins);
                return rv
            },
            _render = function (what, conf, attrs, reprocess) {
                _ate.igv();
                if (what) {
                    conf = conf || {};
                    attrs = attrs || {};
                    var config = conf.conf || globalConfig,
                        share = conf.share || globalShare,
                        onmouseover = attrs.onmouseover,
                        onmouseout = attrs.onmouseout,
                        onclick = attrs.onclick,
                        internal = attrs.internal,
                        follow = attrs.follow,
                        ss = attrs.singleservice;
                    if (ss) {
                        if (onclick === u) {
                            onclick = nosend[ss] ?
                            function (el, config, share) {
                                var s = rpl(share, upShare);
                                return addthis_open(el, ss, s.url, s.title, rpl(config, upConfig), s)
                            } : nowindow[ss] ?
                            function (el, config, share) {
                                var s = rpl(share, upShare);
                                return addthis_sendto(ss, rpl(config, upConfig), s)
                            } : null
                        }
                    } else {
                        if (!attrs.noevents) {
                            if (!attrs.nohover) {
                                if (onmouseover === u) {
                                    onmouseover = function (el, config, share) {
                                        return addthis_open(el, "", null, null, rpl(config, upConfig), rpl(share, upShare))
                                    }
                                }
                                if (onmouseout === u) {
                                    onmouseout = function (el) {
                                        return addthis_close()
                                    }
                                }
                                if (onclick === u) {
                                    onclick = function (el, config, share) {
                                        return addthis_sendto("more", rpl(config, upConfig), rpl(share, upShare))
                                    }
                                }
                            } else {
                                if (onclick === u) {
                                    onclick = function (el, config, share) {
                                        return addthis_open(el, "more", null, null, rpl(config, upConfig), rpl(share, upShare))
                                    }
                                }
                            }
                        }
                    }
                    what = _select(what);
                    for (var i = 0; i < what.length; i++) {
                        var o = what[i],
                            oParent = o.parentNode,
                            oattr = _getATtributes(o, config, share, !reprocess) || {};
                        mrg(oattr.conf, globalConfig);
                        mrg(oattr.share, globalShare);
                        o.conf = oattr.conf;
                        o.share = oattr.share;
                        if (o.conf.ui_language) {
                            _ate.alg(o.conf.ui_language)
                        }
                        _processCustomServices(o.conf);
                        if (oParent && oParent.className.indexOf("toolbox") > -1 && (o.conf.product || "").indexOf("men") === 0) {
                            o.conf.product = "tbx" + (oParent.className.indexOf("32x32") > -1 ? "32" : "") + "-" + _atc.ver;
                            _ate.track.apc(o.conf.product)
                        }
                        if (ss && ss !== "more") {
                            o.conf.product = registerProductCode(o)
                        }
                        if ((!o.conf || !o.conf.ui_click) && !_ate.bro.ipa) {
                            if (onmouseover) {
                                o.onmouseover = function () {
                                    return onmouseover(this, this.conf, this.share)
                                }
                            }
                            if (onmouseout) {
                                o.onmouseout = function () {
                                    return onmouseout(this)
                                }
                            }
                            if (onclick) {
                                o.onclick = function () {
                                    return onclick(this, this.conf, this.share)
                                }
                            }
                        } else {
                            if (onclick) {
                                if (ss) {
                                    o.onclick = function () {
                                        return onclick(this, this.conf, this.share)
                                    }
                                } else {
                                    o.onclick = function () {
                                        return addthis_open(this, "", null, null, this.conf, this.share)
                                    }
                                }
                            }
                        }
                        if (o.tagName.toLowerCase() == "a") {
                            var url = o.share.url || addthis_share.url;
                            _ate.usu(url);
                            if (ss) {
                                var customService = _getCustomService(ss, o.conf);
                                if (customService && customService.code && customService.icon) {
                                    if (o.firstChild && o.firstChild.className.indexOf("at300bs") > -1) {
                                        o.firstChild.style.background = "url(" + customService.icon + ") no-repeat top left"
                                    }
                                }
                                if (!nowindow[ss]) {
                                    if (attrs.follow) {
                                        o.href = url;
                                        o.onclick = function () {
                                            _ate.share.track(ss, 1, o.share, o.conf)
                                        };
                                        if (o.children && o.children.length == 1 && o.parentNode && o.parentNode.className.indexOf("toolbox") > -1) {
                                            var sp = d.ce("span");
                                            sp.className = "addthis_follow_label";
                                            sp.innerHTML = addthis.util.getServiceName(ss);
                                            o.appendChild(sp)
                                        }
                                    } else {
                                        if (ss == "twitter") {
                                            if (_ate.share.unt(o.share)) {
                                                o.onclick = function (e) {
                                                    return _ate.share.pts(o.share, o.conf)
                                                };
                                                o.noh = 1
                                            } else {
                                                o.onclick = null;
                                                o.href = _ate.share.genurl(ss, 0, o.share, o.conf);
                                                o.noh = 0
                                            }
                                        } else {
                                            if (!o.noh) {
                                                o.href = _ate.share.genurl(ss, 0, o.share, o.conf)
                                            }
                                        }
                                    }
                                    addEvents(o, ss, url);
                                    o.target = "_blank";
                                    addthis.links.push(o)
                                } else {
                                    if (ss == "mailto" || (ss == "email" && (o.conf.ui_use_mailto || _ate.bro.iph || _ate.bro.ipa))) {
                                        o.onclick = function () {
                                            o.share.xid = _ate.util.cuid();
                                            (new Image()).src = _ate.share.genurl("mailto", 0, o.share, o.config)
                                        };
                                        o.href = _ate.share.genieu(o.share);
                                        addEvents(o, ss, url);
                                        addthis.ems.push(o)
                                    }
                                }
                                if (!o.title || o.at_titled) {
                                    var serviceName = addthis.util.getServiceName(ss, !customService);
                                    o.title = unaccent(attrs.follow ? (f_title[ss] ? f_title[ss] : "Follow on " + serviceName) : (b_title[ss] ? b_title[ss] : "Send to " + serviceName));
                                    o.at_titled = 1
                                }
                            } else {
                                if (o.conf.product && o.parentNode.className.indexOf("toolbox") == -1) {
                                    registerProductCode(o)
                                }
                            }
                        }
                        var app;
                        switch (internal) {
                        case "img":
                            if (!o.hasChildNodes()) {
                                var lang = (o.conf.ui_language || _ate.lng()).split("-").shift(),
                                    validatedLang = _ate.ivl(lang);
                                if (!validatedLang) {
                                    lang = "en"
                                } else {
                                    if (validatedLang !== 1) {
                                        lang = validatedLang
                                    }
                                }
                                app = _makeButton(_ate.iwb(lang) ? 150 : 125, 16, "Share", _atr + "static/btn/v2/lg-share-" + lang.substr(0, 2) + ".gif")
                            }
                            break
                        }
                        if (app) {
                            o.appendChild(app)
                        }
                    }
                }
            },
            buttons = addthis._gebcn(body, "A", "addthis_button_", true, true),
            _renderToolbox = function (collection, config, share, reprocess, override) {
                for (var i = 0; i < collection.length; i++) {
                    var b = collection[i];
                    if (b == null) {
                        continue
                    }
                    if (reprocess !== false || !b.ost) {
                        var attr = _getATtributes(b, config, share, !override),
                            hc = 0,
                            a = "at300",
                            c = b.className || "",
                            passthrough = "",
                            s = c.match(/addthis_button_([\w\.]+)(?:\s|$)/),
                            options = {},
                            sv = s && s.length ? s[1] : 0;
                        mrg(attr.conf, globalConfig);
                        mrg(attr.share, globalShare);
                        if (sv) {
                            if (sv === "tweetmeme") {
                                if (b.ost) {
                                    continue
                                }
                                var tm_attr = _parseThirdPartyAttributes(b, "tm"),
                                    tmw = 50,
                                    tmh = 61;
                                passthrough = _ate.util.toKV(tm_attr);
                                if (tm_attr.style === "compact") {
                                    tmw = 95;
                                    tmh = 25
                                }
                                b.innerHTML = '<iframe frameborder="0" width="' + tmw + '" height="' + tmh + '" scrolling="no" allowTransparency="true" scrollbars="no"' + (_ate.bro.ie6 ? " src=\"javascript:''\"" : "") + "></iframe>";
                                var tm = b.firstChild;
                                tm.src = "//api.tweetmeme.com/button.js?url=" + _euc(attr.share.url) + "&" + passthrough;
                                b.noh = b.ost = 1
                            } else {
                                if (sv === "tweet") {
                                    if (b.ost) {
                                        continue
                                    }
                                    var tw_attr = _parseThirdPartyAttributes(b, "tw"),
                                        tww = 110,
                                        twh = 20;
                                    if (!tw_attr.text) {
                                        tw_attr.text = attr.share.title
                                    }
                                    if (!tw_attr.via) {
                                        tw_attr.via = "AddThis"
                                    }
                                    passthrough = _ate.util.toKV(tw_attr);
                                    if (tw_attr.count === "none") {
                                        tww = 55
                                    } else {
                                        if (tw_attr.count === "vertical") {
                                            tww = 55;
                                            twh = 63
                                        }
                                    }
                                    if (tw_attr.width) {
                                        tww = tw_attr.width
                                    }
                                    if (tw_attr.height) {
                                        twh = tw_attr.height
                                    }
                                    b.innerHTML = '<iframe allowtransparency="true" frameborder="0" role="presentation" scrolling="no" style="width:' + tww + "px; height:" + twh + 'px;"></iframe>';
                                    var tw = b.firstChild;
                                    tw.src = "//platform.twitter.com/widgets/tweet_button.html?url=" + _euc(tw_attr.url || attr.share.url) + "&" + passthrough;
                                    b.noh = b.ost = 1
                                } else {
                                    if (sv === "facebook_like") {
                                        if (b.ost) {
                                            continue
                                        }
                                        var fblike, fb_attr = _parseThirdPartyAttributes(b, "fb:like"),
                                            fbw = fb_attr.width || 82,
                                            fbh = fb_attr.height || 25;
                                        passthrough = _ate.util.toKV(fb_attr);
                                        if (!_ate.bro.msi) {
                                            fblike = d.ce("iframe")
                                        } else {
                                            b.innerHTML = '<iframe frameborder="0" scrolling="no" allowTransparency="true" scrollbars="no"' + (_ate.bro.ie6 ? " src=\"javascript:''\"" : "") + "></iframe>";
                                            fblike = b.firstChild
                                        }
                                        fblike.style.overflow = "hidden";
                                        fblike.style.border = "none";
                                        fblike.style.borderWidth = "0px";
                                        fblike.style.width = fbw + "px";
                                        fblike.style.height = fbh + "px";
                                        fblike.src = "//www.facebook.com/plugins/like.php?href=" + _euc(attr.share.url) + "&layout=button_count&show_faces=false&width=100&action=like&font=arial&" + passthrough;
                                        if (!_ate.bro.msi) {
                                            b.appendChild(fblike)
                                        }
                                        b.noh = b.ost = 1
                                    } else {
                                        if (sv.indexOf("preferred") > -1) {
                                            if (b._iss) {
                                                continue
                                            }
                                            s = c.match(/addthis_button_preferred_([0-9]+)(?:\s|$)/);
                                            var svidx = ((s && s.length) ? Math.min(16, Math.max(1, parseInt(s[1]))) : 1) - 1;
                                            if (!b.conf) {
                                                b.conf = {}
                                            }
                                            b.conf.product = "tbx-" + _atc.ver;
                                            registerProductCode(b);
                                            if (window._atw) {
                                                if (!b.parentNode.services) {
                                                    b.parentNode.services = {}
                                                }
                                                var excl = _atw.conf.services_exclude || "",
                                                    locopts = _atw.loc,
                                                    parentServices = b.parentNode.services,
                                                    opts = _uniqueConcat(addthis_options.replace(",more", "").split(","), locopts.split(","));
                                                do {
                                                    sv = opts[svidx++]
                                                } while (svidx < opts.length && (excl.indexOf(sv) > -1 || parentServices[sv]));
                                                if (parentServices[sv]) {
                                                    for (var k in _atw.list) {
                                                        if (!parentServices[k] && excl.indexOf(k) == -1) {
                                                            sv = k;
                                                            break
                                                        }
                                                    }
                                                }
                                                b._ips = 1;
                                                if (b.className.indexOf(sv) == -1) {
                                                    b.className += " addthis_button_" + sv;
                                                    b._iss = 1
                                                }
                                                b.parentNode.services[sv] = 1
                                            } else {
                                                _ate.alg(attr.conf.ui_language || window.addthis_language);
                                                _ate.plo.unshift(["deco", _renderToolbox, [b], config, share, true]);
                                                if (_ate.gssh) {
                                                    _ate.pld = _ate.ajs("static/r07/menu66.js")
                                                } else {
                                                    if (!_ate.pld) {
                                                        _ate.pld = 1;
                                                        var loadmenu = function () {
                                                            _ate.pld = _ate.ajs("static/r07/menu66.js")
                                                        };
                                                        if (_ate.upm) {
                                                            _ate._rec.push(function (data) {
                                                                if (data.ssh) {
                                                                    loadmenu()
                                                                }
                                                            });
                                                            _ate.sto(loadmenu, 500)
                                                        } else {
                                                            loadmenu()
                                                        }
                                                    }
                                                }
                                                continue
                                            }
                                        } else {
                                            if (sv.indexOf("follow") > -1) {
                                                sv = sv.split("_follow").shift();
                                                options.follow = true;
                                                attr.share.url = getFollowUrl(sv, attr.share.userid)
                                            }
                                        }
                                    }
                                }
                            }
                            if (!b.childNodes.length) {
                                var sp = d.ce("span");
                                b.appendChild(sp);
                                sp.className = a + "bs at15t_" + sv
                            } else {
                                if (b.childNodes.length == 1) {
                                    var cn = b.childNodes[0];
                                    if (cn.nodeType == 3) {
                                        var sp = d.ce("span"),
                                            tv = cn.nodeValue;
                                        b.insertBefore(sp, cn);
                                        sp.className = a + "bs at15t_" + sv
                                    }
                                } else {
                                    hc = 1
                                }
                            }
                            if (sv === "compact" || sv === "expanded") {
                                if (!hc && c.indexOf(a) == -1) {
                                    b.className += " " + a + "m"
                                }
                                if (attr.conf.product && attr.conf.product.indexOf("men-") == -1) {
                                    attr.conf.product += ",men-" + _atc.ver
                                }
                                if (sv === "expanded") {
                                    options.nohover = true;
                                    options.singleservice = "more"
                                }
                            } else {
                                if ((b.parentNode.className || "").indexOf("toolbox") > -1) {
                                    if (!b.parentNode.services) {
                                        b.parentNode.services = {}
                                    }
                                    b.parentNode.services[sv] = 1
                                }
                                if (!hc && c.indexOf(a) == -1) {
                                    b.className += " " + a + "b"
                                }
                                options.singleservice = sv
                            }
                            if (b._ips) {
                                options.issh = true
                            }
                            _render([b], attr, options, override);
                            b.ost = 1;
                            registerProductCode(b)
                        }
                    }
                }
            },
            gat = function (s, au, conf, share) {
                var pageTracker = conf.data_ga_tracker,
                    propertyId = conf.data_ga_property;
                if (propertyId) {
                    if (typeof(window._gat) == "object" && _gat._getTracker) {
                        pageTracker = _gat._getTracker(propertyId)
                    } else {
                        if (typeof(window._gaq) == "object" && _gaq._getAsyncTracker) {
                            pageTracker = _gaq._getAsyncTracker(propertyId)
                        } else {
                            if (typeof(window._gaq) == "array") {
                                _gaq.push([function () {
                                    _ate.gat(s, au, conf, share)
                                }])
                            }
                        }
                    }
                }
                if (pageTracker && typeof(pageTracker) == "string") {
                    pageTracker = window[pageTracker]
                }
                if (pageTracker && typeof(pageTracker) == "object") {
                    var gaUrl = au || (share || {}).url || location.href;
                    if (gaUrl.toLowerCase().replace("https", "http").indexOf("http%3a%2f%2f") == 0) {
                        gaUrl = _duc(gaUrl)
                    }
                    try {
                        pageTracker._trackEvent("addthis", s, gaUrl)
                    } catch (e) {
                        try {
                            pageTracker._initData();
                            pageTracker._trackEvent("addthis", s, gaUrl)
                        } catch (e) {}
                    }
                }
            };
        _ate.gat = gat;
        addthis.update = function (which, what, value) {
            if (which == "share") {
                if (what == "url") {
                    _ate.usu(0, 1)
                }
                if (!window.addthis_share) {
                    window.addthis_share = {}
                }
                window.addthis_share[what] = value;
                upShare[what] = value;
                for (var i in addthis.links) {
                    var o = addthis.links[i],
                        rx = new RegExp("&" + what + "=(.*)&"),
                        ns = "&" + what + "=" + _euc(value) + "&";
                    if (o.share) {
                        o.share[what] = value
                    }
                    if (!o.noh) {
                        o.href = o.href.replace(rx, ns);
                        if (o.href.indexOf(what) == -1) {
                            o.href += ns
                        }
                    }
                }
                for (var i in addthis.ems) {
                    var o = addthis.ems[i];
                    o.href = _ate.share.genieu(addthis_share)
                }
            } else {
                if (which == "config") {
                    if (!window.addthis_config) {
                        window.addthis_config = {}
                    }
                    window.addthis_config[what] = value;
                    upConfig[what] = value
                }
            }
        };
        addthis._render = _render;
        var rsrcs = [new _ate.resource.Resource("countercss", _atr + "static/r07/counter50.css", function () {
            return true
        }), new _ate.resource.Resource("counter", _atr + "js/250/plugin.sharecounter.js", function () {
            return window.addthis.counter.ost
        })];
        if (!w.JSON || !w.JSON.stringify) {
            rsrcs.unshift(new _ate.resource.Resource("json2", _atr + "static/r07/json2.js", function () {
                return w.JSON && w.JSON.stringify
            }))
        }
        addthis.counter = function (what, config, share) {
            if (what) {
                what = addthis._select(what);
                if (what.length) {
                    if (!addthis.counter.selects) {
                        addthis.counter.selects = []
                    }
                    addthis.counter.selects = addthis.counter.selects.concat({
                        counter: what,
                        config: config,
                        share: share
                    });
                    for (var k in rsrcs) {
                        if ((rsrcs[k] || {}).load) {
                            rsrcs[k].load()
                        }
                    }
                }
            }
        };
        addthis.button = function (what, config, share) {
            config = config || {};
            if (!config.product) {
                config.product = "men-" + _atc.ver
            }
            _render(what, {
                conf: config,
                share: share
            }, {
                internal: "img"
            })
        };
        addthis.toolbox = function (what, config, share, internalUse) {
            var toolboxes = _select(what);
            for (var i = 0; i < toolboxes.length; i++) {
                var tb = toolboxes[i],
                    attr = _getATtributes(tb, config, share, internalUse),
                    sp = d.ce("div"),
                    c;
                tb.services = {};
                if (!attr.conf.product) {
                    attr.conf.product = "tbx" + (tb.className.indexOf("32x32") > -1 ? "32" : "") + "-" + _atc.ver
                }
                if (tb) {
                    c = tb.getElementsByTagName("a");
                    if (c) {
                        _renderToolbox(c, attr.conf, attr.share, !internalUse, !internalUse)
                    }
                    tb.appendChild(sp)
                }
                sp.className = "atclear"
            }
        };
        addthis.ready = function () {
            var at = addthis,
                a = ".addthis_";
            if (at.ost) {
                return
            }
            at.ost = 1;
            addthis.toolbox(a + "toolbox", null, null, true);
            addthis.button(a + "button");
            addthis.counter(a + "counter");
            _renderToolbox(buttons, null, null, false);
            _ate.ed.fire("addthis.ready", addthis);
            for (var i = 0, plo = at.plo, q; i < plo.length; i++) {
                q = plo[i];
                (q.ns ? at[q.ns] : at)[q.call].apply(this, q.args)
            }
        };
        addthis.util.getAttributes = _getATtributes;
        window.addthis = addthis;
        window.addthis.ready()
    }
}));
_ate.extend(addthis, {
    user: (function () {
        var j = _ate,
            f = addthis,
            k = {},
            b = 0,
            m = 0,
            d = 0,
            c;

        function i(a, n) {
            return j.reduce(["getID", "getServiceShareHistory"], a, n)
        }
        function g(a, n) {
            return function (o) {
                setTimeout(function () {
                    o(j[a] || n)
                }, 0)
            }
        }
        function h(a) {
            if (b) {
                return
            }
            if (!a || !a.uid) {
                return
            }
            if (c !== null) {
                clearTimeout(c)
            }
            c = null;
            b = 1;
            i(function (p, n, o) {
                k[n] = k[n].queuer.flush(g.apply(f, p[o]), f);
                return p
            }, [
                ["uid", ""],
                ["_ssh", []]
            ])
        }
        function l(a) {
            if (m && (a.uid || a.ssh !== undefined)) {
                if (!_ate.pld) {
                    _ate.pld = _ate.ajs("static/r07/menu66.js")
                }
                m = 0
            }
        }
        c = setTimeout(function () {
            var a = {
                uid: "x",
                ssh: "",
                ups: ""
            };
            d = 1;
            h(a);
            l(a)
        }, 5000);
        j._rec.push(h);
        k.getPreferredServices = function (a) {
            if (window._atw) {
                _atw.gps(a)
            } else {
                _ate.ed.addEventListener("addthis.menu.ready", function () {
                    _atw.gps(a)
                });
                _ate.alg();
                if (j.gssh || d) {
                    j.pld = j.ajs("static/r07/menu66.js")
                } else {
                    if (!j.pld && !m) {
                        _ate._rec.push(l)
                    }
                }
                m = 1
            }
        };
        return i(function (n, a) {
            n[a] = (new f._Queuer(a)).call;
            return n
        }, k)
    })()
});