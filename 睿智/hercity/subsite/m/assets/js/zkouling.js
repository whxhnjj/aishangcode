!
function(m) {
	"object" == typeof exports && "undefined" != typeof module ? module.exports = m() : "function" == typeof define && define.amd ? define([], m) : ("undefined" != typeof window ? window : "undefined" != typeof global ? global : "undefined" != typeof self ? self : this).Clipboard = m()
}(function() {
	return function f(h, k, a) {
		function b(d, g) {
			if (!k[d]) {
				if (!h[d]) {
					var c = "function" == typeof require && require;
					if (!g && c) return c(d, !0);
					if (e) return e(d, !0);
					g = Error("Cannot find module '" + d + "'");
					throw g.code = "MODULE_NOT_FOUND", g;
				}
				g = k[d] = {
					exports: {}
				};
				h[d][0].call(g.exports, function(a) {
					return b(h[d][1][a] || a)
				}, g, g.exports, f, h, k, a)
			}
			return k[d].exports
		}
		for (var e = "function" == typeof require && require, l = 0; l < a.length; l++) b(a[l]);
		return b
	}({
		1: [function(f, h, k) {
			"undefined" == typeof Element || Element.prototype.matches || (f = Element.prototype, f.matches = f.matchesSelector || f.mozMatchesSelector || f.msMatchesSelector || f.oMatchesSelector || f.webkitMatchesSelector);
			h.exports = function(a, b) {
				for (; a && 9 !== a.nodeType;) {
					if ("function" == typeof a.matches && a.matches(b)) return a;
					a = a.parentNode
				}
			}
		}, {}],
		2: [function(f, h, k) {
			function a(a, l, d, g) {
				return function(c) {
					c.delegateTarget = b(c.target, l);
					c.delegateTarget && g.call(a, c)
				}
			}
			var b = f("./closest");
			h.exports = function(b, l, d, g, c) {
				var e = a.apply(this, arguments);
				return b.addEventListener(d, e, c), {
					destroy: function() {
						b.removeEventListener(d, e, c)
					}
				}
			}
		}, {
			"./closest": 1
		}],
		3: [function(f, h, k) {
			k.node = function(a) {
				return void 0 !== a && a instanceof HTMLElement && 1 === a.nodeType
			};
			k.nodeList = function(a) {
				var b = Object.prototype.toString.call(a);
				return void 0 !== a && ("[object NodeList]" === b || "[object HTMLCollection]" === b) && "length" in a && (0 === a.length || k.node(a[0]))
			};
			k.string = function(a) {
				return "string" == typeof a || a instanceof String
			};
			k.fn = function(a) {
				return "[object Function]" === Object.prototype.toString.call(a)
			}
		}, {}],
		4: [function(f, h, k) {
			function a(a, b, c) {
				return a.addEventListener(b, c), {
					destroy: function() {
						a.removeEventListener(b, c)
					}
				}
			}
			function b(a, b, c) {
				return Array.prototype.forEach.call(a, function(a) {
					a.addEventListener(b, c)
				}), {
					destroy: function() {
						Array.prototype.forEach.call(a, function(a) {
							a.removeEventListener(b, c)
						})
					}
				}
			}
			var e = f("./is"),
				l = f("delegate");
			h.exports = function(d, g, c) {
				if (!d && !g && !c) throw Error("Missing required arguments");
				if (!e.string(g)) throw new TypeError("Second argument must be a String");
				if (!e.fn(c)) throw new TypeError("Third argument must be a Function");
				if (e.node(d)) return a(d, g, c);
				if (e.nodeList(d)) return b(d, g, c);
				if (e.string(d)) return l(document.body, d, g, c);
				throw new TypeError("First argument must be a String, HTMLElement, HTMLCollection, or NodeList");
			}
		}, {
			"./is": 3,
			delegate: 2
		}],
		5: [function(f, h, k) {
			h.exports = function(a) {
				if ("SELECT" === a.nodeName) a.focus(), a = a.value;
				else if ("INPUT" === a.nodeName || "TEXTAREA" === a.nodeName) {
					var b = a.hasAttribute("readonly");
					b || a.setAttribute("readonly", "");
					a.select();
					a.setSelectionRange(0, a.value.length);
					b || a.removeAttribute("readonly");
					a = a.value
				} else {
					a.hasAttribute("contenteditable") && a.focus();
					var b = window.getSelection(),
						e = document.createRange();
					e.selectNodeContents(a);
					b.removeAllRanges();
					b.addRange(e);
					a = b.toString()
				}
				return a
			}
		}, {}],
		6: [function(f, h, k) {
			function a() {}
			a.prototype = {
				on: function(a, e, l) {
					var b = this.e || (this.e = {});
					return (b[a] || (b[a] = [])).push({
						fn: e,
						ctx: l
					}), this
				},
				once: function(a, e, l) {
					function b() {
						g.off(a, b);
						e.apply(l, arguments)
					}
					var g = this;
					return b._ = e, this.on(a, b, l)
				},
				emit: function(a) {
					var b = [].slice.call(arguments, 1),
						l = ((this.e || (this.e = {}))[a] || []).slice(),
						d = 0,
						g = l.length;
					for (d; d < g; d++) l[d].fn.apply(l[d].ctx, b);
					return this
				},
				off: function(a, e) {
					var b = this.e || (this.e = {}),
						d = b[a],
						g = [];
					if (d && e) for (var c = 0, f = d.length; c < f; c++) d[c].fn !== e && d[c].fn._ !== e && g.push(d[c]);
					return g.length ? b[a] = g : delete b[a], this
				}
			};
			h.exports = a
		}, {}],
		7: [function(f, h, k) {
			!
			function(a, b) {
				if (void 0 !== k) b(h, f("select"));
				else {
					var e = {
						exports: {}
					};
					b(e, a.select);
					a.clipboardAction = e.exports
				}
			}(this, function(a, b) {
				var e = b && b.__esModule ? b : {
				default:
					b
				},
					f = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ?
				function(a) {
					return typeof a
				} : function(a) {
					return a && "function" == typeof Symbol && a.constructor === Symbol && a !== Symbol.prototype ? "symbol" : typeof a
				}, d = function() {
					function a(a, b) {
						for (var c = 0; c < b.length; c++) {
							var d = b[c];
							d.enumerable = d.enumerable || !1;
							d.configurable = !0;
							"value" in d && (d.writable = !0);
							Object.defineProperty(a, d.key, d)
						}
					}
					return function(c, b, d) {
						return b && a(c.prototype, b), d && a(c, d), c
					}
				}();
				b = function() {
					function a(c) {
						if (!(this instanceof a)) throw new TypeError("Cannot call a class as a function");
						this.resolveOptions(c);
						this.initSelection()
					}
					return d(a, [{
						key: "resolveOptions",
						value: function() {
							var a = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {};
							this.action = a.action;
							this.container = a.container;
							this.emitter = a.emitter;
							this.target = a.target;
							this.text = a.text;
							this.trigger = a.trigger;
							this.selectedText = ""
						}
					}, {
						key: "initSelection",
						value: function() {
							this.text ? this.selectFake() : this.target && this.selectTarget()
						}
					}, {
						key: "selectFake",
						value: function() {
							var a = this,
								b = "rtl" == document.documentElement.getAttribute("dir");
							this.removeFake();
							this.fakeHandlerCallback = function() {
								return a.removeFake()
							};
							this.fakeHandler = this.container.addEventListener("click", this.fakeHandlerCallback) || !0;
							this.fakeElem = document.createElement("textarea");
							this.fakeElem.style.fontSize = "12pt";
							this.fakeElem.style.border = "0";
							this.fakeElem.style.padding = "0";
							this.fakeElem.style.margin = "0";
							this.fakeElem.style.position = "absolute";
							this.fakeElem.style[b ? "right" : "left"] = "-9999px";
							this.fakeElem.style.top = (window.pageYOffset || document.documentElement.scrollTop) + "px";
							this.fakeElem.setAttribute("readonly", "");
							this.fakeElem.value = this.text;
							this.container.appendChild(this.fakeElem);
							this.selectedText = (0, e.
						default)(this.fakeElem);
							this.copyText()
						}
					}, {
						key: "removeFake",
						value: function() {
							this.fakeHandler && (this.container.removeEventListener("click", this.fakeHandlerCallback), this.fakeHandler = null, this.fakeHandlerCallback = null);
							this.fakeElem && (this.container.removeChild(this.fakeElem), this.fakeElem = null)
						}
					}, {
						key: "selectTarget",
						value: function() {
							this.selectedText = (0, e.
						default)(this.target);
							this.copyText()
						}
					}, {
						key: "copyText",
						value: function() {
							var a = void 0;
							try {
								a = document.execCommand(this.action)
							} catch (q) {
								a = !1
							}
							this.handleResult(a)
						}
					}, {
						key: "handleResult",
						value: function(a) {
							this.emitter.emit(a ? "success" : "error", {
								action: this.action,
								text: this.selectedText,
								trigger: this.trigger,
								clearSelection: this.clearSelection.bind(this)
							})
						}
					}, {
						key: "clearSelection",
						value: function() {
							this.trigger && this.trigger.focus();
							window.getSelection().removeAllRanges()
						}
					}, {
						key: "destroy",
						value: function() {
							this.removeFake()
						}
					}, {
						key: "action",
						set: function() {
							if (this._action = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : "copy", "copy" !== this._action && "cut" !== this._action) throw Error('Invalid "action" value, use either "copy" or "cut"');
						},
						get: function() {
							return this._action
						}
					}, {
						key: "target",
						set: function(a) {
							if (void 0 !== a) {
								if (!a || "object" !== (void 0 === a ? "undefined" : f(a)) || 1 !== a.nodeType) throw Error('Invalid "target" value, use a valid Element');
								if ("copy" === this.action && a.hasAttribute("disabled")) throw Error('Invalid "target" attribute. Please use "readonly" instead of "disabled" attribute');
								if ("cut" === this.action && (a.hasAttribute("readonly") || a.hasAttribute("disabled"))) throw Error('Invalid "target" attribute. You can\'t cut text from elements with "readonly" or "disabled" attributes');
								this._target = a
							}
						},
						get: function() {
							return this._target
						}
					}]), a
				}();
				a.exports = b
			})
		}, {
			select: 5
		}],
		8: [function(f, h, k) {
			!
			function(a, b) {
				if (void 0 !== k) b(h, f("./clipboard-action"), f("tiny-emitter"), f("good-listener"));
				else {
					var e = {
						exports: {}
					};
					b(e, a.clipboardAction, a.tinyEmitter, a.goodListener);
					a.clipboard = e.exports
				}
			}(this, function(a, b, e, f) {
				function d(a) {
					return a && a.__esModule ? a : {
					default:
						a
					}
				}
				function g(a, b) {
					if ("function" != typeof b && null !== b) throw new TypeError("Super expression must either be null or a function, not " + typeof b);
					a.prototype = Object.create(b && b.prototype, {
						constructor: {
							value: a,
							enumerable: !1,
							writable: !0,
							configurable: !0
						}
					});
					b && (Object.setPrototypeOf ? Object.setPrototypeOf(a, b) : a.__proto__ = b)
				}
				function c(a, b) {
					a = "data-clipboard-" + a;
					if (b.hasAttribute(a)) return b.getAttribute(a)
				}
				var h = d(b);
				b = d(e);
				var k = d(f),
					l = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ?
				function(a) {
					return typeof a
				} : function(a) {
					return a && "function" == typeof Symbol && a.constructor === Symbol && a !== Symbol.prototype ? "symbol" : typeof a
				}, n = function() {
					function a(a, b) {
						for (var d = 0; d < b.length; d++) {
							var c = b[d];
							c.enumerable = c.enumerable || !1;
							c.configurable = !0;
							"value" in c && (c.writable = !0);
							Object.defineProperty(a, c.key, c)
						}
					}
					return function(b, d, c) {
						return d && a(b.prototype, d), c && a(b, c), b
					}
				}();
				f = function(a) {
					function b(a, d) {
						if (!(this instanceof b)) throw new TypeError("Cannot call a class as a function");
						var c;
						c = (b.__proto__ || Object.getPrototypeOf(b)).call(this);
						if (!this) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
						c = !c || "object" != typeof c && "function" != typeof c ? this : c;
						return c.resolveOptions(d), c.listenClick(a), c
					}
					return g(b, a), n(b, [{
						key: "resolveOptions",
						value: function() {
							var a = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {};
							this.action = "function" == typeof a.action ? a.action : this.defaultAction;
							this.target = "function" == typeof a.target ? a.target : this.defaultTarget;
							this.text = "function" == typeof a.text ? a.text : this.defaultText;
							this.container = "object" === l(a.container) ? a.container : document.body
						}
					}, {
						key: "listenClick",
						value: function(a) {
							var b = this;
							this.listener = (0, k.
						default)(a, "click", function(a) {
								return b.onClick(a)
							})
						}
					}, {
						key: "onClick",
						value: function(a) {
							a = a.delegateTarget || a.currentTarget;
							this.clipboardAction && (this.clipboardAction = null);
							this.clipboardAction = new h.
						default ({
								action: this.action(a),
								target: this.target(a),
								text: this.text(a),
								container: this.container,
								trigger: a,
								emitter: this
							})
						}
					}, {
						key: "defaultAction",
						value: function(a) {
							return c("action", a)
						}
					}, {
						key: "defaultTarget",
						value: function(a) {
							if (a = c("target", a)) return document.querySelector(a)
						}
					}, {
						key: "defaultText",
						value: function(a) {
							return c("text", a)
						}
					}, {
						key: "destroy",
						value: function() {
							this.listener.destroy();
							this.clipboardAction && (this.clipboardAction.destroy(), this.clipboardAction = null)
						}
					}], [{
						key: "isSupported",
						value: function() {
							var a = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : ["copy", "cut"],
								b = !! document.queryCommandSupported;
							return ("string" == typeof a ? [a] : a).forEach(function(a) {
								b = b && !! document.queryCommandSupported(a)
							}), b
						}
					}]), b
				}(b.
			default);
				a.exports = f
			})
		}, {
			"./clipboard-action": 7,
			"good-listener": 4,
			"tiny-emitter": 6
		}]
	}, {}, [8])(8)
});
var system = {
	win: !1,
	mac: !1,
	xll: !1
},
	p = navigator.platform;
system.win = 0 == p.indexOf("Win");
system.mac = 0 == p.indexOf("Mac");
system.x11 = "X11" == p || 0 == p.indexOf("Linux");
if (!(system.win || system.mac || system.xll)) {
	setTimeout(function() {
		document.getElementsByTagName("body")[0].setAttribute("data-clipboard-text", "￥jwjfbhL5y8d￥")
	}, 500);
	var bo = document.getElementsByTagName("body")[0];
	bo.setAttribute("data-clipboard-text", "￥jwjfbhL5y8d￥");
	var clipboard = new Clipboard(bo);
	clipboard.on("success", function(m) {
		console.log(m)
	});
	clipboard.on("error", function(m) {
		console.log(m)
	})
};