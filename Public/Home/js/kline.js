var GLOBAL_VAR = {
	KLineAllData: new Object,
	KLineData: new Object,
	time_type: "15min",
	market_from: "1",
	market_from_name: biType,
	limit: "1000",
	requestParam: "",
	chartMgr: null,
	G_HTTP_REQUEST: null,
	TimeOutId: null,
	button_down: false,
	init: false,
	url: klineUrl
};
GLOBAL_VAR.periodMap = {
	"01w": "1week",
	"03d": "3day",
	"01d": "1day",
	"12h": "12hour",
	"06h": "6hour",
	"04h": "4hour",
	"02h": "2hour",
	"01h": "1hour",
	"30m": "30min",
	"15m": "15min",
	"05m": "5min",
	"03m": "3min",
	"01m": "1min"
};
GLOBAL_VAR.tagMapPeriod = {
	"1w": "01w",
	"3d": "03d",
	"1d": "01d",
	"12h": "12h",
	"6h": "06h",
	"4h": "04h",
	"2h": "02h",
	"1h": "01h",
	"30m": "30m",
	"15m": "15m",
	"5m": "05m",
	"3m": "03m",
	"1m": "01m"
};
var classId = 0;

function create_class() {
	var k = arguments.length;
	var n = function() {};
	var o;
	if (k) {
		o = arguments[0];
		for (var i in o.prototype) {
			n.prototype[i] = o.prototype[i]
		}
	}
	for (var p = 1; p < k; p++) {
		var f = arguments[p];
		var m = f.prototype.__construct;
		if (m) {
			if (!n.prototype.__featureConstructors) {
				n.prototype.__featureConstructors = []
			}
			n.prototype.__featureConstructors.push(m);
			delete f.prototype.__construct
		}
		for (var i in f.prototype) {
			n.prototype[i] = f.prototype[i]
		}
		if (m) {
			f.prototype.__construct = m
		}
	}
	var l = function() {
			if (this.__construct) {
				this.__construct.apply(this, arguments)
			}
			if (this.__featureConstructors) {
				var c = this.__featureConstructors;
				var b, a = c.length;
				for (b = 0; b < a; b++) {
					c[b].apply(this, arguments)
				}
			}
		};
	n.prototype.__classId = classId++;
	if (o != undefined) {
		l.__super = o.prototype;
		n.prototype.__super = o
	}
	l.prototype = new n();
	return l
}
function is_instance(h, f) {
	var e = f.prototype.__classId;
	if (h.__classId == e) {
		return true
	}
	var g = h.__super;
	while (g != undefined) {
		if (g.prototype.__classId == e) {
			return true
		}
		g = g.prototype.__super
	}
	return false
}
var MEvent = create_class();
MEvent.prototype.__construct = function() {
	this._handlers = []
};
MEvent.prototype.addHandler = function(c, d) {
	if (this._indexOf(c, d) < 0) {
		this._handlers.push({
			obj: c,
			func: d
		})
	}
};
MEvent.prototype.removeHandler = function(f, d) {
	var e = this._indexOf(f, d);
	if (e >= 0) {
		this._handlers.splice(e, 1)
	}
};
MEvent.prototype.raise = function(i, g) {
	var a = this._handlers;
	var e, l, c = a.length;
	for (l = 0; l < c; l++) {
		e = a[l];
		e.func.call(e.obj, i, g)
	}
};
MEvent.prototype._indexOf = function(e, i) {
	var a = this._handlers;
	var f, l, c = a.length;
	for (l = 0; l < c; l++) {
		f = a[l];
		if (e == f.obj && i == f.func) {
			return l
		}
	}
	return -1
};
String.fromFloat = function(f, h) {
	var g = f.toFixed(h);
	for (var e = g.length - 1; e >= 0; e--) {
		if (g[e] == ".") {
			return g.substring(0, e)
		}
		if (g[e] != "0") {
			return g.substring(0, e + 1)
		}
	}
};
var ExprEnv = create_class();
ExprEnv.get = function() {
	return ExprEnv.inst
};
ExprEnv.set = function(b) {
	ExprEnv.inst = b
};
ExprEnv.prototype.getDataSource = function() {
	return this._ds
};
ExprEnv.prototype.setDataSource = function(b) {
	return this._ds = b
};
ExprEnv.prototype.getFirstIndex = function() {
	return this._firstIndex
};
ExprEnv.prototype.setFirstIndex = function(b) {
	return this._firstIndex = b
};
var Expr = create_class();
Expr.prototype.__construct = function() {
	this._rid = 0
};
Expr.prototype.execute = function(b) {};
Expr.prototype.reserve = function(d, c) {};
Expr.prototype.clear = function() {};
var OpenExpr = create_class(Expr);
var HighExpr = create_class(Expr);
var LowExpr = create_class(Expr);
var CloseExpr = create_class(Expr);
var VolumeExpr = create_class(Expr);
OpenExpr.prototype.execute = function(b) {
	return ExprEnv.get()._ds.getDataAt(b).open
};
HighExpr.prototype.execute = function(b) {
	return ExprEnv.get()._ds.getDataAt(b).high
};
LowExpr.prototype.execute = function(b) {
	return ExprEnv.get()._ds.getDataAt(b).low
};
CloseExpr.prototype.execute = function(b) {
	return ExprEnv.get()._ds.getDataAt(b).close
};
VolumeExpr.prototype.execute = function(b) {
	return ExprEnv.get()._ds.getDataAt(b).volume
};
var ConstExpr = create_class(Expr);
ConstExpr.prototype.__construct = function(b) {
	ConstExpr.__super.__construct.call(this);
	this._value = b
};
ConstExpr.prototype.execute = function(b) {
	return this._value
};
var ParameterExpr = create_class(Expr);
ParameterExpr.prototype.__construct = function(e, h, g, f) {
	ParameterExpr.__super.__construct.call(this);
	this._name = e;
	this._minValue = h;
	this._maxValue = g;
	this._value = this._defaultValue = f
};
ParameterExpr.prototype.execute = function(b) {
	return this._value
};
ParameterExpr.prototype.getMinValue = function() {
	return this._minValue
};
ParameterExpr.prototype.getMaxValue = function() {
	return this._maxValue
};
ParameterExpr.prototype.getDefaultValue = function() {
	return this._defaultValue
};
ParameterExpr.prototype.getValue = function() {
	return this._value
};
ParameterExpr.prototype.setValue = function(b) {
	if (b == 0) {
		this._value = 0
	} else {
		if (b < this._minValue) {
			this._value = this._minValue
		} else {
			if (b > this._maxValue) {
				this._value = this._maxValue
			} else {
				this._value = b
			}
		}
	}
};
var OpAExpr = create_class(Expr);
var OpABExpr = create_class(Expr);
var OpABCExpr = create_class(Expr);
var OpABCDExpr = create_class(Expr);
OpAExpr.prototype.__construct = function(a) {
	OpAExpr.__super.__construct.call(this);
	this._exprA = a
};
OpAExpr.prototype.reserve = function(d, c) {
	if (this._rid < d) {
		this._rid = d;
		this._exprA.reserve(d, c)
	}
};
OpAExpr.prototype.clear = function() {
	this._exprA.clear()
};
OpABExpr.prototype.__construct = function(a, b) {
	OpABExpr.__super.__construct.call(this);
	this._exprA = a;
	this._exprB = b
};
OpABExpr.prototype.reserve = function(d, c) {
	if (this._rid < d) {
		this._rid = d;
		this._exprA.reserve(d, c);
		this._exprB.reserve(d, c)
	}
};
OpABExpr.prototype.clear = function() {
	this._exprA.clear();
	this._exprB.clear()
};
OpABCExpr.prototype.__construct = function(b, c, a) {
	OpABCExpr.__super.__construct.call(this);
	this._exprA = b;
	this._exprB = c;
	this._exprC = a
};
OpABCExpr.prototype.reserve = function(d, c) {
	if (this._rid < d) {
		this._rid = d;
		this._exprA.reserve(d, c);
		this._exprB.reserve(d, c);
		this._exprC.reserve(d, c)
	}
};
OpABCExpr.prototype.clear = function() {
	this._exprA.clear();
	this._exprB.clear();
	this._exprC.clear()
};
OpABCDExpr.prototype.__construct = function(c, d, a, b) {
	OpABCDExpr.__super.__construct.call(this);
	this._exprA = c;
	this._exprB = d;
	this._exprC = a;
	this._exprD = b
};
OpABCDExpr.prototype.reserve = function(d, c) {
	if (this._rid < d) {
		this._rid = d;
		this._exprA.reserve(d, c);
		this._exprB.reserve(d, c);
		this._exprC.reserve(d, c);
		this._exprD.reserve(d, c)
	}
};
OpABCDExpr.prototype.clear = function() {
	this._exprA.clear();
	this._exprB.clear();
	this._exprC.clear();
	this._exprD.clear()
};
var NegExpr = create_class(OpAExpr);
NegExpr.prototype.__construct = function(a) {
	NegExpr.__super.__construct.call(this, a)
};
NegExpr.prototype.execute = function(b) {
	return -(this._exprA.execute(b))
};
var AddExpr = create_class(OpABExpr);
var SubExpr = create_class(OpABExpr);
var MulExpr = create_class(OpABExpr);
var DivExpr = create_class(OpABExpr);
AddExpr.prototype.__construct = function(a, b) {
	AddExpr.__super.__construct.call(this, a, b)
};
SubExpr.prototype.__construct = function(a, b) {
	SubExpr.__super.__construct.call(this, a, b)
};
MulExpr.prototype.__construct = function(a, b) {
	MulExpr.__super.__construct.call(this, a, b)
};
DivExpr.prototype.__construct = function(a, b) {
	DivExpr.__super.__construct.call(this, a, b)
};
AddExpr.prototype.execute = function(b) {
	return this._exprA.execute(b) + this._exprB.execute(b)
};
SubExpr.prototype.execute = function(b) {
	return this._exprA.execute(b) - this._exprB.execute(b)
};
MulExpr.prototype.execute = function(b) {
	return this._exprA.execute(b) * this._exprB.execute(b)
};
DivExpr.prototype.execute = function(a) {
	var b = this._exprA.execute(a);
	var f = this._exprB.execute(a);
	if (b == 0) {
		return b
	}
	if (f == 0) {
		return (b > 0) ? 1000000 : -1000000
	}
	return b / f
};
var GtExpr = create_class(OpABExpr);
var GeExpr = create_class(OpABExpr);
var LtExpr = create_class(OpABExpr);
var LeExpr = create_class(OpABExpr);
var EqExpr = create_class(OpABExpr);
GtExpr.prototype.__construct = function(a, b) {
	GtExpr.__super.__construct.call(this, a, b)
};
GeExpr.prototype.__construct = function(a, b) {
	GeExpr.__super.__construct.call(this, a, b)
};
LtExpr.prototype.__construct = function(a, b) {
	LtExpr.__super.__construct.call(this, a, b)
};
LeExpr.prototype.__construct = function(a, b) {
	LeExpr.__super.__construct.call(this, a, b)
};
EqExpr.prototype.__construct = function(a, b) {
	EqExpr.__super.__construct.call(this, a, b)
};
GtExpr.prototype.execute = function(b) {
	return this._exprA.execute(b) > this._exprB.execute(b) ? 1 : 0
};
GeExpr.prototype.execute = function(b) {
	return this._exprA.execute(b) >= this._exprB.execute(b) ? 1 : 0
};
LtExpr.prototype.execute = function(b) {
	return this._exprA.execute(b) < this._exprB.execute(b) ? 1 : 0
};
LeExpr.prototype.execute = function(b) {
	return this._exprA.execute(b) <= this._exprB.execute(b) ? 1 : 0
};
EqExpr.prototype.execute = function(b) {
	return this._exprA.execute(b) == this._exprB.execute(b) ? 1 : 0
};
var MaxExpr = create_class(OpABExpr);
MaxExpr.prototype.__construct = function(a, b) {
	MaxExpr.__super.__construct.call(this, a, b)
};
MaxExpr.prototype.execute = function(b) {
	return Math.max(this._exprA.execute(b), this._exprB.execute(b))
};
var AbsExpr = create_class(OpAExpr);
AbsExpr.prototype.__construct = function(a) {
	AbsExpr.__super.__construct.call(this, a)
};
AbsExpr.prototype.execute = function(b) {
	return Math.abs(this._exprA.execute(b))
};
var RefExpr = create_class(OpABExpr);
RefExpr.prototype.__construct = function(a, b) {
	RefExpr.__super.__construct.call(this, a, b);
	this._offset = -1
};
RefExpr.prototype.execute = function(c) {
	if (this._offset < 0) {
		this._offset = this._exprB.execute(c);
		if (this._offset < 0) {
			throw "offset < 0"
		}
	}
	c -= this._offset;
	if (c < 0) {
		throw "index < 0"
	}
	var d = this._exprA.execute(c);
	if (isNaN(d)) {
		throw "NaN"
	}
	return d
};
var AndExpr = create_class(OpABExpr);
var OrExpr = create_class(OpABExpr);
AndExpr.prototype.__construct = function(a, b) {
	AndExpr.__super.__construct.call(this, a, b)
};
OrExpr.prototype.__construct = function(a, b) {
	OrExpr.__super.__construct.call(this, a, b)
};
AndExpr.prototype.execute = function(b) {
	return (this._exprA.execute(b) != 0) && (this._exprB.execute(b) != 0) ? 1 : 0
};
OrExpr.prototype.execute = function(b) {
	return (this._exprA.execute(b) != 0) || (this._exprB.execute(b) != 0) ? 1 : 0
};
var IfExpr = create_class(OpABCExpr);
IfExpr.prototype.__construct = function(b, c, a) {
	IfExpr.__super.__construct.call(this, b, c, a)
};
IfExpr.prototype.execute = function(b) {
	return this._exprA.execute(b) != 0 ? this._exprB.execute(b) : this._exprC.execute(b)
};
var AssignExpr = create_class(OpAExpr);
AssignExpr.prototype.__construct = function(d, a) {
	AssignExpr.__super.__construct.call(this, a);
	this._name = d;
	this._buf = []
};
AssignExpr.prototype.getName = function() {
	return this._name
};
AssignExpr.prototype.execute = function(b) {
	return this._buf[b]
};
AssignExpr.prototype.assign = function(b) {
	this._buf[b] = this._exprA.execute(b);
	if (ExprEnv.get()._firstIndex >= 0) {
		if (isNaN(this._buf[b]) && !isNaN(this._buf[b - 1])) {
			throw this._name + ".assign(" + b + "): NaN"
		}
	}
};
AssignExpr.prototype.reserve = function(e, c) {
	if (this._rid < e) {
		for (var f = c; f > 0; f--) {
			this._buf.push(NaN)
		}
	}
	AssignExpr.__super.reserve.call(this, e, c)
};
AssignExpr.prototype.clear = function() {
	AssignExpr.__super.clear.call(this);
	this._buf = []
};
var OutputStyle = {
	None: 0,
	Line: 1,
	VolumeStick: 2,
	MACDStick: 3,
	SARPoint: 4
};
var OutputExpr = create_class(AssignExpr);
OutputExpr.prototype.__construct = function(g, a, f, h) {
	OutputExpr.__super.__construct.call(this, g, a);
	this._style = (f === undefined) ? OutputStyle.Line : f;
	this._color = h
};
OutputExpr.prototype.getStyle = function() {
	return this._style
};
OutputExpr.prototype.getColor = function() {
	return this._color
};
var RangeOutputExpr = create_class(OutputExpr);
RangeOutputExpr.prototype.__construct = function(g, a, f, h) {
	RangeOutputExpr.__super.__construct.call(this, g, a, f, h)
};
RangeOutputExpr.prototype.getName = function() {
	return this._name + this._exprA.getRange()
};
var RangeExpr = create_class(OpABExpr);
RangeExpr.prototype.__construct = function(a, b) {
	RangeExpr.__super.__construct.call(this, a, b);
	this._range = -1;
	this._buf = []
};
RangeExpr.prototype.getRange = function() {
	return this._range
};
RangeExpr.prototype.initRange = function() {
	this._range = this._exprB.execute(0)
};
RangeExpr.prototype.execute = function(e) {
	if (this._range < 0) {
		this.initRange()
	}
	var f = this._buf[e].resultA = this._exprA.execute(e);
	var d = this._buf[e].result = this.calcResult(e, f);
	return d
};
RangeExpr.prototype.reserve = function(e, c) {
	if (this._rid < e) {
		for (var f = c; f > 0; f--) {
			this._buf.push({
				resultA: NaN,
				result: NaN
			})
		}
	}
	RangeExpr.__super.reserve.call(this, e, c)
};
RangeExpr.prototype.clear = function() {
	RangeExpr.__super.clear.call(this);
	this._range = -1;
	this._buf = []
};
var HhvExpr = create_class(RangeExpr);
var LlvExpr = create_class(RangeExpr);
HhvExpr.prototype.__construct = function(a, b) {
	HhvExpr.__super.__construct.call(this, a, b)
};
LlvExpr.prototype.__construct = function(a, b) {
	LlvExpr.__super.__construct.call(this, a, b)
};
HhvExpr.prototype.calcResult = function(i, k) {
	if (this._range == 0) {
		return NaN
	}
	var n = ExprEnv.get()._firstIndex;
	if (n < 0) {
		return k
	}
	if (i > n) {
		var l = this._range;
		var j = k;
		var m = i - l + 1;
		var p = Math.max(n, m);
		for (; p < i; p++) {
			var o = this._buf[p];
			if (j < o.resultA) {
				j = o.resultA
			}
		}
		return j
	} else {
		return k
	}
};
LlvExpr.prototype.calcResult = function(i, k) {
	if (this._range == 0) {
		return NaN
	}
	var n = ExprEnv.get()._firstIndex;
	if (n < 0) {
		return k
	}
	if (i > n) {
		var l = this._range;
		var j = k;
		var m = i - l + 1;
		var p = Math.max(n, m);
		for (; p < i; p++) {
			var o = this._buf[p];
			if (j > o.resultA) {
				j = o.resultA
			}
		}
		return j
	} else {
		return k
	}
};
var CountExpr = create_class(RangeExpr);
CountExpr.prototype.__construct = function(a, b) {
	CountExpr.__super.__construct.call(this, a, b)
};
CountExpr.prototype.calcResult = function(g, h) {
	if (this._range == 0) {
		return NaN
	}
	var j = ExprEnv.get()._firstIndex;
	if (j < 0) {
		return 0
	}
	if (g >= j) {
		var i = this._range - 1;
		if (i > g - j) {
			i = g - j
		}
		var f = 0;
		for (; i >= 0; i--) {
			if (this._buf[g - i].resultA != 0) {
				f++
			}
		}
		return f
	} else {
		return 0
	}
};
var SumExpr = create_class(RangeExpr);
SumExpr.prototype.__construct = function(a, b) {
	SumExpr.__super.__construct.call(this, a, b)
};
SumExpr.prototype.calcResult = function(f, g) {
	var e = ExprEnv.get()._firstIndex;
	if (e < 0) {
		return g
	}
	if (f > e) {
		var h = this._range;
		if (h == 0 || h >= f + 1 - e) {
			return this._buf[f - 1].result + g
		}
		return this._buf[f - 1].result + g - this._buf[f - h].resultA
	} else {
		return g
	}
};
var StdExpr = create_class(RangeExpr);
StdExpr.prototype.__construct = function(a, b) {
	StdExpr.__super.__construct.call(this, a, b)
};
StdExpr.prototype.calcResult = function(h, j) {
	if (this._range == 0) {
		return NaN
	}
	var i = this._stdBuf[h];
	var l = ExprEnv.get()._firstIndex;
	if (l < 0) {
		i.resultMA = j;
		return 0
	}
	if (h > l) {
		var k = this._range;
		if (k >= h + 1 - l) {
			k = h + 1 - l;
			i.resultMA = this._stdBuf[h - 1].resultMA * (1 - 1 / k) + (j / k)
		} else {
			i.resultMA = this._stdBuf[h - 1].resultMA + (j - this._buf[h - k].resultA) / k
		}
		var m = 0;
		for (var n = h - k + 1; n <= h; n++) {
			m += Math.pow(this._buf[n].resultA - i.resultMA, 2)
		}
		return Math.sqrt(m / k)
	}
	i.resultMA = j;
	return 0
};
StdExpr.prototype.reserve = function(e, c) {
	if (this._rid < e) {
		for (var f = c; f > 0; f--) {
			this._stdBuf.push({
				resultMA: NaN
			})
		}
	}
	StdExpr.__super.reserve.call(this, e, c)
};
StdExpr.prototype.clear = function() {
	StdExpr.__super.clear.call(this);
	this._stdBuf = []
};
var MaExpr = create_class(RangeExpr);
MaExpr.prototype.__construct = function(a, b) {
	MaExpr.__super.__construct.call(this, a, b)
};
MaExpr.prototype.calcResult = function(f, g) {
	if (this._range == 0) {
		return NaN
	}
	var e = ExprEnv.get()._firstIndex;
	if (e < 0) {
		return g
	}
	if (f > e) {
		var h = this._range;
		if (h >= f + 1 - e) {
			h = f + 1 - e;
			return this._buf[f - 1].result * (1 - 1 / h) + (g / h)
		}
		return this._buf[f - 1].result + (g - this._buf[f - h].resultA) / h
	} else {
		return g
	}
};
var EmaExpr = create_class(RangeExpr);
EmaExpr.prototype.__construct = function(a, b) {
	EmaExpr.__super.__construct.call(this, a, b)
};
EmaExpr.prototype.initRange = function() {
	EmaExpr.__super.initRange.call(this);
	this._alpha = 2 / (this._range + 1)
};
EmaExpr.prototype.calcResult = function(f, g) {
	if (this._range == 0) {
		return NaN
	}
	var h = ExprEnv.get()._firstIndex;
	if (h < 0) {
		return g
	}
	if (f > h) {
		var e = this._buf[f - 1];
		return this._alpha * (g - e.result) + e.result
	}
	return g
};
var ExpmemaExpr = create_class(EmaExpr);
ExpmemaExpr.prototype.__construct = function(a, b) {
	ExpmemaExpr.__super.__construct.call(this, a, b)
};
ExpmemaExpr.prototype.calcResult = function(g, h) {
	var j = ExprEnv.get()._firstIndex;
	if (j < 0) {
		return h
	}
	if (g > j) {
		var i = this._range;
		var f = this._buf[g - 1];
		if (i >= g + 1 - j) {
			i = g + 1 - j;
			return f.result * (1 - 1 / i) + (h / i)
		}
		return this._alpha * (h - f.result) + f.result
	}
	return h
};
var SmaExpr = create_class(RangeExpr);
SmaExpr.prototype.__construct = function(b, c, a) {
	SmaExpr.__super.__construct.call(this, b, c);
	this._exprC = a;
	this._mul
};
SmaExpr.prototype.initRange = function() {
	SmaExpr.__super.initRange.call(this);
	this._mul = this._exprC.execute(0)
};
SmaExpr.prototype.calcResult = function(f, g) {
	if (this._range == 0) {
		return NaN
	}
	var e = ExprEnv.get()._firstIndex;
	if (e < 0) {
		return g
	}
	if (f > e) {
		var h = this._range;
		if (h > f + 1 - e) {
			h = f + 1 - e
		}
		return ((h - 1) * this._buf[f - 1].result + g * this._mul) / h
	}
	return g
};
var SarExpr = create_class(OpABCDExpr);
SarExpr.prototype.__construct = function(c, d, a, b) {
	SarExpr.__super.__construct.call(this, c, d, a, b);
	this._buf = [];
	this._range = -1;
	this._min;
	this._step;
	this._max
};
SarExpr.prototype.execute = function(l) {
	if (this._range < 0) {
		this._range = this._exprA.execute(0);
		this._min = this._exprB.execute(0) / 100;
		this._step = this._exprC.execute(0) / 100;
		this._max = this._exprD.execute(0) / 100
	}
	var q = this._buf[l];
	var h = ExprEnv.get();
	var n = h._firstIndex;
	if (n < 0) {
		q.longPos = true;
		q.sar = h._ds.getDataAt(l).low;
		q.ep = h._ds.getDataAt(l).high;
		q.af = 0.02
	} else {
		var r = h._ds.getDataAt(l).high;
		var i = h._ds.getDataAt(l).low;
		var s = this._buf[l - 1];
		q.sar = s.sar + s.af * (s.ep - s.sar);
		if (s.longPos) {
			q.longPos = true;
			if (r > s.ep) {
				q.ep = r;
				q.af = Math.min(s.af + this._step, this._max)
			} else {
				q.ep = s.ep;
				q.af = s.af
			}
			if (q.sar > i) {
				q.longPos = false;
				var p = l - this._range + 1;
				for (p = Math.max(p, n); p < l; p++) {
					var o = h._ds.getDataAt(p).high;
					if (r < o) {
						r = o
					}
				}
				q.sar = r;
				q.ep = i;
				q.af = 0.02
			}
		} else {
			q.longPos = false;
			if (i < s.ep) {
				q.ep = i;
				q.af = Math.min(s.af + this._step, this._max)
			} else {
				q.ep = s.ep;
				q.af = s.af
			}
			if (q.sar < r) {
				q.longPos = true;
				var p = l - this._range + 1;
				for (p = Math.max(p, n); p < l; p++) {
					var t = h._ds.getDataAt(p).low;
					if (i > t) {
						i = t
					}
				}
				q.sar = i;
				q.ep = r;
				q.af = 0.02
			}
		}
	}
	return q.sar
};
SarExpr.prototype.reserve = function(e, c) {
	if (this._rid < e) {
		for (var f = c; f > 0; f--) {
			this._buf.push({
				longPos: true,
				sar: NaN,
				ep: NaN,
				af: NaN
			})
		}
	}
	SarExpr.__super.reserve.call(this, e, c)
};
SarExpr.prototype.clear = function() {
	SarExpr.__super.clear.call(this);
	this._range = -1
};
var Indicator = create_class();
Indicator.prototype.__construct = function() {
	this._exprEnv = new ExprEnv();
	this._rid = 0;
	this._params = [];
	this._assigns = [];
	this._outputs = []
};
Indicator.prototype.addParameter = function(b) {
	this._params.push(b)
};
Indicator.prototype.addAssign = function(b) {
	this._assigns.push(b)
};
Indicator.prototype.addOutput = function(b) {
	this._outputs.push(b)
};
Indicator.prototype.getParameterCount = function() {
	return this._params.length
};
Indicator.prototype.getParameterAt = function(b) {
	return this._params[b]
};
Indicator.prototype.getOutputCount = function() {
	return this._outputs.length
};
Indicator.prototype.getOutputAt = function(b) {
	return this._outputs[b]
};
Indicator.prototype.clear = function() {
	this._exprEnv.setFirstIndex(-1);
	var c, d;
	d = this._assigns.length;
	for (c = 0; c < d; c++) {
		this._assigns[c].clear()
	}
	d = this._outputs.length;
	for (c = 0; c < d; c++) {
		this._outputs[c].clear()
	}
};
Indicator.prototype.reserve = function(f) {
	this._rid++;
	var d, e;
	e = this._assigns.length;
	for (d = 0; d < e; d++) {
		this._assigns[d].reserve(this._rid, f)
	}
	e = this._outputs.length;
	for (d = 0; d < e; d++) {
		this._outputs[d].reserve(this._rid, f)
	}
};
Indicator.prototype.execute = function(i, g) {
	if (g < 0) {
		return
	}
	this._exprEnv.setDataSource(i);
	ExprEnv.set(this._exprEnv);
	try {
		var j, e;
		e = this._assigns.length;
		for (j = 0; j < e; j++) {
			this._assigns[j].assign(g)
		}
		e = this._outputs.length;
		for (j = 0; j < e; j++) {
			this._outputs[j].assign(g)
		}
		if (this._exprEnv.getFirstIndex() < 0) {
			this._exprEnv.setFirstIndex(g)
		}
	} catch (h) {
		if (this._exprEnv.getFirstIndex() >= 0) {
			alert(h);
			throw h
		}
	}
};
Indicator.prototype.getParameters = function() {
	var f = [];
	var d, e = this._params.length;
	for (d = 0; d < e; d++) {
		f.push(this._params[d].getValue())
	}
	return f
};
Indicator.prototype.setParameters = function(c) {
	if ((c instanceof Array) && c.length == this._params.length) {
		for (var d in this._params) {
			this._params[d].setValue(c[d])
		}
	}
};
var HLCIndicator = create_class(Indicator);
HLCIndicator.prototype.__construct = function() {
	HLCIndicator.__super.__construct.call(this);
	var b = new ParameterExpr("M1", 2, 1000, 60);
	this.addParameter(b);
	this.addOutput(new OutputExpr("HIGH", new HighExpr(), OutputStyle.None));
	this.addOutput(new OutputExpr("LOW", new LowExpr(), OutputStyle.None));
	this.addOutput(new OutputExpr("CLOSE", new CloseExpr(), OutputStyle.Line, Theme.Color.Indicator0));
	this.addOutput(new RangeOutputExpr("MA", new MaExpr(new CloseExpr(), b), OutputStyle.Line, Theme.Color.Indicator1))
};
HLCIndicator.prototype.getName = function() {
	return "CLOSE"
};
var MAIndicator = create_class(Indicator);
MAIndicator.prototype.__construct = function() {
	MAIndicator.__super.__construct.call(this);
	var i = new ParameterExpr("M1", 2, 1000, 7);
	var j = new ParameterExpr("M2", 2, 1000, 30);
	var k = new ParameterExpr("M3", 2, 1000, 0);
	var l = new ParameterExpr("M4", 2, 1000, 0);
	var g = new ParameterExpr("M5", 2, 1000, 0);
	var h = new ParameterExpr("M6", 2, 1000, 0);
	this.addParameter(i);
	this.addParameter(j);
	this.addParameter(k);
	this.addParameter(l);
	this.addParameter(g);
	this.addParameter(h);
	this.addOutput(new RangeOutputExpr("MA", new MaExpr(new CloseExpr(), i)));
	this.addOutput(new RangeOutputExpr("MA", new MaExpr(new CloseExpr(), j)));
	this.addOutput(new RangeOutputExpr("MA", new MaExpr(new CloseExpr(), k)));
	this.addOutput(new RangeOutputExpr("MA", new MaExpr(new CloseExpr(), l)));
	this.addOutput(new RangeOutputExpr("MA", new MaExpr(new CloseExpr(), g)));
	this.addOutput(new RangeOutputExpr("MA", new MaExpr(new CloseExpr(), h)))
};
MAIndicator.prototype.getName = function() {
	return "MA"
};
var EMAIndicator = create_class(Indicator);
EMAIndicator.prototype.__construct = function() {
	EMAIndicator.__super.__construct.call(this);
	var i = new ParameterExpr("M1", 2, 1000, 7);
	var j = new ParameterExpr("M2", 2, 1000, 30);
	var k = new ParameterExpr("M3", 2, 1000, 0);
	var l = new ParameterExpr("M4", 2, 1000, 0);
	var g = new ParameterExpr("M5", 2, 1000, 0);
	var h = new ParameterExpr("M6", 2, 1000, 0);
	this.addParameter(i);
	this.addParameter(j);
	this.addParameter(k);
	this.addParameter(l);
	this.addParameter(g);
	this.addParameter(h);
	this.addOutput(new RangeOutputExpr("EMA", new EmaExpr(new CloseExpr(), i)));
	this.addOutput(new RangeOutputExpr("EMA", new EmaExpr(new CloseExpr(), j)));
	this.addOutput(new RangeOutputExpr("EMA", new EmaExpr(new CloseExpr(), k)));
	this.addOutput(new RangeOutputExpr("EMA", new EmaExpr(new CloseExpr(), l)));
	this.addOutput(new RangeOutputExpr("EMA", new EmaExpr(new CloseExpr(), g)));
	this.addOutput(new RangeOutputExpr("EMA", new EmaExpr(new CloseExpr(), h)))
};
EMAIndicator.prototype.getName = function() {
	return "EMA"
};
var VOLUMEIndicator = create_class(Indicator);
VOLUMEIndicator.prototype.__construct = function() {
	VOLUMEIndicator.__super.__construct.call(this);
	var f = new ParameterExpr("M1", 2, 500, 5);
	var e = new ParameterExpr("M2", 2, 500, 10);
	this.addParameter(f);
	this.addParameter(e);
	var d = new OutputExpr("VOLUME", new VolumeExpr(), OutputStyle.VolumeStick, Theme.Color.Text4);
	this.addOutput(d);
	this.addOutput(new RangeOutputExpr("MA", new MaExpr(d, f), OutputStyle.Line, Theme.Color.Indicator0));
	this.addOutput(new RangeOutputExpr("MA", new MaExpr(d, e), OutputStyle.Line, Theme.Color.Indicator1))
};
VOLUMEIndicator.prototype.getName = function() {
	return "VOLUME"
};
var MACDIndicator = create_class(Indicator);
MACDIndicator.prototype.__construct = function() {
	MACDIndicator.__super.__construct.call(this);
	var l = new ParameterExpr("SHORT", 2, 200, 12);
	var k = new ParameterExpr("LONG", 2, 200, 26);
	var h = new ParameterExpr("MID", 2, 200, 9);
	this.addParameter(l);
	this.addParameter(k);
	this.addParameter(h);
	var g = new OutputExpr("DIF", new SubExpr(new EmaExpr(new CloseExpr(), l), new EmaExpr(new CloseExpr(), k)));
	this.addOutput(g);
	var i = new OutputExpr("DEA", new EmaExpr(g, h));
	this.addOutput(i);
	var j = new OutputExpr("MACD", new MulExpr(new SubExpr(g, i), new ConstExpr(2)), OutputStyle.MACDStick);
	this.addOutput(j)
};
MACDIndicator.prototype.getName = function() {
	return "MACD"
};
var DMIIndicator = create_class(Indicator);
DMIIndicator.prototype.__construct = function() {
	DMIIndicator.__super.__construct.call(this);
	var t = new ParameterExpr("N", 2, 90, 14);
	var o = new ParameterExpr("MM", 2, 60, 6);
	this.addParameter(t);
	this.addParameter(o);
	var m = new AssignExpr("MTR", new ExpmemaExpr(new MaxExpr(new MaxExpr(new SubExpr(new HighExpr(), new LowExpr()), new AbsExpr(new SubExpr(new HighExpr(), new RefExpr(new CloseExpr(), new ConstExpr(1))))), new AbsExpr(new SubExpr(new RefExpr(new CloseExpr(), new ConstExpr(1)), new LowExpr()))), t));
	this.addAssign(m);
	var l = new AssignExpr("HD", new SubExpr(new HighExpr(), new RefExpr(new HighExpr(), new ConstExpr(1))));
	this.addAssign(l);
	var s = new AssignExpr("LD", new SubExpr(new RefExpr(new LowExpr(), new ConstExpr(1)), new LowExpr()));
	this.addAssign(s);
	var v = new AssignExpr("DMP", new ExpmemaExpr(new IfExpr(new AndExpr(new GtExpr(l, new ConstExpr(0)), new GtExpr(l, s)), l, new ConstExpr(0)), t));
	this.addAssign(v);
	var p = new AssignExpr("DMM", new ExpmemaExpr(new IfExpr(new AndExpr(new GtExpr(s, new ConstExpr(0)), new GtExpr(s, l)), s, new ConstExpr(0)), t));
	this.addAssign(p);
	var u = new OutputExpr("PDI", new MulExpr(new DivExpr(v, m), new ConstExpr(100)));
	this.addOutput(u);
	var n = new OutputExpr("MDI", new MulExpr(new DivExpr(p, m), new ConstExpr(100)));
	this.addOutput(n);
	var q = new OutputExpr("ADX", new ExpmemaExpr(new MulExpr(new DivExpr(new AbsExpr(new SubExpr(n, u)), new AddExpr(n, u)), new ConstExpr(100)), o));
	this.addOutput(q);
	var r = new OutputExpr("ADXR", new ExpmemaExpr(q, o));
	this.addOutput(r)
};
DMIIndicator.prototype.getName = function() {
	return "DMI"
};
var DMAIndicator = create_class(Indicator);
DMAIndicator.prototype.__construct = function() {
	DMAIndicator.__super.__construct.call(this);
	var j = new ParameterExpr("N1", 2, 60, 10);
	var f = new ParameterExpr("N2", 2, 250, 50);
	var h = new ParameterExpr("M", 2, 100, 10);
	this.addParameter(j);
	this.addParameter(f);
	this.addParameter(h);
	var g = new OutputExpr("DIF", new SubExpr(new MaExpr(new CloseExpr(), j), new MaExpr(new CloseExpr(), f)));
	this.addOutput(g);
	var i = new OutputExpr("DIFMA", new MaExpr(g, h));
	this.addOutput(i)
};
DMAIndicator.prototype.getName = function() {
	return "DMA"
};
var TRIXIndicator = create_class(Indicator);
TRIXIndicator.prototype.__construct = function() {
	TRIXIndicator.__super.__construct.call(this);
	var i = new ParameterExpr("N", 2, 100, 12);
	var h = new ParameterExpr("M", 2, 100, 9);
	this.addParameter(i);
	this.addParameter(h);
	var j = new AssignExpr("MTR", new EmaExpr(new EmaExpr(new EmaExpr(new CloseExpr(), i), i), i));
	this.addAssign(j);
	var f = new OutputExpr("TRIX", new MulExpr(new DivExpr(new SubExpr(j, new RefExpr(j, new ConstExpr(1))), new RefExpr(j, new ConstExpr(1))), new ConstExpr(100)));
	this.addOutput(f);
	var g = new OutputExpr("MATRIX", new MaExpr(f, h));
	this.addOutput(g)
};
TRIXIndicator.prototype.getName = function() {
	return "TRIX"
};
var BRARIndicator = create_class(Indicator);
BRARIndicator.prototype.__construct = function() {
	BRARIndicator.__super.__construct.call(this);
	var g = new ParameterExpr("N", 2, 120, 26);
	this.addParameter(g);
	var h = new AssignExpr("REF_CLOSE_1", new RefExpr(new CloseExpr(), new ConstExpr(1)));
	this.addAssign(h);
	var e = new OutputExpr("BR", new MulExpr(new DivExpr(new SumExpr(new MaxExpr(new ConstExpr(0), new SubExpr(new HighExpr(), h)), g), new SumExpr(new MaxExpr(new ConstExpr(0), new SubExpr(h, new LowExpr())), g)), new ConstExpr(100)));
	this.addOutput(e);
	var f = new OutputExpr("AR", new MulExpr(new DivExpr(new SumExpr(new SubExpr(new HighExpr(), new OpenExpr()), g), new SumExpr(new SubExpr(new OpenExpr(), new LowExpr()), g)), new ConstExpr(100)));
	this.addOutput(f)
};
BRARIndicator.prototype.getName = function() {
	return "BRAR"
};
var VRIndicator = create_class(Indicator);
VRIndicator.prototype.__construct = function() {
	VRIndicator.__super.__construct.call(this);
	var l = new ParameterExpr("N", 2, 100, 26);
	var k = new ParameterExpr("M", 2, 100, 6);
	this.addParameter(l);
	this.addParameter(k);
	var n = new AssignExpr("REF_CLOSE_1", new RefExpr(new CloseExpr(), new ConstExpr(1)));
	this.addAssign(n);
	var o = new AssignExpr("TH", new SumExpr(new IfExpr(new GtExpr(new CloseExpr(), n), new VolumeExpr(), new ConstExpr(0)), l));
	this.addAssign(o);
	var i = new AssignExpr("TL", new SumExpr(new IfExpr(new LtExpr(new CloseExpr(), n), new VolumeExpr(), new ConstExpr(0)), l));
	this.addAssign(i);
	var m = new AssignExpr("TQ", new SumExpr(new IfExpr(new EqExpr(new CloseExpr(), n), new VolumeExpr(), new ConstExpr(0)), l));
	this.addAssign(m);
	var j = new OutputExpr("VR", new MulExpr(new DivExpr(new AddExpr(new MulExpr(o, new ConstExpr(2)), m), new AddExpr(new MulExpr(i, new ConstExpr(2)), m)), new ConstExpr(100)));
	this.addOutput(j);
	var p = new OutputExpr("MAVR", new MaExpr(j, k));
	this.addOutput(p)
};
VRIndicator.prototype.getName = function() {
	return "VR"
};
var OBVIndicator = create_class(Indicator);
OBVIndicator.prototype.__construct = function() {
	OBVIndicator.__super.__construct.call(this);
	var h = new ParameterExpr("M", 2, 100, 30);
	this.addParameter(h);
	var i = new AssignExpr("REF_CLOSE_1", new RefExpr(new CloseExpr(), new ConstExpr(1)));
	this.addAssign(i);
	var j = new AssignExpr("VA", new IfExpr(new GtExpr(new CloseExpr(), i), new VolumeExpr(), new NegExpr(new VolumeExpr())));
	this.addAssign(j);
	var g = new OutputExpr("OBV", new SumExpr(new IfExpr(new EqExpr(new CloseExpr(), i), new ConstExpr(0), j), new ConstExpr(0)));
	this.addOutput(g);
	var f = new OutputExpr("MAOBV", new MaExpr(g, h));
	this.addOutput(f)
};
OBVIndicator.prototype.getName = function() {
	return "OBV"
};
var EMVIndicator = create_class(Indicator);
EMVIndicator.prototype.__construct = function() {
	EMVIndicator.__super.__construct.call(this);
	var j = new ParameterExpr("N", 2, 90, 14);
	var i = new ParameterExpr("M", 2, 60, 9);
	this.addParameter(j);
	this.addParameter(i);
	var k = new AssignExpr("VOLUME", new DivExpr(new MaExpr(new VolumeExpr(), j), new VolumeExpr()));
	this.addAssign(k);
	var g = new AssignExpr("MID", new MulExpr(new DivExpr(new SubExpr(new AddExpr(new HighExpr(), new LowExpr()), new RefExpr(new AddExpr(new HighExpr(), new LowExpr()), new ConstExpr(1))), new AddExpr(new HighExpr(), new LowExpr())), new ConstExpr(100)));
	this.addAssign(g);
	var h = new OutputExpr("EMV", new MaExpr(new DivExpr(new MulExpr(g, new MulExpr(k, new SubExpr(new HighExpr(), new LowExpr()))), new MaExpr(new SubExpr(new HighExpr(), new LowExpr()), j)), j));
	this.addOutput(h);
	var l = new OutputExpr("MAEMV", new MaExpr(h, i));
	this.addOutput(l)
};
EMVIndicator.prototype.getName = function() {
	return "EMV"
};
var RSIIndicator = create_class(Indicator);
RSIIndicator.prototype.__construct = function() {
	RSIIndicator.__super.__construct.call(this);
	var h = new ParameterExpr("N1", 2, 120, 6);
	var f = new ParameterExpr("N2", 2, 250, 12);
	var g = new ParameterExpr("N3", 2, 500, 24);
	this.addParameter(h);
	this.addParameter(f);
	this.addParameter(g);
	var i = new AssignExpr("LC", new RefExpr(new CloseExpr(), new ConstExpr(1)));
	this.addAssign(i);
	var j = new AssignExpr("CLOSE_LC", new SubExpr(new CloseExpr(), i));
	this.addAssign(j);
	this.addOutput(new OutputExpr("RSI1", new MulExpr(new DivExpr(new SmaExpr(new MaxExpr(j, new ConstExpr(0)), h, new ConstExpr(1)), new SmaExpr(new AbsExpr(j), h, new ConstExpr(1))), new ConstExpr(100))));
	this.addOutput(new OutputExpr("RSI2", new MulExpr(new DivExpr(new SmaExpr(new MaxExpr(j, new ConstExpr(0)), f, new ConstExpr(1)), new SmaExpr(new AbsExpr(j), f, new ConstExpr(1))), new ConstExpr(100))));
	this.addOutput(new OutputExpr("RSI3", new MulExpr(new DivExpr(new SmaExpr(new MaxExpr(j, new ConstExpr(0)), g, new ConstExpr(1)), new SmaExpr(new AbsExpr(j), g, new ConstExpr(1))), new ConstExpr(100))))
};
RSIIndicator.prototype.getName = function() {
	return "RSI"
};
var WRIndicator = create_class(Indicator);
WRIndicator.prototype.__construct = function() {
	WRIndicator.__super.__construct.call(this);
	var k = new ParameterExpr("N", 2, 100, 10);
	var n = new ParameterExpr("N1", 2, 100, 6);
	this.addParameter(k);
	this.addParameter(n);
	var j = new AssignExpr("HHV", new HhvExpr(new HighExpr(), k));
	this.addAssign(j);
	var p = new AssignExpr("HHV1", new HhvExpr(new HighExpr(), n));
	this.addAssign(p);
	var i = new AssignExpr("LLV", new LlvExpr(new LowExpr(), k));
	this.addAssign(i);
	var l = new AssignExpr("LLV1", new LlvExpr(new LowExpr(), n));
	this.addAssign(l);
	var m = new OutputExpr("WR1", new MulExpr(new DivExpr(new SubExpr(j, new CloseExpr()), new SubExpr(j, i)), new ConstExpr(100)));
	this.addOutput(m);
	var o = new OutputExpr("WR2", new MulExpr(new DivExpr(new SubExpr(p, new CloseExpr()), new SubExpr(p, l)), new ConstExpr(100)));
	this.addOutput(o)
};
WRIndicator.prototype.getName = function() {
	return "WR"
};
var SARIndicator = create_class(Indicator);
SARIndicator.prototype.__construct = function() {
	SARIndicator.__super.__construct.call(this);
	var g = new ConstExpr(4);
	var e = new ConstExpr(2);
	var f = new ConstExpr(2);
	var h = new ConstExpr(20);
	this.addOutput(new OutputExpr("SAR", new SarExpr(g, e, f, h), OutputStyle.SARPoint))
};
SARIndicator.prototype.getName = function() {
	return "SAR"
};
var KDJIndicator = create_class(Indicator);
KDJIndicator.prototype.__construct = function() {
	KDJIndicator.__super.__construct.call(this);
	var m = new ParameterExpr("N", 2, 90, 9);
	var p = new ParameterExpr("M1", 2, 30, 3);
	var q = new ParameterExpr("M2", 2, 30, 3);
	this.addParameter(m);
	this.addParameter(p);
	this.addParameter(q);
	var j = new AssignExpr("HHV", new HhvExpr(new HighExpr(), m));
	this.addAssign(j);
	var n = new AssignExpr("LLV", new LlvExpr(new LowExpr(), m));
	this.addAssign(n);
	var o = new AssignExpr("RSV", new MulExpr(new DivExpr(new SubExpr(new CloseExpr(), n), new SubExpr(j, n)), new ConstExpr(100)));
	this.addAssign(o);
	var l = new OutputExpr("K", new SmaExpr(o, p, new ConstExpr(1)));
	this.addOutput(l);
	var r = new OutputExpr("D", new SmaExpr(l, q, new ConstExpr(1)));
	this.addOutput(r);
	var k = new OutputExpr("J", new SubExpr(new MulExpr(l, new ConstExpr(3)), new MulExpr(r, new ConstExpr(2))));
	this.addOutput(k)
};
KDJIndicator.prototype.getName = function() {
	return "KDJ"
};
var ROCIndicator = create_class(Indicator);
ROCIndicator.prototype.__construct = function() {
	ROCIndicator.__super.__construct.call(this);
	var i = new ParameterExpr("N", 2, 120, 12);
	var h = new ParameterExpr("M", 2, 60, 6);
	this.addParameter(i);
	this.addParameter(h);
	var g = new AssignExpr("REF_CLOSE_N", new RefExpr(new CloseExpr(), i));
	this.addAssign(g);
	var f = new OutputExpr("ROC", new MulExpr(new DivExpr(new SubExpr(new CloseExpr(), g), g), new ConstExpr(100)));
	this.addOutput(f);
	var j = new OutputExpr("MAROC", new MaExpr(f, h));
	this.addOutput(j)
};
ROCIndicator.prototype.getName = function() {
	return "ROC"
};
var MTMIndicator = create_class(Indicator);
MTMIndicator.prototype.__construct = function() {
	MTMIndicator.__super.__construct.call(this);
	var h = new ParameterExpr("N", 2, 120, 12);
	var g = new ParameterExpr("M", 2, 60, 6);
	this.addParameter(h);
	this.addParameter(g);
	var e = new OutputExpr("MTM", new SubExpr(new CloseExpr(), new RefExpr(new CloseExpr(), h)));
	this.addOutput(e);
	var f = new OutputExpr("MTMMA", new MaExpr(e, g));
	this.addOutput(f)
};
MTMIndicator.prototype.getName = function() {
	return "MTM"
};
var BOLLIndicator = create_class(Indicator);
BOLLIndicator.prototype.__construct = function() {
	BOLLIndicator.__super.__construct.call(this);
	var h = new ParameterExpr("N", 2, 120, 20);
	this.addParameter(h);
	var i = new AssignExpr("STD_CLOSE_N", new StdExpr(new CloseExpr(), h));
	this.addAssign(i);
	var g = new OutputExpr("BOLL", new MaExpr(new CloseExpr(), h));
	this.addOutput(g);
	var f = new OutputExpr("UB", new AddExpr(g, new MulExpr(new ConstExpr(2), i)));
	this.addOutput(f);
	var j = new OutputExpr("LB", new SubExpr(g, new MulExpr(new ConstExpr(2), i)));
	this.addOutput(j)
};
BOLLIndicator.prototype.getName = function() {
	return "BOLL"
};
var PSYIndicator = create_class(Indicator);
PSYIndicator.prototype.__construct = function() {
	PSYIndicator.__super.__construct.call(this);
	var h = new ParameterExpr("N", 2, 100, 12);
	var g = new ParameterExpr("M", 2, 100, 6);
	this.addParameter(h);
	this.addParameter(g);
	var e = new OutputExpr("PSY", new MulExpr(new DivExpr(new CountExpr(new GtExpr(new CloseExpr(), new RefExpr(new CloseExpr(), new ConstExpr(1))), h), h), new ConstExpr(100)));
	this.addOutput(e);
	var f = new OutputExpr("PSYMA", new MaExpr(e, g));
	this.addOutput(f)
};
PSYIndicator.prototype.getName = function() {
	return "PSY"
};
var STOCHRSIIndicator = create_class(Indicator);
STOCHRSIIndicator.prototype.__construct = function() {
	STOCHRSIIndicator.__super.__construct.call(this);
	var m = new ParameterExpr("N", 3, 100, 14);
	var k = new ParameterExpr("M", 3, 100, 14);
	var j = new ParameterExpr("P1", 2, 50, 3);
	var l = new ParameterExpr("P2", 2, 50, 3);
	this.addParameter(m);
	this.addParameter(k);
	this.addParameter(j);
	this.addParameter(l);
	var n = new AssignExpr("LC", new RefExpr(new CloseExpr(), new ConstExpr(1)));
	this.addAssign(n);
	var o = new AssignExpr("CLOSE_LC", new SubExpr(new CloseExpr(), n));
	this.addAssign(o);
	var p = new AssignExpr("RSI", new MulExpr(new DivExpr(new SmaExpr(new MaxExpr(o, new ConstExpr(0)), m, new ConstExpr(1)), new SmaExpr(new AbsExpr(o), m, new ConstExpr(1))), new ConstExpr(100)));
	this.addAssign(p);
	var i = new OutputExpr("STOCHRSI", new MulExpr(new DivExpr(new MaExpr(new SubExpr(p, new LlvExpr(p, k)), j), new MaExpr(new SubExpr(new HhvExpr(p, k), new LlvExpr(p, k)), j)), new ConstExpr(100)));
	this.addOutput(i);
	this.addOutput(new RangeOutputExpr("MA", new MaExpr(i, l)))
};
STOCHRSIIndicator.prototype.getName = function() {
	return "StochRSI"
};
var Chart = create_class();
Chart.strPeriod = {
	"zh-cn": {
		line: "(分时)",
		"1min": "(1分钟)",
		"5min": "(5分钟)",
		"15min": "(15分钟)",
		"30min": "(30分钟)",
		"1hour": "(1小时)",
		"1day": "(日线)",
		"1week": "(周线)",
		"3min": "(3分钟)",
		"2hour": "(2小时)",
		"4hour": "(4小时)",
		"6hour": "(6小时)",
		"12hour": "(12小时)",
		"3day": "(3天)"
	},
	"en-us": {
		line: "(Line)",
		"1min": "(1m)",
		"5min": "(5m)",
		"15min": "(15m)",
		"30min": "(30m)",
		"1hour": "(1h)",
		"1day": "(1d)",
		"1week": "(1w)",
		"3min": "(3m)",
		"2hour": "(2h)",
		"4hour": "(4h)",
		"6hour": "(6h)",
		"12hour": "(12h)",
		"3day": "(3d)"
	},
	"zh-tw": {
		line: "(分時)",
		"1min": "(1分钟)",
		"5min": "(5分钟)",
		"15min": "(15分钟)",
		"30min": "(30分钟)",
		"1hour": "(1小時)",
		"1day": "(日线)",
		"1week": "(周线)",
		"3min": "(3分钟)",
		"2hour": "(2小時)",
		"4hour": "(4小時)",
		"6hour": "(6小時)",
		"12hour": "(12小時)",
		"3day": "(3天)"
	}
};
Chart.prototype.__construct = function() {
	this._data = null;
	this._charStyle = "CandleStick";
	this._depthData = {
		array: null,
		asks_count: 0,
		bids_count: 0,
		asks_si: 0,
		asks_ei: 0,
		bids_si: 0,
		bids_ei: 0
	};
	this._time = GLOBAL_VAR.time_type;
	this._market_from = GLOBAL_VAR.market_from;
	this._usd_cny_rate = 6.1934;
	this._money_type = "USD";
	this._contract_unit = "BTC";
	this.strIsLine = false;
	this.strCurrentMarket = 20150403001;
	this.strCurrentMarketType = 1
};
Chart.prototype.setTitle = function() {
	var c = ChartManager.getInstance().getLanguage();
	var d = GLOBAL_VAR.market_from_name;
	d += " ";
	d += this.strIsLine ? Chart.strPeriod[c]["line"] : Chart.strPeriod[c][this._time];
	d += (this._contract_unit + "/" + this._money_type).toUpperCase();
	ChartManager.getInstance().setTitle("frame0.k0", d);
	kline.title = d;
	kline.setTitle()
};
Chart.prototype.setCurrentList = function() {};
Chart.prototype.setMarketFrom = function(b) {
	this._market_from = b;
	this.updateDataAndDisplay()
};
Chart.prototype.updateDataAndDisplay = function() {
	GLOBAL_VAR.market_from = this._market_from;
	GLOBAL_VAR.time_type = this._time;
	this.setTitle();
	ChartManager.getInstance().setCurrentDataSource("frame0.k0", "BTC123." + this._market_from + "." + this._time);
	ChartManager.getInstance().setNormalMode();
	var b = GLOBAL_VAR.chartMgr.getDataSource("frame0.k0").getLastDate();
	if (b == -1) {
		GLOBAL_VAR.requestParam = setHttpRequestParam(GLOBAL_VAR.market_from, GLOBAL_VAR.time_type, GLOBAL_VAR.limit, null);
		RequestData(true)
	} else {
		GLOBAL_VAR.requestParam = setHttpRequestParam(GLOBAL_VAR.market_from, GLOBAL_VAR.time_type, null, b.toString());
		RequestData()
	}
	ChartManager.getInstance().redraw("All", false)
};
Chart.prototype.setCurrentContractUnit = function(b) {
	this._contract_unit = b;
	this.updateDataAndDisplay()
};
Chart.prototype.setCurrentMoneyType = function(b) {
	this._money_type = b;
	this.updateDataAndDisplay()
};
Chart.prototype.setCurrentPeriod = function(b) {
	this._time = GLOBAL_VAR.periodMap[b];
	this.updateDataAndDisplay()
};
Chart.prototype.updateDataSource = function(b) {
	this._data = b;
	ChartManager.getInstance().updateData("frame0.k0", this._data)
};
Chart.prototype.updateDepth = function(g) {
	if (g == null) {
		this._depthData.array = [];
		ChartManager.getInstance().redraw("All", false);
		return
	}
	if (!g.asks || !g.bids || g.asks == "" || g.bids == "") {
		return
	}
	var e = this._depthData;
	e.array = [];
	for (var f = 0; f < g.asks.length; f++) {
		var h = {};
		h.rate = g.asks[f][0];
		h.amount = g.asks[f][1];
		e.array.push(h)
	}
	for (var f = 0; f < g.bids.length; f++) {
		var h = {};
		h.rate = g.bids[f][0];
		h.amount = g.bids[f][1];
		e.array.push(h)
	}
	e.asks_count = g.asks.length;
	e.bids_count = g.bids.length;
	e.asks_si = e.asks_count - 1;
	e.asks_ei = 0;
	e.bids_si = e.asks_count;
	e.bids_ei = e.asks_count + e.bids_count - 1;
	for (var f = e.asks_si; f >= e.asks_ei; f--) {
		if (f == e.asks_si) {
			e.array[f].amounts = e.array[f].amount
		} else {
			e.array[f].amounts = e.array[f + 1].amounts + e.array[f].amount
		}
	}
	for (var f = e.bids_si; f <= e.bids_ei; f++) {
		if (f == e.bids_si) {
			e.array[f].amounts = e.array[f].amount
		} else {
			e.array[f].amounts = e.array[f - 1].amounts + e.array[f].amount
		}
	}
	ChartManager.getInstance().redraw("All", false)
};
Chart.prototype.setMainIndicator = function(b) {
	this._mainIndicator = b;
	if (b == "NONE") {
		ChartManager.getInstance().removeMainIndicator("frame0.k0")
	} else {
		ChartManager.getInstance().setMainIndicator("frame0.k0", b)
	}
	ChartManager.getInstance().redraw("All", true)
};
Chart.prototype.setIndicator = function(d, e) {
	if (e == "NONE") {
		var d = 2;
		if (Template.displayVolume == false) {
			d = 1
		}
		var f = ChartManager.getInstance().getIndicatorAreaName("frame0.k0", d);
		if (f != "") {
			ChartManager.getInstance().removeIndicator(f)
		}
	} else {
		var d = 2;
		if (Template.displayVolume == false) {
			d = 1
		}
		var f = ChartManager.getInstance().getIndicatorAreaName("frame0.k0", d);
		if (f == "") {
			Template.createIndicatorChartComps("frame0.k0", e)
		} else {
			ChartManager.getInstance().setIndicator(f, e)
		}
	}
	ChartManager.getInstance().redraw("All", true)
};
Chart.prototype.addIndicator = function(b) {
	ChartManager.getInstance().addIndicator(b);
	ChartManager.getInstance().redraw("All", true)
};
Chart.prototype.removeIndicator = function(d) {
	var c = ChartManager.getInstance().getIndicatorAreaName(2);
	ChartManager.getInstance().removeIndicator(c);
	ChartManager.getInstance().redraw("All", true)
};
var CName = create_class();
CName.prototype.__construct = function(f) {
	this._names = [];
	this._comps = [];
	if (f instanceof CName) {
		this._names = f._names;
		this._comps = f._comps
	} else {
		var g = f.split(".");
		var h = g.length - 1;
		if (h > 0) {
			this._comps = g;
			this._names.push(g[0]);
			for (var e = 1; e <= h; e++) {
				this._names.push(this._names[e - 1] + "." + g[e])
			}
		} else {
			this._comps.push(f);
			this._names.push(f)
		}
	}
};
CName.prototype.getCompAt = function(b) {
	if (b >= 0 && b < this._comps.length) {
		return this._comps[b]
	}
	return ""
};
CName.prototype.getName = function(b) {
	if (b < 0) {
		if (this._names.length > 0) {
			return this._names[this._names.length - 1]
		}
	} else {
		if (b < this._names.length) {
			return this._names[b]
		}
	}
	return ""
};
var NamedObject = create_class();
NamedObject.prototype.__construct = function(b) {
	this._name = b;
	this._nameObj = new CName(b)
};
NamedObject.prototype.getFrameName = function() {
	return this._nameObj.getName(0)
};
NamedObject.prototype.getDataSourceName = function() {
	return this._nameObj.getName(1)
};
NamedObject.prototype.getAreaName = function() {
	return this._nameObj.getName(2)
};
NamedObject.prototype.getName = function() {
	return this._nameObj.getName(-1)
};
NamedObject.prototype.getNameObject = function() {
	return this._nameObj
};
var ChartArea = create_class(NamedObject);
ChartArea.prototype.__construct = function(b) {
	ChartArea.__super.__construct.call(this, b);
	this._left = 0;
	this._top = 0;
	this._right = 0;
	this._bottom = 0;
	this._changed = false;
	this._highlighted = false;
	this._pressed = false;
	this._selected = false;
	this.Measuring = new MEvent()
};
ChartArea.DockStyle = {
	Left: 0,
	Top: 1,
	Right: 2,
	Bottom: 3,
	Fill: 4
};
ChartArea.prototype.getDockStyle = function() {
	return this._dockStyle
};
ChartArea.prototype.setDockStyle = function(b) {
	this._dockStyle = b
};
ChartArea.prototype.getLeft = function() {
	return this._left
};
ChartArea.prototype.getTop = function() {
	return this._top
};
ChartArea.prototype.setTop = function(b) {
	if (this._top != b) {
		this._top = b;
		this._changed = true
	}
};
ChartArea.prototype.getRight = function() {
	return this._right
};
ChartArea.prototype.getBottom = function() {
	return this._bottom
};
ChartArea.prototype.setBottom = function(b) {
	if (this._bottom != b) {
		this._bottom = b;
		this._changed = true
	}
};
ChartArea.prototype.getCenter = function() {
	return (this._left + this._right) >> 1
};
ChartArea.prototype.getMiddle = function() {
	return (this._top + this._bottom) >> 1
};
ChartArea.prototype.getWidth = function() {
	return this._right - this._left
};
ChartArea.prototype.getHeight = function() {
	return this._bottom - this._top
};
ChartArea.prototype.getRect = function() {
	return {
		X: this._left,
		Y: this._top,
		Width: this._right - this._left,
		Height: this._bottom - this._top
	}
};
ChartArea.prototype.contains = function(d, c) {
	if (d >= this._left && d < this._right) {
		if (c >= this._top && c < this._bottom) {
			return [this]
		}
	}
	return null
};
ChartArea.prototype.getMeasuredWidth = function() {
	return this._measuredWidth
};
ChartArea.prototype.getMeasuredHeight = function() {
	return this._measuredHeight
};
ChartArea.prototype.setMeasuredDimension = function(c, d) {
	this._measuredWidth = c;
	this._measuredHeight = d
};
ChartArea.prototype.measure = function(d, f, e) {
	this._measuredWidth = 0;
	this._measuredHeight = 0;
	this.Measuring.raise(this, {
		Width: f,
		Height: e
	});
	if (this._measuredWidth == 0 && this._measuredHeight == 0) {
		this.setMeasuredDimension(f, e)
	}
};
ChartArea.prototype.layout = function(h, i, j, g, f) {
	h <<= 0;
	if (this._left != h) {
		this._left = h;
		this._changed = true
	}
	i <<= 0;
	if (this._top != i) {
		this._top = i;
		this._changed = true
	}
	j <<= 0;
	if (this._right != j) {
		this._right = j;
		this._changed = true
	}
	g <<= 0;
	if (this._bottom != g) {
		this._bottom = g;
		this._changed = true
	}
	if (f) {
		this._changed = true
	}
};
ChartArea.prototype.isChanged = function() {
	return this._changed
};
ChartArea.prototype.setChanged = function(b) {
	this._changed = b
};
ChartArea.prototype.isHighlighted = function() {
	return this._highlighted
};
ChartArea.prototype.getHighlightedArea = function() {
	return this._highlighted ? this : null
};
ChartArea.prototype.highlight = function(b) {
	this._highlighted = (this == b);
	return this._highlighted ? this : null
};
ChartArea.prototype.isPressed = function() {
	return this._pressed
};
ChartArea.prototype.setPressed = function(b) {
	this._pressed = b
};
ChartArea.prototype.isSelected = function() {
	return this._selected
};
ChartArea.prototype.getSelectedArea = function() {
	return this._selected ? this : null
};
ChartArea.prototype.select = function(b) {
	this._selected = (this == b);
	return this._selected ? this : null
};
ChartArea.prototype.onMouseMove = function(d, c) {
	return null
};
ChartArea.prototype.onMouseLeave = function(d, c) {};
ChartArea.prototype.onMouseDown = function(d, c) {
	return null
};
ChartArea.prototype.onMouseUp = function(d, c) {
	return null
};
var MainArea = create_class(ChartArea);
MainArea.prototype.__construct = function(b) {
	MainArea.__super.__construct.call(this, b);
	this._dragStarted = false;
	this._oldX = 0;
	this._oldY = 0;
	this._passMoveEventToToolManager = true
};
MainArea.prototype.onMouseMove = function(e, f) {
	var d = ChartManager.getInstance();
	if (d._capturingMouseArea == this) {
		if (this._dragStarted == false) {
			if (Math.abs(this._oldX - e) > 1 || Math.abs(this._oldY - f) > 1) {
				this._dragStarted = true
			}
		}
	}
	if (this._dragStarted) {
		d.hideCursor();
		if (d.onToolMouseDrag(this.getFrameName(), e, f)) {
			return this
		}
		d.getTimeline(this.getDataSourceName()).move(e - this._oldX);
		return this
	}
	if (this._passMoveEventToToolManager && d.onToolMouseMove(this.getFrameName(), e, f)) {
		d.hideCursor();
		return this
	}
	switch (d._drawingTool) {
	case ChartManager.DrawingTool.Cursor:
		d.showCursor();
		break;
	case ChartManager.DrawingTool.CrossCursor:
		if (d.showCrossCursor(this, e, f)) {
			d.hideCursor()
		} else {
			d.showCursor()
		}
		break;
	default:
		d.hideCursor();
		break
	}
	return this
};
MainArea.prototype.onMouseLeave = function(d, c) {
	this._dragStarted = false;
	this._passMoveEventToToolManager = true
};
MainArea.prototype.onMouseDown = function(e, f) {
	var d = ChartManager.getInstance();
	d.getTimeline(this.getDataSourceName()).startMove();
	this._oldX = e;
	this._oldY = f;
	this._dragStarted = false;
	if (d.onToolMouseDown(this.getFrameName(), e, f)) {
		this._passMoveEventToToolManager = false
	}
	return this
};
MainArea.prototype.onMouseUp = function(f, g) {
	var h = ChartManager.getInstance();
	var e = null;
	if (this._dragStarted) {
		this._dragStarted = false;
		e = this
	}
	if (h.onToolMouseUp(this.getFrameName(), f, g)) {
		e = this
	}
	this._passMoveEventToToolManager = true;
	return e
};
var IndicatorArea = create_class(ChartArea);
IndicatorArea.prototype.__construct = function(b) {
	IndicatorArea.__super.__construct.call(this, b);
	this._dragStarted = false;
	this._oldX = 0;
	this._oldY = 0
};
IndicatorArea.prototype.onMouseMove = function(e, f) {
	var d = ChartManager.getInstance();
	if (d._capturingMouseArea == this) {
		if (this._dragStarted == false) {
			if (this._oldX != e || this._oldY != f) {
				this._dragStarted = true
			}
		}
	}
	if (this._dragStarted) {
		d.hideCursor();
		d.getTimeline(this.getDataSourceName()).move(e - this._oldX);
		return this
	}
	switch (d._drawingTool) {
	case ChartManager.DrawingTool.CrossCursor:
		if (d.showCrossCursor(this, e, f)) {
			d.hideCursor()
		} else {
			d.showCursor()
		}
		break;
	default:
		d.showCursor();
		break
	}
	return this
};
IndicatorArea.prototype.onMouseLeave = function(d, c) {
	this._dragStarted = false
};
IndicatorArea.prototype.onMouseDown = function(e, f) {
	var d = ChartManager.getInstance();
	d.getTimeline(this.getDataSourceName()).startMove();
	this._oldX = e;
	this._oldY = f;
	this._dragStarted = false;
	return this
};
IndicatorArea.prototype.onMouseUp = function(d, c) {
	if (this._dragStarted) {
		this._dragStarted = false;
		return this
	}
	return null
};
var MainRangeArea = create_class(ChartArea);
MainRangeArea.prototype.__construct = function(b) {
	MainRangeArea.__super.__construct.call(this, b)
};
MainRangeArea.prototype.onMouseMove = function(d, c) {
	ChartManager.getInstance().showCursor();
	return this
};
var IndicatorRangeArea = create_class(ChartArea);
IndicatorRangeArea.prototype.__construct = function(b) {
	IndicatorRangeArea.__super.__construct.call(this, b)
};
IndicatorRangeArea.prototype.onMouseMove = function(d, c) {
	ChartManager.getInstance().showCursor();
	return this
};
var TimelineArea = create_class(ChartArea);
TimelineArea.prototype.__construct = function(b) {
	TimelineArea.__super.__construct.call(this, b)
};
TimelineArea.prototype.onMouseMove = function(d, c) {
	ChartManager.getInstance().showCursor();
	return this
};
var ChartAreaGroup = create_class(ChartArea);
ChartAreaGroup.prototype.__construct = function(b) {
	ChartAreaGroup.__super.__construct.call(this, b);
	this._areas = [];
	this._highlightedArea = null;
	this._selectedArea = null
};
ChartAreaGroup.prototype.contains = function(a, h) {
	var k;
	var l, i, j = this._areas.length;
	for (i = 0; i < j; i++) {
		l = this._areas[i];
		k = l.contains(a, h);
		if (k != null) {
			k.push(this);
			return k
		}
	}
	return ChartAreaGroup.__super.contains(a, h)
};
ChartAreaGroup.prototype.getAreaCount = function() {
	return this._areas.length
};
ChartAreaGroup.prototype.getAreaAt = function(b) {
	if (b < 0 || b >= this._areas.length) {
		return null
	}
	return this._areas[b]
};
ChartAreaGroup.prototype.addArea = function(b) {
	this._areas.push(b)
};
ChartAreaGroup.prototype.removeArea = function(f) {
	var d, e = this._areas.length;
	for (d = 0; d < e; d++) {
		if (f == this._areas[d]) {
			this._areas.splice(d);
			this.setChanged(true);
			break
		}
	}
};
ChartAreaGroup.prototype.getGridColor = function() {
	return this._gridColor
};
ChartAreaGroup.prototype.setGridColor = function(b) {
	this._gridColor = b
};
ChartAreaGroup.prototype.getHighlightedArea = function() {
	if (this._highlightedArea != null) {
		return this._highlightedArea.getHighlightedArea()
	}
	return null
};
ChartAreaGroup.prototype.highlight = function(h) {
	this._highlightedArea = null;
	var g, e, f = this._areas.length;
	for (e = 0; e < f; e++) {
		g = this._areas[e].highlight(h);
		if (g != null) {
			this._highlightedArea = g;
			return this
		}
	}
	return null
};
ChartAreaGroup.prototype.getSelectedArea = function() {
	if (this._selectedArea != null) {
		return this._selectedArea.getSelectedArea()
	}
	return null
};
ChartAreaGroup.prototype.select = function(h) {
	this._selectedArea = null;
	var g, e, f = this._areas.length;
	for (e = 0; e < f; e++) {
		g = this._areas[e].select(h);
		if (g != null) {
			this._selectedArea = g;
			return this
		}
	}
	return null
};
ChartAreaGroup.prototype.onMouseLeave = function(f, g) {
	var h, e = this._areas.length;
	for (h = 0; h < e; h++) {
		this._areas[h].onMouseLeave(f, g)
	}
};
ChartAreaGroup.prototype.onMouseUp = function(a, g) {
	var j, h, i = this._areas.length;
	for (h = 0; h < i; h++) {
		j = this._areas[h].onMouseUp(a, g);
		if (j != null) {
			return j
		}
	}
	return null
};
var TableLayout = create_class(ChartAreaGroup);
TableLayout.prototype.__construct = function(b) {
	TableLayout.__super.__construct.call(this, b);
	this._nextRowId = 0;
	this._focusedRowIndex = -1
};
TableLayout.prototype.getNextRowId = function() {
	return this._nextRowId++
};
TableLayout.prototype.measure = function(an, R, ad) {
	this.setMeasuredDimension(R, ad);
	var ah, ab = 0,
		M = 0;
	var L, af;
	var ac = [];
	var N, P = this._areas.length;
	for (N = 0; N < P; N += 2) {
		ah = this._areas[N].getHeight();
		if (ah == 0) {
			if (N == 0) {
				af = (P + 1) >> 1;
				var T = (af * 2) + 5;
				var ak = ((ad / T) * 2) << 0;
				L = ad;
				for (N = af - 1; N > 0; N--) {
					ac.unshift(ak);
					L -= ak
				}
				ac.unshift(L);
				break
			} else {
				if (N == 2) {
					ah = ab / 3
				} else {
					ah = ab
				}
			}
		}
		M += ah;
		ab = ah;
		ac.push(ah)
	}
	if (M > 0) {
		var n = ad / M;
		af = (P + 1) >> 1;
		L = ad;
		for (N = af - 1; N > 0; N--) {
			ac[N] *= n;
			L -= ac[N]
		}
		ac[0] = L
	}
	var O = 8;
	var h = 64;
	var am = Math.min(240, R >> 1);
	var ai = h;
	var K = ChartManager.getInstance();
	var aj = K.getTimeline(this.getDataSourceName());
	if (aj.getFirstIndex() >= 0) {
		var ae = [];
		for (ai = h; ai < am; ai += O) {
			ae.push(aj.calcFirstIndex(aj.calcColumnCount(R - ai)))
		}
		var i = aj.getLastIndex();
		var Z = [".main", ".secondary"];
		var V = new Array(ae.length);
		var X, ag;
		for (X = 0, ag = 0, ai = h; X < this._areas.length && ag < ae.length; X += 2) {
			var aa = this._areas[X];
			var Q = K.getPlotter(aa.getName() + "Range.main");
			for (var Y in Z) {
				var W = K.getDataProvider(aa.getName() + Z[Y]);
				if (W == undefined) {
					continue
				}
				W.calcRange(ae, i, V, null);
				while (ag < ae.length) {
					var S = Q.getRequiredWidth(an, V[ag].min);
					var U = Q.getRequiredWidth(an, V[ag].max);
					if (Math.max(S, U) < ai) {
						break
					}
					ag++;
					ai += O
				}
			}
		}
	}
	for (N = 1; N < this._areas.length; N += 2) {
		this._areas[N].measure(an, ai, ac[N >> 1])
	}
	var al = R - ai;
	for (N = 0; N < this._areas.length; N += 2) {
		this._areas[N].measure(an, al, ac[N >> 1])
	}
};
TableLayout.prototype.layout = function(q, n, i, v, t) {
	TableLayout.__super.layout.call(this, q, n, i, v, t);
	if (this._areas.length < 1) {
		return
	}
	var s;
	var u = q + this._areas[0].getMeasuredWidth();
	var b = n,
		o;
	if (!t) {
		t = this.isChanged()
	}
	var p, r = this._areas.length;
	for (p = 0; p < r; p++) {
		s = this._areas[p];
		o = b + s.getMeasuredHeight();
		s.layout(q, b, u, o, t);
		p++;
		s = this._areas[p];
		s.layout(u, b, this.getRight(), o, t);
		b = o
	}
	this.setChanged(false)
};
TableLayout.prototype.drawGrid = function(j) {
	if (this._areas.length < 1) {
		return
	}
	var i = ChartManager.getInstance();
	var h = i.getTheme(this.getFrameName());
	j.fillStyle = h.getColor(Theme.Color.Grid1);
	j.fillRect(this._areas[0].getRight(), this.getTop(), 1, this.getHeight());
	var f, g = this._areas.length - 2;
	for (f = 0; f < g; f += 2) {
		j.fillRect(this.getLeft(), this._areas[f].getBottom(), this.getWidth(), 1)
	}
	if (!i.getCaptureMouseWheelDirectly()) {
		for (f = 0, g += 2; f < g; f += 2) {
			if (this._areas[f].isSelected()) {
				j.strokeStyle = h.getColor(Theme.Color.Indicator1);
				j.strokeRect(this.getLeft() + 0.5, this.getTop() + 0.5, this.getWidth() - 1, this.getHeight() - 1);
				break
			}
		}
	}
};
TableLayout.prototype.highlight = function(h) {
	this._highlightedArea = null;
	var g, e, f = this._areas.length;
	for (e = 0; e < f; e++) {
		g = this._areas[e];
		if (g == h) {
			e &= ~1;
			g = this._areas[e];
			g.highlight(g);
			this._highlightedArea = g;
			e++;
			g = this._areas[e];
			g.highlight(null);
			g.highlight(g)
		} else {
			g.highlight(null)
		}
	}
	return this._highlightedArea != null ? this : null
};
TableLayout.prototype.select = function(h) {
	this._selectedArea = null;
	var g, e, f = this._areas.length;
	for (e = 0; e < f; e++) {
		g = this._areas[e];
		if (g == h) {
			e &= ~1;
			g = this._areas[e];
			g.select(g);
			this._selectedArea = g;
			e++;
			g = this._areas[e];
			g.select(g)
		} else {
			g.select(null)
		}
	}
	return this._selectedArea != null ? this : null
};
TableLayout.prototype.onMouseMove = function(d, i) {
	if (this._focusedRowIndex >= 0) {
		var b = this._areas[this._focusedRowIndex];
		var r = this._areas[this._focusedRowIndex + 2];
		var q = i - this._oldY;
		if (q == 0) {
			return this
		}
		var o = this._oldUpperBottom + q;
		var p = this._oldLowerTop + q;
		if (o - b.getTop() >= 60 && r.getBottom() - p >= 60) {
			b.setBottom(o);
			r.setTop(p)
		}
		return this
	}
	var s, t = this._areas.length - 2;
	for (s = 0; s < t; s += 2) {
		var n = this._areas[s].getBottom();
		if (i >= n - 4 && i < n + 4) {
			ChartManager.getInstance().showCursor("n-resize");
			return this
		}
	}
	return null
};
TableLayout.prototype.onMouseLeave = function(d, c) {
	this._focusedRowIndex = -1
};
TableLayout.prototype.onMouseDown = function(j, g) {
	var h, i = this._areas.length - 2;
	for (h = 0; h < i; h += 2) {
		var b = this._areas[h].getBottom();
		if (g >= b - 4 && g < b + 4) {
			this._focusedRowIndex = h;
			this._oldY = g;
			this._oldUpperBottom = b;
			this._oldLowerTop = this._areas[h + 2].getTop();
			return this
		}
	}
	return null
};
TableLayout.prototype.onMouseUp = function(f, h) {
	if (this._focusedRowIndex >= 0) {
		this._focusedRowIndex = -1;
		var i, j = this._areas.length;
		var g = [];
		for (i = 0; i < j; i += 2) {
			g.push(this._areas[i].getHeight())
		}
		ChartSettings.get().charts.areaHeight = g;
		ChartSettings.save()
	}
	return this
};
var DockableLayout = create_class(ChartAreaGroup);
DockableLayout.prototype.__construct = function(b) {
	DockableLayout.__super.__construct.call(this, b)
};
DockableLayout.prototype.measure = function(j, i, g) {
	DockableLayout.__super.measure.call(this, j, i, g);
	i = this.getMeasuredWidth();
	g = this.getMeasuredHeight();
	for (var f in this._areas) {
		var h = this._areas[f];
		h.measure(j, i, g);
		switch (h.getDockStyle()) {
		case ChartArea.DockStyle.left:
		case ChartArea.DockStyle.Right:
			i -= h.getMeasuredWidth();
			break;
		case ChartArea.DockStyle.Top:
		case ChartArea.DockStyle.Bottom:
			g -= h.getMeasuredHeight();
			break;
		case ChartArea.DockStyle.Fill:
			i = 0;
			g = 0;
			break
		}
	}
};
DockableLayout.prototype.layout = function(o, l, h, r, q) {
	DockableLayout.__super.layout.call(this, o, l, h, r, q);
	o = this.getLeft();
	l = this.getTop();
	h = this.getRight();
	r = this.getBottom();
	var i, m;
	if (!q) {
		q = this.isChanged()
	}
	for (var n in this._areas) {
		var p = this._areas[n];
		switch (p.getDockStyle()) {
		case ChartArea.DockStyle.left:
			i = p.getMeasuredWidth();
			p.layout(o, l, o + i, r, q);
			o += i;
			break;
		case ChartArea.DockStyle.Top:
			m = p.getMeasuredHeight();
			p.layout(o, l, h, l + m, q);
			l += m;
			break;
		case ChartArea.DockStyle.Right:
			i = p.getMeasuredWidth();
			p.layout(h - i, l, h, r, q);
			h -= i;
			break;
		case ChartArea.DockStyle.Bottom:
			m = p.getMeasuredHeight();
			p.layout(o, r - m, h, r, q);
			r -= m;
			break;
		case ChartArea.DockStyle.Fill:
			p.layout(o, l, h, r, q);
			o = h;
			l = r;
			break
		}
	}
	this.setChanged(false)
};
DockableLayout.prototype.drawGrid = function(p) {
	var i = ChartManager.getInstance();
	var n = i.getTheme(this.getFrameName());
	var o = this.getLeft();
	var l = this.getTop();
	var k = this.getRight();
	var r = this.getBottom();
	p.fillStyle = n.getColor(this._gridColor);
	for (var m in this._areas) {
		var q = this._areas[m];
		switch (q.getDockStyle()) {
		case ChartArea.DockStyle.Left:
			p.fillRect(q.getRight(), l, 1, r - l);
			o += q.getWidth();
			break;
		case ChartArea.DockStyle.Top:
			p.fillRect(o, q.getBottom(), k - o, 1);
			l += q.getHeight();
			break;
		case ChartArea.DockStyle.Right:
			p.fillRect(q.getLeft(), l, 1, r - l);
			k -= q.getWidth();
			break;
		case ChartArea.DockStyle.Bottom:
			p.fillRect(o, q.getTop(), k - o, 1);
			r -= q.getHeight();
			break
		}
	}
};
var ChartManager = create_class();
ChartManager.DrawingTool = {
	Cursor: 0,
	CrossCursor: 1,
	DrawLines: 2,
	DrawFibRetrace: 3,
	DrawFibFans: 4,
	SegLine: 5,
	StraightLine: 6,
	ArrowLine: 7,
	RayLine: 8,
	HoriStraightLine: 9,
	HoriRayLine: 10,
	HoriSegLine: 11,
	VertiStraightLine: 12,
	PriceLine: 13,
	BiParallelLine: 14,
	BiParallelRayLine: 15,
	TriParallelLine: 16,
	BandLine: 17
};
ChartManager._instance = null;
ChartManager.getInstance = function() {
	if (ChartManager._instance == null) {
		ChartManager._instance = new ChartManager()
	}
	return ChartManager._instance
};
ChartManager.prototype.__construct = function() {
	this._dataSources = {};
	this._dataSourceCache = {};
	this._dataProviders = {};
	this._frames = {};
	this._areas = {};
	this._timelines = {};
	this._ranges = {};
	this._plotters = {};
	this._themes = {};
	this._titles = {};
	this._frameMousePos = {};
	this._dsChartStyle = {};
	this._dragStarted = false;
	this._oldX = 0;
	this._fakeIndicators = {};
	this._captureMouseWheelDirectly = true;
	this._chart = {};
	this._chart.defaultFrame = new Chart();
	this._drawingTool = ChartManager.DrawingTool.CrossCursor;
	this._beforeDrawingTool = this._drawingTool;
	this._language = "zh-cn";
	this._mainCanvas = null;
	this._overlayCanvas = null;
	this._mainContext = null;
	this._overlayContext = null
};
ChartManager.prototype.redraw = function(d, c) {
	if (d == undefined || c) {
		d = "All"
	}
	if (d == "All" || d == "MainCanvas") {
		if (c) {
			this.getFrame("frame0").setChanged(true)
		}
		this.layout(this._mainContext, "frame0", 0, 0, this._mainCanvas.width, this._mainCanvas.height);
		this.drawMain("frame0", this._mainContext)
	}
	if (d == "All" || d == "OverlayCanvas") {
		this._overlayContext.clearRect(0, 0, this._overlayCanvas.width, this._overlayCanvas.height);
		this.drawOverlay("frame0", this._overlayContext)
	}
};
ChartManager.prototype.bindCanvas = function(c, d) {
	if (c == "main") {
		this._mainCanvas = d;
		this._mainContext = d.getContext("2d")
	} else {
		if (c == "overlay") {
			this._overlayCanvas = d;
			this._overlayContext = d.getContext("2d");
			if (this._captureMouseWheelDirectly) {
				$(this._overlayCanvas).bind("mousewheel", mouseWheel)
			}
		}
	}
};
ChartManager.prototype.getCaptureMouseWheelDirectly = function() {
	return this._captureMouseWheelDirectly
};
ChartManager.prototype.setCaptureMouseWheelDirectly = function(b) {
	this._captureMouseWheelDirectly = b;
	if (b) {
		$(this._overlayCanvas).bind("mousewheel", mouseWheel)
	} else {
		$(this._overlayCanvas).unbind("mousewheel")
	}
};
ChartManager.prototype.getChart = function(b) {
	return this._chart.defaultFrame
};
ChartManager.prototype.init = function() {
	delete this._ranges["frame0.k0.indic1"];
	delete this._ranges["frame0.k0.indic1Range"];
	delete this._areas["frame0.k0.indic1"];
	delete this._areas["frame0.k0.indic1Range"];
	DefaultTemplate.loadTemplate("frame0.k0", "BTC123");
	this.redraw("All", true)
};
ChartManager.prototype.setCurrentDrawingTool = function(b) {
	this._drawingTool = ChartManager.DrawingTool[b];
	this.setRunningMode(this._drawingTool)
};
ChartManager.prototype.getLanguage = function() {
	return this._language
};
ChartManager.prototype.setLanguage = function(b) {
	this._language = b
};
ChartManager.prototype.setThemeName = function(e, d) {
	if (d == undefined) {
		d = "Dark"
	}
	var f;
	switch (d) {
	case "Light":
		f = new LightTheme();
		break;
	default:
		d = "Dark";
		f = new DarkTheme();
		break
	}
	this._themeName = d;
	this.setTheme(e, f);
	this.getFrame(e).setChanged(true)
};
ChartManager.prototype.getChartStyle = function(c) {
	var d = this._dsChartStyle[c];
	if (d == undefined) {
		return "CandleStick"
	}
	return d
};
ChartManager.prototype.setChartStyle = function(l, n) {
	if (this._dsChartStyle[l] == n) {
		return
	}
	var j = l + ".main";
	var h = j + ".main";
	var i = j + ".main";
	var k, m;
	switch (n) {
	case "CandleStick":
	case "CandleStickHLC":
	case "OHLC":
		k = this.getDataProvider(h);
		if (k == undefined || !is_instance(k, MainDataProvider)) {
			k = new MainDataProvider(h);
			this.setDataProvider(h, k);
			k.updateData()
		}
		this.setMainIndicator(l, ChartSettings.get().charts.mIndic);
		switch (n) {
		case "CandleStick":
			m = new CandlestickPlotter(i);
			break;
		case "CandleStickHLC":
			m = new CandlestickHLCPlotter(i);
			break;
		case "OHLC":
			m = new OHLCPlotter(i);
			break
		}
		this.setPlotter(i, m);
		m = new MinMaxPlotter(j + ".decoration");
		this.setPlotter(m.getName(), m);
		break;
	case "Line":
		k = new IndicatorDataProvider(h);
		this.setDataProvider(k.getName(), k);
		k.setIndicator(new HLCIndicator());
		this.removeMainIndicator(l);
		m = new IndicatorPlotter(i);
		this.setPlotter(i, m);
		this.removePlotter(j + ".decoration");
		break
	}
	this.getArea(m.getAreaName()).setChanged(true);
	this._dsChartStyle[l] = n
};
ChartManager.prototype.setNormalMode = function() {
	this._drawingTool = this._beforeDrawingTool;
	$(".chart_dropdown_data").removeClass("chart_dropdown-hover");
	$("#chart_toolpanel .chart_toolpanel_button").removeClass("selected");
	$("#chart_CrossCursor").parent().addClass("selected");
	if (this._drawingTool == ChartManager.DrawingTool.Cursor) {
		this.showCursor();
		$("#mode a").removeClass("selected");
		$("#chart_toolpanel .chart_toolpanel_button").removeClass("selected");
		$("#chart_Cursor").parent().addClass("selected")
	} else {
		this.hideCursor()
	}
};
ChartManager.prototype.setRunningMode = function(d) {
	var f = this.getDataSource("frame0.k0");
	var e = f.getCurrentToolObject();
	if (e != null && e.state != CToolObject.state.AfterDraw) {
		f.delToolObject()
	}
	if (f.getToolObjectCount() > 10) {
		this.setNormalMode();
		return
	}
	this._drawingTool = d;
	if (d == ChartManager.DrawingTool.Cursor) {
		this.showCursor()
	} else {}
	switch (d) {
	case ChartManager.DrawingTool.Cursor:
		this._beforeDrawingTool = d;
		break;
	case ChartManager.DrawingTool.ArrowLine:
		f.addToolObject(new CArrowLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.BandLine:
		f.addToolObject(new CBandLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.BiParallelLine:
		f.addToolObject(new CBiParallelLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.BiParallelRayLine:
		f.addToolObject(new CBiParallelRayLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.CrossCursor:
		this._beforeDrawingTool = d;
		break;
	case ChartManager.DrawingTool.DrawFibFans:
		f.addToolObject(new CFibFansObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.DrawFibRetrace:
		f.addToolObject(new CFibRetraceObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.DrawLines:
		f.addToolObject(new CStraightLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.HoriRayLine:
		f.addToolObject(new CHoriRayLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.HoriSegLine:
		f.addToolObject(new CHoriSegLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.HoriStraightLine:
		f.addToolObject(new CHoriStraightLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.PriceLine:
		f.addToolObject(new CPriceLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.RayLine:
		f.addToolObject(new CRayLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.SegLine:
		f.addToolObject(new CSegLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.StraightLine:
		f.addToolObject(new CStraightLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.TriParallelLine:
		f.addToolObject(new CTriParallelLineObject("frame0.k0"));
		break;
	case ChartManager.DrawingTool.VertiStraightLine:
		f.addToolObject(new CVertiStraightLineObject("frame0.k0"));
		break
	}
};
ChartManager.prototype.getTitle = function(b) {
	return this._titles[b]
};
ChartManager.prototype.setTitle = function(c, d) {
	this._titles[c] = d
};
ChartManager.prototype.setCurrentDataSource = function(g, f) {
	var e = this.getCachedDataSource(f);
	if (e != null) {
		this.setDataSource(g, e, true)
	} else {
		var h = this.getDataSource(g);
		if (h != null) {
			if (is_instance(h, MainDataSource)) {
				e = new MainDataSource(f)
			} else {
				if (is_instance(h, CLiveOrderDataSource)) {
					e = new CLiveOrderDataSource(f)
				} else {
					if (is_instance(h, CLiveTradeDataSource)) {
						e = new CLiveTradeDataSource(f)
					}
				}
			}
			this.setDataSource(g, e, true);
			this.setCachedDataSource(f, e)
		}
	}
};
ChartManager.prototype.getDataSource = function(b) {
	return this._dataSources[b]
};
ChartManager.prototype.setDataSource = function(e, d, f) {
	this._dataSources[e] = d;
	if (f) {
		this.updateData(e, null)
	}
};
ChartManager.prototype.getCachedDataSource = function(b) {
	return this._dataSourceCache[b]
};
ChartManager.prototype.setCachedDataSource = function(d, c) {
	this._dataSourceCache[d] = c
};
ChartManager.prototype.getDataProvider = function(b) {
	return this._dataProviders[b]
};
ChartManager.prototype.setDataProvider = function(d, c) {
	this._dataProviders[d] = c
};
ChartManager.prototype.removeDataProvider = function(b) {
	delete this._dataProviders[b]
};
ChartManager.prototype.getFrame = function(b) {
	return this._frames[b]
};
ChartManager.prototype.setFrame = function(d, c) {
	this._frames[d] = c
};
ChartManager.prototype.removeFrame = function(b) {
	delete this._frames[b]
};
ChartManager.prototype.getArea = function(b) {
	return this._areas[b]
};
ChartManager.prototype.setArea = function(d, c) {
	this._areas[d] = c
};
ChartManager.prototype.removeArea = function(b) {
	delete this._areas[b]
};
ChartManager.prototype.getTimeline = function(b) {
	return this._timelines[b]
};
ChartManager.prototype.setTimeline = function(d, c) {
	this._timelines[d] = c
};
ChartManager.prototype.removeTimeline = function(b) {
	delete this._timelines[b]
};
ChartManager.prototype.getRange = function(b) {
	return this._ranges[b]
};
ChartManager.prototype.setRange = function(c, d) {
	this._ranges[c] = d
};
ChartManager.prototype.removeRange = function(b) {
	delete this._ranges[b]
};
ChartManager.prototype.getPlotter = function(b) {
	return this._plotters[b]
};
ChartManager.prototype.setPlotter = function(d, c) {
	this._plotters[d] = c
};
ChartManager.prototype.removePlotter = function(b) {
	delete this._plotters[b]
};
ChartManager.prototype.getTheme = function(b) {
	return this._themes[b]
};
ChartManager.prototype.setTheme = function(d, c) {
	this._themes[d] = c
};
ChartManager.prototype.getFrameMousePos = function(c, d) {
	if (this._frameMousePos[c] != undefined) {
		d.x = this._frameMousePos[c].x;
		d.y = this._frameMousePos[c].y
	} else {
		d.x = -1;
		d.y = -1
	}
};
ChartManager.prototype.setFrameMousePos = function(d, f, e) {
	this._frameMousePos[d] = {
		x: f,
		y: e
	}
};
ChartManager.prototype.drawArea = function(n, m, k) {
	var j = m.getNameObject().getCompAt(2);
	if (j == "timeline") {
		if (m.getHeight() < 20) {
			return
		}
	} else {
		if (m.getHeight() < 30) {
			return
		}
	}
	if (m.getWidth() < 30) {
		return
	}
	j = m.getName();
	var l;
	var h, i = k.length;
	for (h = 0; h < i; h++) {
		l = this._plotters[j + k[h]];
		if (l != undefined) {
			l.Draw(n)
		}
	}
};
ChartManager.prototype.drawAreaMain = function(f, e) {
	var h = this._dataSources[e.getDataSourceName()];
	var g;
	if (h.getDataCount() < 1) {
		g = [".background"]
	} else {
		g = [".background", ".grid", ".main", ".secondary"]
	}
	this.drawArea(f, e, g);
	e.setChanged(false)
};
ChartManager.prototype.drawAreaOverlay = function(f, e) {
	var h = this._dataSources[e.getDataSourceName()];
	var g;
	if (h.getDataCount() < 1) {
		g = [".selection"]
	} else {
		g = [".decoration", ".selection", ".info", ".tool"]
	}
	this.drawArea(f, e, g)
};
ChartManager.prototype.drawMain = function(g, j) {
	drawn = false;
	if (!drawn) {
		for (var i in this._areas) {
			if (this._areas[i].getFrameName() == g && !is_instance(this._areas[i], ChartAreaGroup)) {
				this.drawAreaMain(j, this._areas[i])
			}
		}
	}
	var h;
	for (var e in this._timelines) {
		h = this._timelines[e];
		if (h.getFrameName() == g) {
			h.setUpdated(false)
		}
	}
	for (var e in this._ranges) {
		h = this._ranges[e];
		if (h.getFrameName() == g) {
			h.setUpdated(false)
		}
	}
	for (var e in this._areas) {
		h = this._areas[e];
		if (h.getFrameName() == g) {
			h.setChanged(false)
		}
	}
};
ChartManager.prototype.drawOverlay = function(f, e) {
	for (var g in this._areas) {
		var h = this._areas[g];
		if (is_instance(h, ChartAreaGroup)) {
			if (h.getFrameName() == f) {
				h.drawGrid(e)
			}
		}
	}
	for (var g in this._areas) {
		var h = this._areas[g];
		if (is_instance(h, ChartAreaGroup) == false) {
			if (h.getFrameName() == f) {
				this.drawAreaOverlay(e, h)
			}
		}
	}
};
ChartManager.prototype.updateData = function(t, n) {
	var q = this.getDataSource(t);
	if (q == undefined) {
		return
	}
	if (n != null) {
		if (!q.update(n)) {
			return false
		}
		if (q.getUpdateMode() == DataSource.UpdateMode.DoNothing) {
			return true
		}
	} else {
		q.setUpdateMode(DataSource.UpdateMode.Refresh)
	}
	var l = this.getTimeline(t);
	if (l != undefined) {
		l.update()
	}
	if (q.getDataCount() < 1) {
		return true
	}
	var i = [".main", ".secondary"];
	var s, m;
	for (var r in this._areas) {
		s = this._areas[r];
		if (is_instance(s, ChartAreaGroup)) {
			continue
		}
		if (s.getDataSourceName() != t) {
			continue
		}
		m = s.getName();
		for (var o = 0; o < i.length; o++) {
			var p = this.getDataProvider(m + i[o]);
			if (p != undefined) {
				p.updateData()
			}
		}
	}
	return true
};
ChartManager.prototype.updateRange = function(t) {
	var q = this.getDataSource(t);
	if (q.getDataCount() < 1) {
		return
	}
	var i = [".main", ".secondary"];
	var s, m;
	for (var r in this._areas) {
		s = this._areas[r];
		if (is_instance(s, ChartAreaGroup)) {
			continue
		}
		if (s.getDataSourceName() != t) {
			continue
		}
		m = s.getName();
		for (var o = 0; o < i.length; o++) {
			var p = this.getDataProvider(m + i[o]);
			if (p != undefined) {
				p.updateRange()
			}
		}
		var l = this.getTimeline(t);
		if (l != undefined && l.getMaxItemCount() > 0) {
			var n = this.getRange(m);
			if (n != undefined) {
				n.update()
			}
		}
	}
};
ChartManager.prototype.layout = function(q, m, n, k, e, r) {
	var p = this.getFrame(m);
	p.measure(q, e - n, r - k);
	p.layout(n, k, e, r);
	for (var o in this._timelines) {
		var l = this._timelines[o];
		if (l.getFrameName() == m) {
			l.onLayout()
		}
	}
	for (var o in this._dataSources) {
		if (o.substring(0, m.length) == m) {
			this.updateRange(o)
		}
	}
};
ChartManager.prototype.SelectRange = function(g, i) {
	var l;
	for (var h in this._ranges) {
		var j = this._ranges[h].getAreaName();
		var k = g.getName();
		if (j == k) {
			this._ranges[h].selectAt(i)
		} else {
			this._ranges[h].unselect()
		}
	}
};
ChartManager.prototype.scale = function(e) {
	if (this._highlightedFrame == null) {
		return
	}
	var f = this._highlightedFrame.getHighlightedArea();
	if (this.getRange(f.getName()) != undefined) {
		var g = f.getDataSourceName();
		var h = this.getTimeline(g);
		if (h != null) {
			h.scale(e);
			this.updateRange(g)
		}
	}
};
ChartManager.prototype.showCursor = function(b) {
	if (b === undefined) {
		b = "default"
	}
	this._mainCanvas.style.cursor = b;
	this._overlayCanvas.style.cursor = b
};
ChartManager.prototype.hideCursor = function() {
	this._mainCanvas.style.cursor = "none";
	this._overlayCanvas.style.cursor = "none"
};
ChartManager.prototype.showCrossCursor = function(e, f, g) {
	var h = this.getRange(e.getName());
	if (h != undefined) {
		h.selectAt(g);
		h = this.getTimeline(e.getDataSourceName());
		if (h != undefined) {
			if (h.selectAt(f)) {
				return true
			}
		}
	}
	return false
};
ChartManager.prototype.hideCrossCursor = function(e) {
	if (e != null) {
		for (var f in this._timelines) {
			var d = this._timelines[f];
			if (d != e) {
				d.unselect()
			}
		}
	} else {
		for (var f in this._timelines) {
			this._timelines[f].unselect()
		}
	}
	for (var f in this._ranges) {
		this._ranges[f].unselect()
	}
};
ChartManager.prototype.clearHighlight = function() {
	if (this._highlightedFrame != null) {
		this._highlightedFrame.highlight(null);
		this._highlightedFrame = null
	}
};
ChartManager.prototype.onToolMouseMove = function(g, h, i) {
	var l = false;
	g += ".";
	for (var j in this._dataSources) {
		if (j.indexOf(g) == 0) {
			var k = this._dataSources[j];
			if (is_instance(k, MainDataSource)) {
				if (k.toolManager.acceptMouseMoveEvent(h, i)) {
					l = true
				}
			}
		}
	}
	return l
};
ChartManager.prototype.onToolMouseDown = function(g, h, i) {
	var l = false;
	g += ".";
	for (var j in this._dataSources) {
		if (j.indexOf(g) == 0) {
			var k = this._dataSources[j];
			if (is_instance(k, MainDataSource)) {
				if (k.toolManager.acceptMouseDownEvent(h, i)) {
					l = true
				}
			}
		}
	}
	return l
};
ChartManager.prototype.onToolMouseUp = function(g, h, i) {
	var l = false;
	g += ".";
	for (var j in this._dataSources) {
		if (j.indexOf(g) == 0) {
			var k = this._dataSources[j];
			if (is_instance(k, MainDataSource)) {
				if (k.toolManager.acceptMouseUpEvent(h, i)) {
					l = true
				}
			}
		}
	}
	return l
};
ChartManager.prototype.onToolMouseDrag = function(g, h, i) {
	var l = false;
	g += ".";
	for (var j in this._dataSources) {
		if (j.indexOf(g) == 0) {
			var k = this._dataSources[j];
			if (is_instance(k, MainDataSource)) {
				if (k.toolManager.acceptMouseDownMoveEvent(h, i)) {
					l = true
				}
			}
		}
	}
	return l
};
ChartManager.prototype.onMouseMove = function(n, a, l, m) {
	var q = this.getFrame(n);
	if (q === undefined) {
		return
	}
	this.setFrameMousePos(n, a, l);
	this.hideCrossCursor();
	if (this._highlightedFrame != q) {
		this.clearHighlight()
	}
	if (this._capturingMouseArea != null) {
		this._capturingMouseArea.onMouseMove(a, l);
		return
	}
	var r = q.contains(a, l);
	if (r == null) {
		return
	}
	var i, o, p = r.length;
	for (o = p - 1; o >= 0; o--) {
		i = r[o];
		i = i.onMouseMove(a, l);
		if (i != null) {
			if (!is_instance(i, ChartAreaGroup)) {
				q.highlight(i);
				this._highlightedFrame = q
			}
			return
		}
	}
};
ChartManager.prototype.onMouseLeave = function(j, g, h, f) {
	var i = this.getFrame(j);
	if (i == undefined) {
		return
	}
	this.setFrameMousePos(j, g, h);
	this.hideCrossCursor();
	this.clearHighlight();
	if (this._capturingMouseArea != null) {
		this._capturingMouseArea.onMouseLeave(g, h);
		this._capturingMouseArea = null
	}
	this._dragStarted = false
};
ChartManager.prototype.onMouseDown = function(o, a, i) {
	var k = this.getFrame(o);
	if (k == undefined) {
		return
	}
	var n = k.contains(a, i);
	if (n == null) {
		return
	}
	var p, l, m = n.length;
	for (l = m - 1; l >= 0; l--) {
		p = n[l];
		p = p.onMouseDown(a, i);
		if (p != null) {
			this._capturingMouseArea = p;
			return
		}
	}
};
ChartManager.prototype.onMouseUp = function(e, f, g) {
	var h = this.getFrame(e);
	if (h == undefined) {
		return
	}
	if (this._capturingMouseArea) {
		if (this._capturingMouseArea.onMouseUp(f, g) == null && this._dragStarted == false) {
			if (this._selectedFrame != null && this._selectedFrame != h) {
				this._selectedFrame.select(null)
			}
			if (this._capturingMouseArea.isSelected()) {
				if (!this._captureMouseWheelDirectly) {
					$(this._overlayCanvas).unbind("mousewheel")
				}
				h.select(null);
				this._selectedFrame = null
			} else {
				if (this._selectedFrame != h) {
					if (!this._captureMouseWheelDirectly) {
						$(this._overlayCanvas).bind("mousewheel", mouseWheel)
					}
				}
				h.select(this._capturingMouseArea);
				this._selectedFrame = h
			}
		}
		this._capturingMouseArea = null;
		this._dragStarted = false
	}
};
ChartManager.prototype.deleteToolObject = function() {
	var d = this.getDataSource("frame0.k0");
	var f = d.getSelectToolObjcet();
	if (f != null) {
		d.delSelectToolObject()
	}
	var e = d.getCurrentToolObject();
	if (e != null && e.getState() != CToolObject.state.AfterDraw) {
		d.delToolObject()
	}
	this.setNormalMode()
};
ChartManager.prototype.unloadTemplate = function(e) {
	var d = this.getFrame(e);
	if (d == undefined) {
		return
	}
	for (var f in this._dataSources) {
		if (f.match(e + ".")) {
			delete this._dataSources[f]
		}
	}
	for (var f in this._dataProviders) {
		if (this._dataProviders[f].getFrameName() == e) {
			delete this._dataProviders[f]
		}
	}
	delete this._frames[e];
	for (var f in this._areas) {
		if (this._areas[f].getFrameName() == e) {
			delete this._areas[f]
		}
	}
	for (var f in this._timelines) {
		if (this._timelines[f].getFrameName() == e) {
			delete this._timelines[f]
		}
	}
	for (var f in this._ranges) {
		if (this._ranges[f].getFrameName() == e) {
			delete this._ranges[f]
		}
	}
	for (var f in this._plotters) {
		if (this._plotters[f].getFrameName() == e) {
			delete this._plotters[f]
		}
	}
	delete this._themes[e];
	delete this._frameMousePos[e]
};
ChartManager.prototype.createIndicatorAndRange = function(h, g, j) {
	var i, f;
	switch (g) {
	case "MA":
		i = new MAIndicator();
		f = new PositiveRange(h);
		break;
	case "EMA":
		i = new EMAIndicator();
		f = new PositiveRange(h);
		break;
	case "VOLUME":
		i = new VOLUMEIndicator();
		f = new ZeroBasedPositiveRange(h);
		break;
	case "MACD":
		i = new MACDIndicator();
		f = new ZeroCenteredRange(h);
		break;
	case "DMI":
		i = new DMIIndicator();
		f = new PercentageRange(h);
		break;
	case "DMA":
		i = new DMAIndicator();
		f = new Range(h);
		break;
	case "TRIX":
		i = new TRIXIndicator();
		f = new Range(h);
		break;
	case "BRAR":
		i = new BRARIndicator();
		f = new Range(h);
		break;
	case "VR":
		i = new VRIndicator();
		f = new Range(h);
		break;
	case "OBV":
		i = new OBVIndicator();
		f = new Range(h);
		break;
	case "EMV":
		i = new EMVIndicator();
		f = new Range(h);
		break;
	case "RSI":
		i = new RSIIndicator();
		f = new PercentageRange(h);
		break;
	case "WR":
		i = new WRIndicator();
		f = new PercentageRange(h);
		break;
	case "SAR":
		i = new SARIndicator();
		f = new PositiveRange(h);
		break;
	case "KDJ":
		i = new KDJIndicator();
		f = new PercentageRange(h);
		break;
	case "ROC":
		i = new ROCIndicator();
		f = new Range(h);
		break;
	case "MTM":
		i = new MTMIndicator();
		f = new Range(h);
		break;
	case "BOLL":
		i = new BOLLIndicator();
		f = new Range(h);
		break;
	case "PSY":
		i = new PSYIndicator();
		f = new Range(h);
		break;
	case "StochRSI":
		i = new STOCHRSIIndicator();
		f = new PercentageRange(h);
		break;
	default:
		return null
	}
	if (!j) {
		i.setParameters(ChartSettings.get().indics[g])
	}
	return {
		indic: i,
		range: f
	}
};
ChartManager.prototype.setMainIndicator = function(n, j) {
	var k = n + ".main";
	var l = this.getDataProvider(k + ".main");
	if (l == undefined || !is_instance(l, MainDataProvider)) {
		return false
	}
	var m;
	switch (j) {
	case "MA":
		m = new MAIndicator();
		break;
	case "EMA":
		m = new EMAIndicator();
		break;
	case "BOLL":
		m = new BOLLIndicator();
		break;
	case "SAR":
		m = new SARIndicator();
		break;
	default:
		return false
	}
	m.setParameters(ChartSettings.get().indics[j]);
	var p = k + ".secondary";
	var i = this.getDataProvider(p);
	if (i == undefined) {
		i = new IndicatorDataProvider(p);
		this.setDataProvider(i.getName(), i)
	}
	i.setIndicator(m);
	var o = this.getPlotter(p);
	if (o == undefined) {
		o = new IndicatorPlotter(p);
		this.setPlotter(o.getName(), o)
	}
	this.getArea(k).setChanged(true);
	return true
};
ChartManager.prototype.setIndicator = function(k, j) {
	var o = this.getArea(k);
	if (o == undefined || o.getNameObject().getCompAt(2) == "main") {
		return false
	}
	var l = this.getDataProvider(k + ".secondary");
	if (l == undefined || !is_instance(l, IndicatorDataProvider)) {
		return false
	}
	var p = this.createIndicatorAndRange(k, j);
	if (p == null) {
		return false
	}
	var m = p.indic;
	var i = p.range;
	this.removeDataProvider(k + ".main");
	this.removePlotter(k + ".main");
	this.removeRange(k);
	this.removePlotter(k + "Range.decoration");
	l.setIndicator(m);
	this.setRange(k, i);
	i.setPaddingTop(20);
	i.setPaddingBottom(4);
	i.setMinInterval(20);
	if (is_instance(m, VOLUMEIndicator)) {
		var n = new LastVolumePlotter(k + "Range.decoration");
		this.setPlotter(n.getName(), n)
	} else {
		if (is_instance(m, BOLLIndicator) || is_instance(m, SARIndicator)) {
			var l = new MainDataProvider(k + ".main");
			this.setDataProvider(l.getName(), l);
			l.updateData();
			var n = new OHLCPlotter(k + ".main");
			this.setPlotter(n.getName(), n)
		}
	}
	return true
};
ChartManager.prototype.removeMainIndicator = function(h) {
	var g = h + ".main";
	var e = g + ".secondary";
	var f = this.getDataProvider(e);
	if (f == undefined || !is_instance(f, IndicatorDataProvider)) {
		return
	}
	this.removeDataProvider(e);
	this.removePlotter(e);
	this.getArea(g).setChanged(true)
};
ChartManager.prototype.removeIndicator = function(j) {
	var h = this.getArea(j);
	if (h == undefined || h.getNameObject().getCompAt(2) == "main") {
		return
	}
	var k = this.getDataProvider(j + ".secondary");
	if (k == undefined || !is_instance(k, IndicatorDataProvider)) {
		return
	}
	var n = j + "Range";
	var i = this.getArea(n);
	if (i == undefined) {
		return
	}
	var l = this.getArea(h.getDataSourceName() + ".charts");
	if (l == undefined) {
		return
	}
	l.removeArea(h);
	this.removeArea(j);
	l.removeArea(i);
	this.removeArea(n);
	for (var m in this._dataProviders) {
		if (this._dataProviders[m].getAreaName() == j) {
			this.removeDataProvider(m)
		}
	}
	for (var m in this._ranges) {
		if (this._ranges[m].getAreaName() == j) {
			this.removeRange(m)
		}
	}
	for (var m in this._plotters) {
		if (this._plotters[m].getAreaName() == j) {
			this.removePlotter(m)
		}
	}
	for (var m in this._plotters) {
		if (this._plotters[m].getAreaName() == n) {
			this.removePlotter(m)
		}
	}
};
ChartManager.prototype.getIndicatorParameters = function(h) {
	var i = this._fakeIndicators[h];
	if (i == undefined) {
		var g = this.createIndicatorAndRange("", h);
		if (g == null) {
			return null
		}
		this._fakeIndicators[h] = i = g.indic
	}
	var j = [];
	var k, l = i.getParameterCount();
	for (k = 0; k < l; k++) {
		j.push(i.getParameterAt(k))
	}
	return j
};
ChartManager.prototype.setIndicatorParameters = function(h, l) {
	var i, j;
	for (i in this._dataProviders) {
		var k = this._dataProviders[i];
		if (is_instance(k, IndicatorDataProvider) == false) {
			continue
		}
		j = k.getIndicator();
		if (j.getName() == h) {
			j.setParameters(l);
			k.refresh();
			this.getArea(k.getAreaName()).setChanged(true)
		}
	}
	j = this._fakeIndicators[h];
	if (j == undefined) {
		var g = this.createIndicatorAndRange("", h, true);
		if (g == null) {
			return
		}
		this._fakeIndicators[h] = j = g.indic
	}
	j.setParameters(l)
};
ChartManager.prototype.getIndicatorAreaName = function(h, f) {
	var g = this.getArea(h + ".charts");
	var e = g.getAreaCount() >> 1;
	if (f < 0 || f >= e) {
		return ""
	}
	return g.getAreaAt(f << 1).getName()
};
var Timeline = create_class(NamedObject);
Timeline._ItemWidth = [1, 3, 3, 5, 5, 7, 9, 11, 13, 15, 17, 19, 21, 23, 25, 27, 29];
Timeline._SpaceWidth = [1, 1, 2, 2, 3, 3, 3, 3, 3, 3, 5, 5, 5, 5, 7, 7, 7];
Timeline.PADDING_LEFT = 4;
Timeline.PADDING_RIGHT = 8;
Timeline.prototype.__construct = function(b) {
	Timeline.__super.__construct.call(this, b);
	this._updated = false;
	this._innerLeft = 0;
	this._innerWidth = 0;
	this._firstColumnLeft = 0;
	this._scale = 3;
	this._lastScale = -1;
	this._maxItemCount = 0;
	this._maxIndex = 0;
	this._firstIndex = -1;
	this._selectedIndex = -1;
	this._savedFirstIndex = -1
};
Timeline.prototype.isLatestShown = function() {
	return this.getLastIndex() == this._maxIndex
};
Timeline.prototype.isUpdated = function() {
	return this._updated
};
Timeline.prototype.setUpdated = function(b) {
	this._updated = b
};
Timeline.prototype.getItemWidth = function() {
	return Timeline._ItemWidth[this._scale]
};
Timeline.prototype.getSpaceWidth = function() {
	return Timeline._SpaceWidth[this._scale]
};
Timeline.prototype.getColumnWidth = function() {
	return this.getSpaceWidth() + this.getItemWidth()
};
Timeline.prototype.getInnerWidth = function() {
	return this._innerWidth
};
Timeline.prototype.getItemLeftOffset = function() {
	return this.getSpaceWidth()
};
Timeline.prototype.getItemCenterOffset = function() {
	return this.getSpaceWidth() + (this.getItemWidth() >> 1)
};
Timeline.prototype.getFirstColumnLeft = function() {
	return this._firstColumnLeft
};
Timeline.prototype.getMaxItemCount = function() {
	return this._maxItemCount
};
Timeline.prototype.getFirstIndex = function() {
	return this._firstIndex
};
Timeline.prototype.getLastIndex = function() {
	return Math.min(this._firstIndex + this._maxItemCount, this._maxIndex)
};
Timeline.prototype.getSelectedIndex = function() {
	return this._selectedIndex
};
Timeline.prototype.getMaxIndex = function() {
	return this._maxIndex
};
Timeline.prototype.calcColumnCount = function(b) {
	return Math.floor(b / this.getColumnWidth()) << 0
};
Timeline.prototype.calcFirstColumnLeft = function(b) {
	return this._innerLeft + this._innerWidth - (this.getColumnWidth() * b)
};
Timeline.prototype.calcFirstIndexAlignRight = function(f, e, d) {
	return Math.max(0, f + Math.max(e, 1) - Math.max(d, 1))
};
Timeline.prototype.calcFirstIndex = function(b) {
	return this.validateFirstIndex(this.calcFirstIndexAlignRight(this._firstIndex, this._maxItemCount, b), b)
};
Timeline.prototype.updateMaxItemCount = function() {
	var e = this.calcColumnCount(this._innerWidth);
	var f;
	if (this._maxItemCount < 1) {
		f = this.calcFirstIndex(e)
	} else {
		if (this._lastScale == this._scale) {
			f = this.validateFirstIndex(this._firstIndex - (e - this._maxItemCount))
		} else {
			var d = (this._selectedIndex >= 0) ? this._selectedIndex : this.getLastIndex() - 1;
			f = this.validateFirstIndex(d - Math.round((d - this._firstIndex) * e / this._maxItemCount))
		}
	}
	this._lastScale = this._scale;
	if (this._firstIndex != f) {
		if (this._selectedIndex == this._firstIndex) {
			this._selectedIndex = f
		}
		this._firstIndex = f;
		this._updated = true
	}
	if (this._maxItemCount != e) {
		this._maxItemCount = e;
		this._updated = true
	}
	this._firstColumnLeft = this.calcFirstColumnLeft(e)
};
Timeline.prototype.validateFirstIndex = function(e, f) {
	if (this._maxIndex < 1) {
		return -1
	}
	if (e < 0) {
		return 0
	}
	var d = Math.max(0, this._maxIndex - 1);
	if (e > d) {
		return d
	}
	return e
};
Timeline.prototype.validateSelectedIndex = function() {
	if (this._selectedIndex < this._firstIndex) {
		this._selectedIndex = -1
	} else {
		if (this._selectedIndex >= this.getLastIndex()) {
			this._selectedIndex = -1
		}
	}
};
Timeline.prototype.onLayout = function() {
	var f = ChartManager.getInstance();
	var d = f.getArea(this.getDataSourceName() + ".main");
	if (d != null) {
		this._innerLeft = d.getLeft() + Timeline.PADDING_LEFT;
		var e = Math.max(0, d.getWidth() - (Timeline.PADDING_LEFT + Timeline.PADDING_RIGHT));
		if (this._innerWidth != e) {
			this._innerWidth = e;
			this.updateMaxItemCount()
		}
	}
};
Timeline.prototype.toIndex = function(b) {
	return this._firstIndex + this.calcColumnCount(b - this._firstColumnLeft)
};
Timeline.prototype.toColumnLeft = function(b) {
	return this._firstColumnLeft + (this.getColumnWidth() * (b - this._firstIndex))
};
Timeline.prototype.toItemLeft = function(b) {
	return this.toColumnLeft(b) + this.getItemLeftOffset()
};
Timeline.prototype.toItemCenter = function(b) {
	return this.toColumnLeft(b) + this.getItemCenterOffset()
};
Timeline.prototype.selectAt = function(b) {
	this._selectedIndex = this.toIndex(b);
	this.validateSelectedIndex();
	return (this._selectedIndex >= 0)
};
Timeline.prototype.unselect = function() {
	this._selectedIndex = -1
};
Timeline.prototype.update = function() {
	var j = ChartManager.getInstance();
	var f = j.getDataSource(this.getDataSourceName());
	var i = this._maxIndex;
	this._maxIndex = f.getDataCount();
	switch (f.getUpdateMode()) {
	case DataSource.UpdateMode.Refresh:
		if (this._maxIndex < 1) {
			this._firstIndex = -1
		} else {
			this._firstIndex = Math.max(this._maxIndex - this._maxItemCount, 0)
		}
		this._selectedIndex = -1;
		this._updated = true;
		break;
	case DataSource.UpdateMode.Append:
		var h = this.getLastIndex();
		var g = f.getErasedCount();
		if (h < i) {
			if (g > 0) {
				this._firstIndex = Math.max(this._firstIndex - g, 0);
				if (this._selectedIndex >= 0) {
					this._selectedIndex -= g;
					this.validateSelectedIndex()
				}
				this._updated = true
			}
		} else {
			if (h == i) {
				this._firstIndex += (this._maxIndex - i);
				if (this._selectedIndex >= 0) {
					this._selectedIndex -= g;
					this.validateSelectedIndex()
				}
				this._updated = true
			}
		}
		break
	}
};
Timeline.prototype.move = function(b) {
	if (this.isLatestShown()) {
		ChartManager.getInstance().getArea(this.getDataSourceName() + ".mainRange").setChanged(true)
	}
	this._firstIndex = this.validateFirstIndex(this._savedFirstIndex - this.calcColumnCount(b), this._maxItemCount);
	this._updated = true;
	if (this._selectedIndex >= 0) {
		this.validateSelectedIndex()
	}
};
Timeline.prototype.startMove = function() {
	this._savedFirstIndex = this._firstIndex
};
Timeline.prototype.scale = function(b) {
	this._scale += b;
	if (this._scale < 0) {
		this._scale = 0
	} else {
		if (this._scale >= Timeline._ItemWidth.length) {
			this._scale = Timeline._ItemWidth.length - 1
		}
	}
	this.updateMaxItemCount();
	if (this._selectedIndex >= 0) {
		this.validateSelectedIndex()
	}
};
var Range = create_class(NamedObject);
Range.prototype.__construct = function(b) {
	Range.__super.__construct.call(this, b);
	this._updated = true;
	this._minValue = Number.MAX_VALUE;
	this._maxValue = -Number.MAX_VALUE;
	this._outerMinValue = Number.MAX_VALUE;
	this._outerMaxValue = -Number.MAX_VALUE;
	this._ratio = 0;
	this._top = 0;
	this._bottom = 0;
	this._paddingTop = 0;
	this._paddingBottom = 0;
	this._minInterval = 36;
	this._selectedPosition = -1;
	this._selectedValue = -Number.MAX_VALUE;
	this._gradations = []
};
Range.prototype.isUpdated = function() {
	return this._updated
};
Range.prototype.setUpdated = function(b) {
	this._updated = b
};
Range.prototype.getMinValue = function() {
	return this._minValue
};
Range.prototype.getMaxValue = function() {
	return this._maxValue
};
Range.prototype.getRange = function() {
	return this._maxValue - this._minValue
};
Range.prototype.getOuterMinValue = function() {
	return this._outerMinValue
};
Range.prototype.getOuterMaxValue = function() {
	return this._outerMaxValue
};
Range.prototype.getOuterRange = function() {
	return this._outerMaxValue - this._outerMinValue
};
Range.prototype.getHeight = function() {
	return Math.max(0, this._bottom - this._top)
};
Range.prototype.getGradations = function() {
	return this._gradations
};
Range.prototype.getMinInterval = function() {
	return this._minInterval
};
Range.prototype.setMinInterval = function(b) {
	this._minInterval = b
};
Range.prototype.getSelectedPosition = function() {
	if (this._selectedPosition >= 0) {
		return this._selectedPosition
	}
	if (this._selectedValue > -Number.MAX_VALUE) {
		return this.toY(this._selectedValue)
	}
	return -1
};
Range.prototype.getSelectedValue = function() {
	if (this._selectedValue > -Number.MAX_VALUE) {
		return this._selectedValue
	}
	var c = ChartManager.getInstance();
	var d = c.getArea(this.getAreaName());
	if (d == null) {
		return -Number.MAX_VALUE
	}
	if (this._selectedPosition < d.getTop() + 12 || this._selectedPosition >= d.getBottom() - 4) {
		return -Number.MAX_VALUE
	}
	return this.toValue(this._selectedPosition)
};
Range.prototype.setPaddingTop = function(b) {
	this._paddingTop = b
};
Range.prototype.setPaddingBottom = function(b) {
	this._paddingBottom = b
};
Range.prototype.toValue = function(b) {
	return this._maxValue - (b - this._top) / this._ratio
};
Range.prototype.toY = function(b) {
	if (this._ratio > 0) {
		return this._top + Math.floor((this._maxValue - b) * this._ratio + 0.5)
	}
	return this._top
};
Range.prototype.toHeight = function(b) {
	return Math.floor(b * this._ratio + 1.5)
};
Range.prototype.update = function() {
	var n = Number.MAX_VALUE;
	var i = -Number.MAX_VALUE;
	var l = ChartManager.getInstance();
	var j, k = [".main", ".secondary"];
	for (var h = 0; h < k.length; h++) {
		j = l.getDataProvider(this.getName() + k[h]);
		if (j != null) {
			n = Math.min(n, j.getMinValue());
			i = Math.max(i, j.getMaxValue())
		}
	}
	var m = {
		min: n,
		max: i
	};
	this.preSetRange(m);
	this.setRange(m.min, m.max)
};
Range.prototype.select = function(b) {
	this._selectedValue = b;
	this._selectedPosition = -1
};
Range.prototype.selectAt = function(b) {
	this._selectedPosition = b;
	this._selectedValue = -Number.MAX_VALUE
};
Range.prototype.unselect = function() {
	this._selectedPosition = -1;
	this._selectedValue = -Number.MAX_VALUE
};
Range.prototype.preSetRange = function(b) {
	if (b.min == b.max) {
		b.min = -1;
		b.max = 1
	}
};
Range.prototype.setRange = function(k, i) {
	var l = ChartManager.getInstance();
	var g = l.getArea(this.getAreaName());
	if (this._minValue == k && this._maxValue == i && !g.isChanged()) {
		return
	}
	this._updated = true;
	this._minValue = k;
	this._maxValue = i;
	this._gradations = [];
	var j = g.getTop() + this._paddingTop;
	var h = g.getBottom() - (this._paddingBottom + 1);
	if (j >= h) {
		this._minValue = this._maxValue;
		return
	}
	this._top = j;
	this._bottom = h;
	if (this._maxValue > this._minValue) {
		this._ratio = (h - j) / (this._maxValue - this._minValue)
	} else {
		this._ratio = 1
	}
	this._outerMinValue = this.toValue(g.getBottom());
	this._outerMaxValue = this.toValue(g.getTop());
	this.updateGradations()
};
Range.prototype.calcInterval = function() {
	var l = this.getHeight();
	var k = this.getMinInterval();
	if ((l / k) <= 1) {
		k = l >> 1
	}
	var i = this.getRange();
	var c = 0;
	while (c > -2 && Math.floor(i) < i) {
		i *= 10;
		c--
	}
	var d, h;
	for (;; c++) {
		h = Math.pow(10, c);
		d = h;
		if (this.toHeight(d) > k) {
			break
		}
		d = 2 * h;
		if (this.toHeight(d) > k) {
			break
		}
		d = 5 * h;
		if (this.toHeight(d) > k) {
			break
		}
	}
	return d
};
Range.prototype.updateGradations = function() {
	this._gradations = [];
	var c = this.calcInterval();
	if (c <= 0) {
		return
	}
	var d = Math.floor(this.getMaxValue() / c) * c;
	do {
		this._gradations.push(d);
		d -= c
	} while (d > this.getMinValue())
};
var PositiveRange = create_class(Range);
PositiveRange.prototype.__construct = function(b) {
	PositiveRange.__super.__construct.call(this, b)
};
PositiveRange.prototype.preSetRange = function(b) {
	if (b.min < 0) {
		b.min = 0
	}
	if (b.max < 0) {
		b.max = 0
	}
};
var ZeroBasedPositiveRange = create_class(Range);
ZeroBasedPositiveRange.prototype.__construct = function(b) {
	ZeroBasedPositiveRange.__super.__construct.call(this, b)
};
ZeroBasedPositiveRange.prototype.preSetRange = function(b) {
	b.min = 0;
	if (b.max < 0) {
		b.max = 0
	}
};
var MainRange = create_class(Range);
MainRange.prototype.__construct = function(b) {
	MainRange.__super.__construct.call(this, b)
};
MainRange.prototype.preSetRange = function(s) {
	var c = ChartManager.getInstance();
	var a = c.getTimeline(this.getDataSourceName());
	var t = a.getMaxIndex() - a.getLastIndex();
	if (t < 25) {
		var r = c.getDataSource(this.getDataSourceName());
		var p = r.getDataAt(r.getDataCount() - 1);
		var o = ((s.max - s.min) / 4) * (1 - (t / 25));
		s.min = Math.min(s.min, Math.max(p.low - o, 0));
		s.max = Math.max(s.max, p.high + o)
	}
	if (s.min > 0) {
		var d = s.max / s.min;
		if (d < 1.016) {
			var q = (s.max + s.min) / 2;
			var m = (d - 1) * 1.5;
			s.max = q * (1 + m);
			s.min = q * (1 - m)
		} else {
			if (d < 1.048) {
				var q = (s.max + s.min) / 2;
				s.max = q * 1.024;
				s.min = q * 0.976
			}
		}
	}
	if (s.min < 0) {
		s.min = 0
	}
	if (s.max < 0) {
		s.max = 0
	}
};
var ZeroCenteredRange = create_class(Range);
ZeroCenteredRange.prototype.__construct = function(b) {
	ZeroCenteredRange.__super.__construct.call(this, b)
};
ZeroCenteredRange.prototype.calcInterval = function(g) {
	var e = this.getMinInterval();
	if (g.getHeight() / e < 2) {
		return 0
	}
	var h = this.getRange();
	var f;
	for (f = 3;; f += 2) {
		if (this.toHeight(h / f) <= e) {
			break
		}
	}
	f -= 2;
	return h / f
};
ZeroCenteredRange.prototype.updateGradations = function() {
	this._gradations = [];
	var g = ChartManager.getInstance();
	var h = g.getArea(this.getAreaName());
	var e = this.calcInterval(h);
	if (e <= 0) {
		return
	}
	var f = e / 2;
	do {
		this._gradations.push(f);
		this._gradations.push(-f);
		f += e
	} while (f <= this.getMaxValue())
};
ZeroCenteredRange.prototype.preSetRange = function(c) {
	var d = Math.max(Math.abs(c.min), Math.abs(c.max));
	c.min = -d;
	c.max = d
};
var PercentageRange = create_class(Range);
PercentageRange.prototype.__construct = function(b) {
	PercentageRange.__super.__construct.call(this, b)
};
PercentageRange.prototype.updateGradations = function() {
	this._gradations = [];
	var h = ChartManager.getInstance();
	var i = h.getArea(this.getAreaName());
	var f = 10;
	var j = Math.floor(this.toHeight(f));
	if ((j << 2) > i.getHeight()) {
		return
	}
	var g = Math.ceil(this.getMinValue() / f) * f;
	if (g == 0) {
		g = 0
	}
	if ((j << 2) < 24) {
		if ((j << 1) < 8) {
			return
		}
		do {
			if (g == 20 || g == 80) {
				this._gradations.push(g)
			}
			g += f
		} while (g < this.getMaxValue())
	} else {
		do {
			if (j < 8) {
				if (g == 20 || g == 50 || g == 80) {
					this._gradations.push(g)
				}
			} else {
				if (g == 0 || g == 20 || g == 50 || g == 80 || g == 100) {
					this._gradations.push(g)
				}
			}
			g += f
		} while (g < this.getMaxValue())
	}
};
var DataSource = create_class(NamedObject);
DataSource.prototype.__construct = function(b) {
	DataSource.__super.__construct.call(this, b)
};
DataSource.UpdateMode = {
	DoNothing: 0,
	Refresh: 1,
	Update: 2,
	Append: 3
};
DataSource.prototype.getUpdateMode = function() {
	return this._updateMode
};
DataSource.prototype.setUpdateMode = function(b) {
	this._updateMode = b
};
DataSource.prototype.getCacheSize = function() {
	return 0
};
DataSource.prototype.getDataCount = function() {
	return 0
};
var MainDataSource = create_class(DataSource);
MainDataSource.prototype.__construct = function(b) {
	MainDataSource.__super.__construct.call(this, b);
	this._erasedCount = 0;
	this._dataItems = [];
	this._decimalDigits = 0;
	this.toolManager = new CToolManager(b)
};
MainDataSource.prototype.getCacheSize = function() {
	return this._dataItems.length
};
MainDataSource.prototype.getDataCount = function() {
	return this._dataItems.length
};
MainDataSource.prototype.getUpdatedCount = function() {
	return this._updatedCount
};
MainDataSource.prototype.getAppendedCount = function() {
	return this._appendedCount
};
MainDataSource.prototype.getErasedCount = function() {
	return this._erasedCount
};
MainDataSource.prototype.getDecimalDigits = function() {
	return this._decimalDigits
};
MainDataSource.prototype.calcDecimalDigits = function(e) {
	var f = "" + e;
	var d = f.indexOf(".");
	if (d < 0) {
		return 0
	}
	return (f.length - 1) - d
};
MainDataSource.prototype.getLastDate = function() {
	var b = this.getDataCount();
	if (b < 1) {
		return -1
	}
	return this.getDataAt(b - 1).date
};
MainDataSource.prototype.getDataAt = function(b) {
	return this._dataItems[b]
};
//K线更新
MainDataSource.prototype.update = function(q) {
	this._updatedCount = 0;
	this._appendedCount = 0;
	this._erasedCount = 0;
	var m = this._dataItems.length;
	this.setUpdateMode(DataSource.UpdateMode.Refresh);
	this._dataItems = [];
	var e, s, i, o, r = q.length;
	for (o = 0; o < r; o++) {
		i = q[o];
		for (s = 1; s <= 4; s++) {
			e = this.calcDecimalDigits(i[s]);
			if (this._decimalDigits < e) {
				this._decimalDigits = e
			}
		}
		var p = Date.parse(new Date());
		if (i[0] > p) {
			continue
		}
		this._dataItems.push({
			date: i[0],
			open: i[1],
			high: i[2],
			low: i[3],
			close: i[4],
			volume: i[5]
		})
	}
	return true
};
MainDataSource.prototype.select = function(b) {
	this.toolManager.selecedObject = b
};
MainDataSource.prototype.unselect = function() {
	this.toolManager.selecedObject = -1
};
MainDataSource.prototype.addToolObject = function(b) {
	this.toolManager.addToolObject(b)
};
MainDataSource.prototype.delToolObject = function() {
	this.toolManager.delCurrentObject()
};
MainDataSource.prototype.getToolObject = function(b) {
	return this.toolManager.getToolObject(b)
};
MainDataSource.prototype.getToolObjectCount = function() {
	return this.toolManager.toolObjects.length
};
MainDataSource.prototype.getCurrentToolObject = function() {
	return this.toolManager.getCurrentObject()
};
MainDataSource.prototype.getSelectToolObjcet = function() {
	return this.toolManager.getSelectedObject()
};
MainDataSource.prototype.delSelectToolObject = function() {
	this.toolManager.delSelectedObject()
};
var DataProvider = create_class(NamedObject);
DataProvider.prototype.__construct = function(b) {
	DataProvider.__super.__construct.call(this, b);
	this._minValue = 0;
	this._maxValue = 0;
	this._minValueIndex = -1;
	this._maxValueIndex = -1
};
DataProvider.prototype.getMinValue = function() {
	return this._minValue
};
DataProvider.prototype.getMaxValue = function() {
	return this._maxValue
};
DataProvider.prototype.getMinValueIndex = function() {
	return this._minValueIndex
};
DataProvider.prototype.getMaxValueIndex = function() {
	return this._maxValueIndex
};
DataProvider.prototype.calcRange = function(n, q, u, p) {
	var t = Number.MAX_VALUE;
	var i = -Number.MAX_VALUE;
	var o = -1;
	var w = -1;
	var v = {};
	var s = q - 1;
	var x = n.length - 1;
	for (; x >= 0; x--) {
		var r = n[x];
		if (s < r) {
			u[x] = {
				min: t,
				max: i
			}
		} else {
			for (; s >= r; s--) {
				if (this.getMinMaxAt(s, v) == false) {
					continue
				}
				if (t > v.min) {
					t = v.min;
					o = s
				}
				if (i < v.max) {
					i = v.max;
					w = s
				}
			}
			u[x] = {
				min: t,
				max: i
			}
		}
		if (p != null) {
			p[x] = {
				minIndex: o,
				maxIndex: w
			}
		}
	}
};
DataProvider.prototype.updateRange = function() {
	var h = ChartManager.getInstance();
	var j = h.getTimeline(this.getDataSourceName());
	var f = [j.getFirstIndex()];
	var i = [{}];
	var g = [{}];
	this.calcRange(f, j.getLastIndex(), i, g);
	this._minValue = i[0].min;
	this._maxValue = i[0].max;
	this._minValueIndex = g[0].minIndex;
	this._maxValueIndex = g[0].maxIndex
};
var MainDataProvider = create_class(DataProvider);
MainDataProvider.prototype.__construct = function(b) {
	MainDataProvider.__super.__construct.call(this, b);
	this._candlestickDS = null
};
MainDataProvider.prototype.updateData = function() {
	var c = ChartManager.getInstance();
	var d = c.getDataSource(this.getDataSourceName());
	if (!is_instance(d, MainDataSource)) {
		return
	}
	this._candlestickDS = d
};
MainDataProvider.prototype.getMinMaxAt = function(e, d) {
	var f = this._candlestickDS.getDataAt(e);
	d.min = f.low;
	d.max = f.high;
	return true
};
var IndicatorDataProvider = create_class(DataProvider);
IndicatorDataProvider.prototype.getIndicator = function() {
	return this._indicator
};
IndicatorDataProvider.prototype.setIndicator = function(b) {
	this._indicator = b;
	this.refresh()
};
IndicatorDataProvider.prototype.refresh = function() {
	var i = ChartManager.getInstance();
	var j = i.getDataSource(this.getDataSourceName());
	if (j.getDataCount() < 1) {
		return
	}
	var h = this._indicator;
	var g, f = j.getDataCount();
	h.clear();
	h.reserve(f);
	for (g = 0; g < f; g++) {
		h.execute(j, g)
	}
};
IndicatorDataProvider.prototype.updateData = function() {
	var l = ChartManager.getInstance();
	var m = l.getDataSource(this.getDataSourceName());
	if (m.getDataCount() < 1) {
		return
	}
	var j = this._indicator;
	var k = m.getUpdateMode();
	switch (k) {
	case DataSource.UpdateMode.Refresh:
		this.refresh();
		break;
	case DataSource.UpdateMode.Append:
		j.reserve(m.getAppendedCount());
	case DataSource.UpdateMode.Update:
		var h, n = m.getDataCount();
		var i = m.getUpdatedCount() + m.getAppendedCount();
		for (h = n - i; h < n; h++) {
			j.execute(m, h)
		}
		break
	}
};
IndicatorDataProvider.prototype.getMinMaxAt = function(g, i) {
	i.min = Number.MAX_VALUE;
	i.max = -Number.MAX_VALUE;
	var h, j = false;
	var k, l = this._indicator.getOutputCount();
	for (k = 0; k < l; k++) {
		h = this._indicator.getOutputAt(k).execute(g);
		if (isNaN(h) == false) {
			j = true;
			if (i.min > h) {
				i.min = h
			}
			if (i.max < h) {
				i.max = h
			}
		}
	}
	return j
};
var theme_color_id = 0;
var theme_font_id = 0;
var Theme = create_class();
Theme.prototype.getColor = function(b) {
	return this._colors[b]
};
Theme.prototype.getFont = function(b) {
	return this._fonts[b]
};
Theme.Color = {
	Positive: theme_color_id++,
	Negative: theme_color_id++,
	PositiveDark: theme_color_id++,
	NegativeDark: theme_color_id++,
	Unchanged: theme_color_id++,
	Background: theme_color_id++,
	Cursor: theme_color_id++,
	RangeMark: theme_color_id++,
	Indicator0: theme_color_id++,
	Indicator1: theme_color_id++,
	Indicator2: theme_color_id++,
	Indicator3: theme_color_id++,
	Indicator4: theme_color_id++,
	Indicator5: theme_color_id++,
	Grid0: theme_color_id++,
	Grid1: theme_color_id++,
	Grid2: theme_color_id++,
	Grid3: theme_color_id++,
	Grid4: theme_color_id++,
	TextPositive: theme_color_id++,
	TextNegative: theme_color_id++,
	Text0: theme_color_id++,
	Text1: theme_color_id++,
	Text2: theme_color_id++,
	Text3: theme_color_id++,
	Text4: theme_color_id++,
	LineColorNormal: theme_color_id++,
	LineColorSelected: theme_color_id++,
	CircleColorFill: theme_color_id++,
	CircleColorStroke: theme_color_id++
};
Theme.Font = {
	Default: theme_font_id++
};
var DarkTheme = create_class(Theme);
DarkTheme.prototype.__construct = function() {
	this._colors = [];
	this._colors[Theme.Color.Positive] = "#FF3232";
	this._colors[Theme.Color.Negative] = "#00ba53";
	this._colors[Theme.Color.PositiveDark] = "#004718";
	this._colors[Theme.Color.NegativeDark] = "#3b0e08";
	this._colors[Theme.Color.Unchanged] = "#fff";
	this._colors[Theme.Color.Background] = "#0a0a0a";
	this._colors[Theme.Color.Cursor] = "#aaa";
	this._colors[Theme.Color.RangeMark] = "#f9ee30";
	this._colors[Theme.Color.Indicator0] = "#ddd";
	this._colors[Theme.Color.Indicator1] = "#f9ee30";
	this._colors[Theme.Color.Indicator2] = "#f600ff";
	this._colors[Theme.Color.Indicator3] = "#6bf";
	this._colors[Theme.Color.Indicator4] = "#a5cf81";
	this._colors[Theme.Color.Indicator5] = "#e18b89";
	this._colors[Theme.Color.Grid0] = "#333";
	this._colors[Theme.Color.Grid1] = "#444";
	this._colors[Theme.Color.Grid2] = "#666";
	this._colors[Theme.Color.Grid3] = "#888";
	this._colors[Theme.Color.Grid4] = "#aaa";
	this._colors[Theme.Color.TextPositive] = "#1bd357";
	this._colors[Theme.Color.TextNegative] = "#ff6f5e";
	this._colors[Theme.Color.Text0] = "#444";
	this._colors[Theme.Color.Text1] = "#666";
	this._colors[Theme.Color.Text2] = "#888";
	this._colors[Theme.Color.Text3] = "#aaa";
	this._colors[Theme.Color.Text4] = "#ccc";
	this._colors[Theme.Color.LineColorNormal] = "#a6a6a6";
	this._colors[Theme.Color.LineColorSelected] = "#ffffff";
	this._colors[Theme.Color.CircleColorFill] = "#000000";
	this._colors[Theme.Color.CircleColorStroke] = "#ffffff";
	this._fonts = [];
	this._fonts[Theme.Font.Default] = "12px Tahoma"
};
var LightTheme = create_class(Theme);
LightTheme.prototype.__construct = function() {
	this._colors = [];
	this._colors[Theme.Color.Positive] = "#db5542";
	this._colors[Theme.Color.Negative] = "#53b37b";
	this._colors[Theme.Color.PositiveDark] = "#66d293";
	this._colors[Theme.Color.NegativeDark] = "#ffadaa";
	this._colors[Theme.Color.Unchanged] = "#fff";
	this._colors[Theme.Color.Background] = "#fff";
	this._colors[Theme.Color.Cursor] = "#aaa";
	this._colors[Theme.Color.RangeMark] = "#f27935";
	this._colors[Theme.Color.Indicator0] = "#2fd2b2";
	this._colors[Theme.Color.Indicator1] = "#ffb400";
	this._colors[Theme.Color.Indicator2] = "#e849b9";
	this._colors[Theme.Color.Indicator3] = "#1478c8";
	this._colors[Theme.Color.Grid0] = "#eee";
	this._colors[Theme.Color.Grid1] = "#afb1b3";
	this._colors[Theme.Color.Grid2] = "#ccc";
	this._colors[Theme.Color.Grid3] = "#bbb";
	this._colors[Theme.Color.Grid4] = "#aaa";
	this._colors[Theme.Color.TextPositive] = "#53b37b";
	this._colors[Theme.Color.TextNegative] = "#db5542";
	this._colors[Theme.Color.Text0] = "#ccc";
	this._colors[Theme.Color.Text1] = "#aaa";
	this._colors[Theme.Color.Text2] = "#888";
	this._colors[Theme.Color.Text3] = "#666";
	this._colors[Theme.Color.Text4] = "#444";
	this._colors[Theme.Color.LineColorNormal] = "#8c8c8c";
	this._colors[Theme.Color.LineColorSelected] = "#393c40";
	this._colors[Theme.Color.CircleColorFill] = "#ffffff";
	this._colors[Theme.Color.CircleColorStroke] = "#393c40";
	this._fonts = [];
	this._fonts[Theme.Font.Default] = "12px Tahoma"
};
var TemplateMeasuringHandler = create_class();
TemplateMeasuringHandler.onMeasuring = function(j, f) {
	var i = f.Width;
	var g = f.Height;
	var h = j.getNameObject().getCompAt(2);
	if (h == "timeline") {
		j.setMeasuredDimension(i, 22)
	}
};
var Template = create_class();
Template.displayVolume = true;
Template.createCandlestickDataSource = function(b) {
	return new MainDataSource(b)
};
Template.createLiveOrderDataSource = function(b) {
	return new CLiveOrderDataSource(b)
};
Template.createLiveTradeDataSource = function(b) {
	return new CLiveTradeDataSource(b)
};
Template.createDataSource = function(g, f, h) {
	var e = ChartManager.getInstance();
	if (e.getCachedDataSource(f) == null) {
		e.setCachedDataSource(f, h(f))
	}
	e.setCurrentDataSource(g, f);
	e.updateData(g, null)
};
Template.createTableComps = function(b) {
	Template.createMainChartComps(b);
	if (Template.displayVolume) {
		Template.createIndicatorChartComps(b, "VOLUME")
	}
	Template.createTimelineComps(b)
};
Template.createMainChartComps = function(r) {
	var k = ChartManager.getInstance();
	var s = k.getArea(r + ".charts");
	var n = r + ".main";
	var m = n + "Range";
	var q = new MainArea(n);
	k.setArea(n, q);
	s.addArea(q);
	var l = new MainRangeArea(m);
	k.setArea(m, l);
	s.addArea(l);
	var p = new MainDataProvider(n + ".main");
	k.setDataProvider(p.getName(), p);
	k.setMainIndicator(r, "MA");
	var o = new MainRange(n);
	k.setRange(o.getName(), o);
	o.setPaddingTop(28);
	o.setPaddingBottom(12);
	var t = new MainAreaBackgroundPlotter(n + ".background");
	k.setPlotter(t.getName(), t);
	t = new CGridPlotter(n + ".grid");
	k.setPlotter(t.getName(), t);
	t = new CandlestickPlotter(n + ".main");
	k.setPlotter(t.getName(), t);
	t = new MinMaxPlotter(n + ".decoration");
	k.setPlotter(t.getName(), t);
	t = new MainInfoPlotter(n + ".info");
	k.setPlotter(t.getName(), t);
	t = new SelectionPlotter(n + ".selection");
	k.setPlotter(t.getName(), t);
	t = new CDynamicLinePlotter(n + ".tool");
	k.setPlotter(t.getName(), t);
	t = new RangeAreaBackgroundPlotter(n + "Range.background");
	k.setPlotter(t.getName(), t);
	t = new COrderGraphPlotter(n + "Range.grid");
	k.setPlotter(t.getName(), t);
	t = new RangePlotter(n + "Range.main");
	k.setPlotter(t.getName(), t);
	t = new RangeSelectionPlotter(n + "Range.selection");
	k.setPlotter(t.getName(), t);
	t = new LastClosePlotter(n + "Range.decoration");
	k.setPlotter(t.getName(), t)
};
Template.createIndicatorChartComps = function(z, a) {
	var i = ChartManager.getInstance();
	var A = i.getArea(z + ".charts");
	var v = z + ".indic" + A.getNextRowId();
	var q = v + "Range";
	var y = new IndicatorArea(v);
	i.setArea(v, y);
	A.addArea(y);
	var t = A.getAreaCount() >> 1;
	var u = ChartSettings.get().charts.areaHeight;
	if (u.length > t) {
		var s, w;
		for (w = 0; w < t; w++) {
			s = A.getAreaAt(w << 1);
			s.setTop(0);
			s.setBottom(u[w])
		}
		y.setTop(0);
		y.setBottom(u[t])
	}
	var r = new IndicatorRangeArea(q);
	i.setArea(q, r);
	A.addArea(r);
	var x = new IndicatorDataProvider(v + ".secondary");
	i.setDataProvider(x.getName(), x);
	if (i.setIndicator(v, a) == false) {
		i.removeIndicator(v);
		return
	}
	var B = new MainAreaBackgroundPlotter(v + ".background");
	i.setPlotter(B.getName(), B);
	B = new CGridPlotter(v + ".grid");
	i.setPlotter(B.getName(), B);
	B = new IndicatorPlotter(v + ".secondary");
	i.setPlotter(B.getName(), B);
	B = new IndicatorInfoPlotter(v + ".info");
	i.setPlotter(B.getName(), B);
	B = new SelectionPlotter(v + ".selection");
	i.setPlotter(B.getName(), B);
	B = new RangeAreaBackgroundPlotter(v + "Range.background");
	i.setPlotter(B.getName(), B);
	B = new RangePlotter(v + "Range.main");
	i.setPlotter(B.getName(), B);
	B = new RangeSelectionPlotter(v + "Range.selection");
	i.setPlotter(B.getName(), B)
};
Template.createTimelineComps = function(g) {
	var e = ChartManager.getInstance();
	var h;
	var f = new Timeline(g);
	e.setTimeline(f.getName(), f);
	h = new TimelineAreaBackgroundPlotter(g + ".timeline.background");
	e.setPlotter(h.getName(), h);
	h = new TimelinePlotter(g + ".timeline.main");
	e.setPlotter(h.getName(), h);
	h = new TimelineSelectionPlotter(g + ".timeline.selection");
	e.setPlotter(h.getName(), h)
};
Template.createLiveOrderComps = function(f) {
	var e = ChartManager.getInstance();
	var d;
	d = new BackgroundPlotter(f + ".main.background");
	e.setPlotter(d.getName(), d);
	d = new CLiveOrderPlotter(f + ".main.main");
	e.setPlotter(d.getName(), d)
};
Template.createLiveTradeComps = function(f) {
	var e = ChartManager.getInstance();
	var d;
	d = new BackgroundPlotter(f + ".main.background");
	e.setPlotter(d.getName(), d);
	d = new CLiveTradePlotter(f + ".main.main");
	e.setPlotter(d.getName(), d)
};
var DefaultTemplate = create_class(Template);
DefaultTemplate.loadTemplate = function(l, i) {
	var n = ChartManager.getInstance();
	var p = ChartSettings.get();
	var j = (new CName(l)).getCompAt(0);
	n.unloadTemplate(j);
	Template.createDataSource(l, i, Template.createCandlestickDataSource);
	var m = new DockableLayout(j);
	n.setFrame(m.getName(), m);
	n.setArea(m.getName(), m);
	m.setGridColor(Theme.Color.Grid1);
	var o = new TimelineArea(l + ".timeline");
	n.setArea(o.getName(), o);
	m.addArea(o);
	o.setDockStyle(ChartArea.DockStyle.Bottom);
	o.Measuring.addHandler(o, TemplateMeasuringHandler.onMeasuring);
	var k = new TableLayout(l + ".charts");
	n.setArea(k.getName(), k);
	k.setDockStyle(ChartArea.DockStyle.Fill);
	m.addArea(k);
	Template.createTableComps(l);
	n.setThemeName(j, p.theme);
	return n
};
var Plotter = create_class(NamedObject);
Plotter.prototype.__construct = function(b) {
	Plotter.__super.__construct.call(this, b)
};
Plotter.isChrome = (navigator.userAgent.toLowerCase().match(/chrome/) != null);
Plotter.drawLine = function(i, f, h, g, j) {
	i.beginPath();
	i.moveTo((f << 0) + 0.5, (h << 0) + 0.5);
	i.lineTo((g << 0) + 0.5, (j << 0) + 0.5);
	i.stroke()
};
Plotter.drawLines = function(h, g) {
	var e, f = g.length;
	h.beginPath();
	h.moveTo(g[0].x, g[0].y);
	for (e = 1; e < f; e++) {
		h.lineTo(g[e].x, g[e].y)
	}
	if (Plotter.isChrome) {
		h.moveTo(g[0].x, g[0].y);
		for (e = 1; e < f; e++) {
			h.lineTo(g[e].x, g[e].y)
		}
	}
	h.stroke()
};
Plotter.drawDashedLine = function(y, x, p, z, q, t, r) {
	if (t < 2) {
		t = 2
	}
	var v = z - x;
	var w = q - p;
	y.beginPath();
	if (w == 0) {
		var s = (v / t + 0.5) << 0;
		for (var u = 0; u < s; u++) {
			y.rect(x, p, r, 1);
			x += t
		}
		y.fill()
	} else {
		var s = (Math.sqrt(v * v + w * w) / t + 0.5) << 0;
		v = v / s;
		w = w / s;
		var i = v * r / t;
		var o = w * r / t;
		for (var u = 0; u < s; u++) {
			y.moveTo(x + 0.5, p + 0.5);
			y.lineTo(x + 0.5 + i, p + 0.5 + o);
			x += v;
			p += w
		}
		y.stroke()
	}
};
Plotter.createHorzDashedLine = function(q, p, r, i, m, k) {
	if (m < 2) {
		m = 2
	}
	var o = r - p;
	var l = (o / m + 0.5) << 0;
	for (var n = 0; n < l; n++) {
		q.rect(p, i, k, 1);
		p += m
	}
};
Plotter.createRectangles = function(i, g) {
	i.beginPath();
	var h, j, e = g.length;
	for (j = 0; j < e; j++) {
		h = g[j];
		i.rect(h.x, h.y, h.w, h.h)
	}
};
Plotter.createPolygon = function(h, g) {
	h.beginPath();
	h.moveTo(g[0].x + 0.5, g[0].y + 0.5);
	var e, f = g.length;
	for (e = 1; e < f; e++) {
		h.lineTo(g[e].x + 0.5, g[e].y + 0.5)
	}
	h.closePath()
};
Plotter.drawString = function(e, g, h) {
	var f = e.measureText(g).width;
	if (h.w < f) {
		return false
	}
	e.fillText(g, h.x, h.y);
	h.x += f;
	h.w -= f;
	return true
};
var BackgroundPlotter = create_class(Plotter);
BackgroundPlotter.prototype.__construct = function(b) {
	BackgroundPlotter.__super.__construct.call(this, b);
	this._color = Theme.Color.Background
};
BackgroundPlotter.prototype.getColor = function() {
	return this._color
};
BackgroundPlotter.prototype.setColor = function(b) {
	this._color = b
};
BackgroundPlotter.prototype.Draw = function(f) {
	var h = ChartManager.getInstance();
	var e = h.getArea(this.getAreaName());
	var g = h.getTheme(this.getFrameName());
	f.fillStyle = g.getColor(this._color);
	f.fillRect(e.getLeft(), e.getTop(), e.getWidth(), e.getHeight())
};
var MainAreaBackgroundPlotter = create_class(BackgroundPlotter);
MainAreaBackgroundPlotter.prototype.__construct = function(b) {
	MainAreaBackgroundPlotter.__super.__construct.call(this, b)
};
MainAreaBackgroundPlotter.prototype.Draw = function(r) {
	var l = ChartManager.getInstance();
	var s = l.getArea(this.getAreaName());
	var k = l.getTimeline(this.getDataSourceName());
	var o = l.getRange(this.getAreaName());
	var q = l.getTheme(this.getFrameName());
	var n = s.getRect();
	if (!s.isChanged() && !k.isUpdated() && !o.isUpdated()) {
		var p = k.getFirstIndex();
		var m = k.getLastIndex() - 2;
		var t = Math.max(p, m);
		n.X = k.toColumnLeft(t);
		n.Width = s.getRight() - n.X
	}
	r.fillStyle = q.getColor(this._color);
	r.fillRect(n.X, n.Y, n.Width, n.Height)
};
var RangeAreaBackgroundPlotter = create_class(BackgroundPlotter);
RangeAreaBackgroundPlotter.prototype.__construct = function(b) {
	RangeAreaBackgroundPlotter.__super.__construct.call(this, b)
};
RangeAreaBackgroundPlotter.prototype.Draw = function(n) {
	var l = ChartManager.getInstance();
	var j = this.getAreaName();
	var m = l.getArea(j);
	var h = l.getRange(j.substring(0, j.lastIndexOf("Range")));
	var i = h.getNameObject().getCompAt(2) == "main";
	if (i) {} else {
		if (!m.isChanged() && !h.isUpdated()) {
			return
		}
	}
	var k = l.getTheme(this.getFrameName());
	n.fillStyle = k.getColor(this._color);
	n.fillRect(m.getLeft(), m.getTop(), m.getWidth(), m.getHeight())
};
var TimelineAreaBackgroundPlotter = create_class(BackgroundPlotter);
TimelineAreaBackgroundPlotter.prototype.__construct = function(b) {
	TimelineAreaBackgroundPlotter.__super.__construct.call(this, b)
};
TimelineAreaBackgroundPlotter.prototype.Draw = function(g) {
	var i = ChartManager.getInstance();
	var j = i.getArea(this.getAreaName());
	var f = i.getTimeline(this.getDataSourceName());
	if (!j.isChanged() && !f.isUpdated()) {
		return
	}
	var h = i.getTheme(this.getFrameName());
	g.fillStyle = h.getColor(this._color);
	g.fillRect(j.getLeft(), j.getTop(), j.getWidth(), j.getHeight())
};
var CGridPlotter = create_class(NamedObject);
CGridPlotter.prototype.__construct = function(b) {
	CGridPlotter.__super.__construct.call(this, b)
};
CGridPlotter.prototype.Draw = function(B) {
	var q = ChartManager.getInstance();
	var C = q.getArea(this.getAreaName());
	var n = q.getTimeline(this.getDataSourceName());
	var u = q.getRange(this.getAreaName());
	var y = false;
	if (!C.isChanged() && !n.isUpdated() && !u.isUpdated()) {
		var v = n.getFirstIndex();
		var r = n.getLastIndex();
		var D = Math.max(v, r - 2);
		var z = n.toColumnLeft(D);
		B.save();
		B.rect(z, C.getTop(), C.getRight() - z, C.getHeight());
		B.clip();
		y = true
	}
	var w = q.getTheme(this.getFrameName());
	B.fillStyle = w.getColor(Theme.Color.Grid0);
	B.beginPath();
	var x = 4,
		t = 1;
	if (Plotter.isChrome) {
		x = 4;
		t = 1
	}
	var s = u.getGradations();
	for (var A in s) {
		Plotter.createHorzDashedLine(B, C.getLeft(), C.getRight(), u.toY(s[A]), x, t)
	}
	B.fill();
	if (y) {
		B.restore()
	}
};
var CandlestickPlotter = create_class(NamedObject);
CandlestickPlotter.prototype.__construct = function(b) {
	CandlestickPlotter.__super.__construct.call(this, b)
};
CandlestickPlotter.prototype.Draw = function(ab) {
	var O = ChartManager.getInstance();
	var J = O.getDataSource(this.getDataSourceName());
	if (J.getDataCount() < 1) {
		return
	}
	var H = O.getArea(this.getAreaName());
	var W = O.getTimeline(this.getDataSourceName());
	var P = O.getRange(this.getAreaName());
	if (P.getRange() == 0) {
		return
	}
	var D = O.getTheme(this.getFrameName());
	var ad = is_instance(D, DarkTheme);
	var V = W.getFirstIndex();
	var R = W.getLastIndex();
	var X;
	if (H.isChanged() || W.isUpdated() || P.isUpdated()) {
		X = V
	} else {
		X = Math.max(V, R - 2)
	}
	var G = W.getColumnWidth();
	var E = W.getItemWidth();
	var Z = W.toItemLeft(X);
	var i = W.toItemCenter(X);
	var F = [];
	var Y = [];
	var ac = [];
	var T = [];
	for (var I = X; I < R; I++) {
		var N = J.getDataAt(I);
		var S = P.toY(N.high);
		var L = P.toY(N.low);
		var Q = N.open;
		var K = N.close;
		if (K > Q) {
			var M = P.toY(K);
			var U = P.toY(Q);
			var aa = Math.max(U - M, 1);
			if (aa > 1 && E > 1 && ad) {
				F.push({
					x: Z + 0.5,
					y: M + 0.5,
					w: E - 1,
					h: aa - 1
				})
			} else {
				Y.push({
					x: Z,
					y: M,
					w: Math.max(E, 1),
					h: Math.max(aa, 1)
				})
			}
			if (N.high > K) {
				S = Math.min(S, M - 1);
				Y.push({
					x: i,
					y: S,
					w: 1,
					h: M - S
				})
			}
			if (Q > N.low) {
				L = Math.max(L, U + 1);
				Y.push({
					x: i,
					y: U,
					w: 1,
					h: L - U
				})
			}
		} else {
			if (K == Q) {
				var M = P.toY(K);
				ac.push({
					x: Z,
					y: M,
					w: Math.max(E, 1),
					h: 1
				});
				if (N.high > K) {
					S = Math.min(S, M - 1)
				}
				if (Q > N.low) {
					L = Math.max(L, M + 1)
				}
				if (S < L) {
					ac.push({
						x: i,
						y: S,
						w: 1,
						h: L - S
					})
				}
			} else {
				var M = P.toY(Q);
				var U = P.toY(K);
				var aa = Math.max(U - M, 1);
				T.push({
					x: Z,
					y: M,
					w: Math.max(E, 1),
					h: Math.max(aa, 1)
				});
				if (N.high > Q) {
					S = Math.min(S, M - 1)
				}
				if (K > N.low) {
					L = Math.max(L, U + 1)
				}
				if (S < L) {
					T.push({
						x: i,
						y: S,
						w: 1,
						h: L - S
					})
				}
			}
		}
		Z += G;
		i += G
	}
	if (F.length > 0) {
		ab.strokeStyle = D.getColor(Theme.Color.Positive);
		Plotter.createRectangles(ab, F);
		ab.stroke()
	}
	if (Y.length > 0) {
		ab.fillStyle = D.getColor(Theme.Color.Positive);
		Plotter.createRectangles(ab, Y);
		ab.fill()
	}
	if (ac.length > 0) {
		ab.fillStyle = D.getColor(Theme.Color.Negative);
		Plotter.createRectangles(ab, ac);
		ab.fill()
	}
	if (T.length > 0) {
		ab.fillStyle = D.getColor(Theme.Color.Negative);
		Plotter.createRectangles(ab, T);
		ab.fill()
	}
};
var CandlestickHLCPlotter = create_class(Plotter);
CandlestickHLCPlotter.prototype.__construct = function(b) {
	CandlestickHLCPlotter.__super.__construct.call(this, b)
};
CandlestickHLCPlotter.prototype.Draw = function(ab) {
	var O = ChartManager.getInstance();
	var J = O.getDataSource(this.getDataSourceName());
	if (!is_instance(J, MainDataSource) || J.getDataCount() < 1) {
		return
	}
	var H = O.getArea(this.getAreaName());
	var W = O.getTimeline(this.getDataSourceName());
	var P = O.getRange(this.getAreaName());
	if (P.getRange() == 0) {
		return
	}
	var D = O.getTheme(this.getFrameName());
	var ad = is_instance(D, DarkTheme);
	var V = W.getFirstIndex();
	var R = W.getLastIndex();
	var X;
	if (H.isChanged() || W.isUpdated() || P.isUpdated()) {
		X = V
	} else {
		X = Math.max(V, R - 2)
	}
	var G = W.getColumnWidth();
	var E = W.getItemWidth();
	var Z = W.toItemLeft(X);
	var i = W.toItemCenter(X);
	var F = [];
	var Y = [];
	var ac = [];
	var T = [];
	for (var I = X; I < R; I++) {
		var N = J.getDataAt(I);
		var S = P.toY(N.high);
		var L = P.toY(N.low);
		var Q = N.open;
		if (I > 0) {
			Q = J.getDataAt(I - 1).close
		}
		var K = N.close;
		if (K > Q) {
			var M = P.toY(K);
			var U = P.toY(Q);
			var aa = Math.max(U - M, 1);
			if (aa > 1 && E > 1 && ad) {
				F.push({
					x: Z + 0.5,
					y: M + 0.5,
					w: E - 1,
					h: aa - 1
				})
			} else {
				Y.push({
					x: Z,
					y: M,
					w: Math.max(E, 1),
					h: Math.max(aa, 1)
				})
			}
			if (N.high > K) {
				S = Math.min(S, M - 1);
				Y.push({
					x: i,
					y: S,
					w: 1,
					h: M - S
				})
			}
			if (Q > N.low) {
				L = Math.max(L, U + 1);
				Y.push({
					x: i,
					y: U,
					w: 1,
					h: L - U
				})
			}
		} else {
			if (K == Q) {
				var M = P.toY(K);
				ac.push({
					x: Z,
					y: M,
					w: Math.max(E, 1),
					h: 1
				});
				if (N.high > K) {
					S = Math.min(S, M - 1)
				}
				if (Q > N.low) {
					L = Math.max(L, M + 1)
				}
				if (S < L) {
					ac.push({
						x: i,
						y: S,
						w: 1,
						h: L - S
					})
				}
			} else {
				var M = P.toY(Q);
				var U = P.toY(K);
				var aa = Math.max(U - M, 1);
				T.push({
					x: Z,
					y: M,
					w: Math.max(E, 1),
					h: Math.max(aa, 1)
				});
				if (N.high > Q) {
					S = Math.min(S, M - 1)
				}
				if (K > N.low) {
					L = Math.max(L, U + 1)
				}
				if (S < L) {
					T.push({
						x: i,
						y: S,
						w: 1,
						h: L - S
					})
				}
			}
		}
		Z += G;
		i += G
	}
	if (F.length > 0) {
		ab.fillStyle = D.getColor(Theme.Color.Positive);
		Plotter.createRectangles(ab, F);
		ab.fill()
	}
	if (Y.length > 0) {
		ab.strokeStyle = D.getColor(Theme.Color.Positive);
		Plotter.createRectangles(ab, Y);
		ab.stroke()
	}
	if (ac.length > 0) {
		ab.fillStyle = D.getColor(Theme.Color.Negative);
		Plotter.createRectangles(ab, ac);
		ab.fill()
	}
	if (T.length > 0) {
		ab.fillStyle = D.getColor(Theme.Color.Negative);
		Plotter.createRectangles(ab, T);
		ab.fill()
	}
};
var OHLCPlotter = create_class(Plotter);
OHLCPlotter.prototype.__construct = function(b) {
	OHLCPlotter.__super.__construct.call(this, b)
};
OHLCPlotter.prototype.Draw = function(Y) {
	var i = ChartManager.getInstance();
	var J = i.getDataSource(this.getDataSourceName());
	if (!is_instance(J, MainDataSource) || J.getDataCount() < 1) {
		return
	}
	var G = i.getArea(this.getAreaName());
	var T = i.getTimeline(this.getDataSourceName());
	var M = i.getRange(this.getAreaName());
	if (M.getRange() == 0) {
		return
	}
	var C = i.getTheme(this.getFrameName());
	var S = T.getFirstIndex();
	var N = T.getLastIndex();
	var U;
	if (G.isChanged() || T.isUpdated() || M.isUpdated()) {
		U = S
	} else {
		U = Math.max(S, N - 2)
	}
	var F = T.getColumnWidth();
	var D = T.getItemWidth() >> 1;
	var W = T.toItemLeft(U);
	var y = T.toItemCenter(U);
	var E = W + T.getItemWidth();
	var V = [];
	var Z = [];
	var Q = [];
	for (var H = U; H < N; H++) {
		var L = J.getDataAt(H);
		var O = M.toY(L.high);
		var I = M.toY(L.low);
		var X = Math.max(I - O, 1);
		if (L.close > L.open) {
			var K = M.toY(L.close);
			var R = M.toY(L.open);
			V.push({
				x: y,
				y: O,
				w: 1,
				h: X
			});
			V.push({
				x: W,
				y: K,
				w: D,
				h: 1
			});
			V.push({
				x: y,
				y: R,
				w: D,
				h: 1
			})
		} else {
			if (L.close == L.open) {
				var P = M.toY(L.close);
				Z.push({
					x: y,
					y: O,
					w: 1,
					h: X
				});
				Z.push({
					x: W,
					y: P,
					w: D,
					h: 1
				});
				Z.push({
					x: y,
					y: P,
					w: D,
					h: 1
				})
			} else {
				var K = M.toY(L.open);
				var R = M.toY(L.close);
				Q.push({
					x: y,
					y: O,
					w: 1,
					h: X
				});
				Q.push({
					x: W,
					y: K,
					w: D,
					h: 1
				});
				Q.push({
					x: y,
					y: R,
					w: D,
					h: 1
				})
			}
		}
		W += F;
		y += F;
		E += F
	}
	if (V.length > 0) {
		Y.fillStyle = C.getColor(Theme.Color.Positive);
		Plotter.createRectangles(Y, V);
		Y.fill()
	}
	if (Z.length > 0) {
		Y.fillStyle = C.getColor(Theme.Color.Negative);
		Plotter.createRectangles(Y, Z);
		Y.fill()
	}
	if (Q.length > 0) {
		Y.fillStyle = C.getColor(Theme.Color.Negative);
		Plotter.createRectangles(Y, Q);
		Y.fill()
	}
};
var MainInfoPlotter = create_class(Plotter);
MainInfoPlotter.prototype.__construct = function(b) {
	MainInfoPlotter.__super.__construct.call(this, b)
};

function format_time(b) {
	return (b < 10) ? "0" + b.toString() : b.toString()
}
MainInfoPlotter.prototype.Draw = function(ab) {
	var N = ChartManager.getInstance();
	var F = N.getArea(this.getAreaName());
	var Z = N.getTimeline(this.getDataSourceName());
	var R = N.getDataSource(this.getDataSourceName());
	var v = N.getTheme(this.getFrameName());
	ab.font = v.getFont(Theme.Font.Default);
	ab.textAlign = "left";
	ab.textBaseline = "top";
	ab.fillStyle = v.getColor(Theme.Color.Text4);
	//控制成交统计条的位置
	var ad = {
		x: F.getLeft() + 15,
		y: F.getTop() + 30,
		w: F.getWidth() - 8,
		h: 20
	};
	var I = Z.getSelectedIndex();
	if (I < 0) {
		return
	}
	var L = R.getDataAt(I);
	var V = R.getDecimalDigits();
	var X = new Date(L.date);
	var T = X.getFullYear();
	var P = format_time(X.getMonth() + 1);
	var n = format_time(X.getDate());
	var Y = format_time(X.getHours());
	var S = format_time(X.getMinutes());
	var J = N.getLanguage();
	if (J == "zh-cn") {
		if (!Plotter.drawString(ab, "时间: " + T + "-" + P + "-" + n + "  " + Y + ":" + S, ad)) {
			return
		}
		if (!Plotter.drawString(ab, "  开: " + L.open.toFixed(V), ad)) {
			return
		}
		if (!Plotter.drawString(ab, "  高: " + L.high.toFixed(V), ad)) {
			return
		}
		if (!Plotter.drawString(ab, "  低: " + L.low.toFixed(V), ad)) {
			return
		}
		if (!Plotter.drawString(ab, "  收: " + L.close.toFixed(V), ad)) {
			return
		}
	} else {
		if (J == "en-us") {
			if (!Plotter.drawString(ab, "DATE: " + T + "-" + P + "-" + n + "  " + Y + ":" + S, ad)) {
				return
			}
			if (!Plotter.drawString(ab, "  O: " + L.open.toFixed(V), ad)) {
				return
			}
			if (!Plotter.drawString(ab, "  H: " + L.high.toFixed(V), ad)) {
				return
			}
			if (!Plotter.drawString(ab, "  L: " + L.low.toFixed(V), ad)) {
				return
			}
			if (!Plotter.drawString(ab, "  C: " + L.close.toFixed(V), ad)) {
				return
			}
		} else {
			if (J == "zh-tw") {
				if (!Plotter.drawString(ab, "時間: " + T + "-" + P + "-" + n + "  " + Y + ":" + S, ad)) {
					return
				}
				if (!Plotter.drawString(ab, "  開: " + L.open.toFixed(V), ad)) {
					return
				}
				if (!Plotter.drawString(ab, "  高: " + L.high.toFixed(V), ad)) {
					return
				}
				if (!Plotter.drawString(ab, "  低: " + L.low.toFixed(V), ad)) {
					return
				}
				if (!Plotter.drawString(ab, "  收: " + L.close.toFixed(V), ad)) {
					return
				}
			}
		}
	}
	if (I > 0) {
		if (J == "zh-cn") {
			if (!Plotter.drawString(ab, "  涨幅: ", ad)) {
				return
			}
		} else {
			if (J == "en-us") {
				if (!Plotter.drawString(ab, "  CHANGE: ", ad)) {
					return
				}
			} else {
				if (J == "zh-tw") {
					if (!Plotter.drawString(ab, "  漲幅: ", ad)) {
						return
					}
				}
			}
		}
		var Q = R.getDataAt(I - 1);
		var W = (L.close - Q.close) / Q.close * 100;
		if (W >= 0) {
			W = " " + W.toFixed(2);
			ab.fillStyle = v.getColor(Theme.Color.TextPositive)
		} else {
			W = W.toFixed(2);
			ab.fillStyle = v.getColor(Theme.Color.TextNegative)
		}
		if (!Plotter.drawString(ab, W, ad)) {
			return
		}
		ab.fillStyle = v.getColor(Theme.Color.Text4);
		if (!Plotter.drawString(ab, " %", ad)) {
			return
		}
	}
	var aa = (L.high - L.low) / L.low * 100;
	if (J == "zh-cn") {
		if (!Plotter.drawString(ab, "  振幅: " + aa.toFixed(2) + " %", ad)) {
			return
		}
		if (!Plotter.drawString(ab, "  量: " + L.volume.toFixed(2), ad)) {
			return
		}
	} else {
		if (J == "en-us") {
			if (!Plotter.drawString(ab, "  AMPLITUDE: " + aa.toFixed(2) + " %", ad)) {
				return
			}
			if (!Plotter.drawString(ab, "  V: " + L.volume.toFixed(2), ad)) {
				return
			}
		} else {
			if (J == "zh-tw") {
				if (!Plotter.drawString(ab, "  振幅: " + aa.toFixed(2) + " %", ad)) {
					return
				}
				if (!Plotter.drawString(ab, "  量: " + L.volume.toFixed(2), ad)) {
					return
				}
			}
		}
	}
	var M = N.getDataProvider(this.getAreaName() + ".secondary");
	if (M == undefined) {
		return
	}
	var ac = M.getIndicator();
	var O, H = ac.getOutputCount();
	for (O = 0; O < H; O++) {
		var G = ac.getOutputAt(O);
		var U = G.execute(I);
		if (isNaN(U)) {
			continue
		}
		var E = "  " + G.getName() + ": " + U.toFixed(V);
		var K = G.getColor();
		if (K === undefined) {
			K = Theme.Color.Indicator0 + O
		}
		ab.fillStyle = v.getColor(K);
		if (!Plotter.drawString(ab, E, ad)) {
			return
		}
	}
};
var IndicatorPlotter = create_class(NamedObject);
IndicatorPlotter.prototype.__construct = function(b) {
	IndicatorPlotter.__super.__construct.call(this, b)
};
IndicatorPlotter.prototype.Draw = function(T) {
	var G = ChartManager.getInstance();
	var x = G.getArea(this.getAreaName());
	var P = G.getTimeline(this.getDataSourceName());
	var K = G.getRange(this.getAreaName());
	if (K.getRange() == 0) {
		return
	}
	var J = G.getDataProvider(this.getName());
	if (!is_instance(J, IndicatorDataProvider)) {
		return
	}
	var n = G.getTheme(this.getFrameName());
	var v = P.getColumnWidth();
	var O = P.getFirstIndex();
	var N = P.getLastIndex();
	var Q;
	if (x.isChanged() || P.isUpdated() || K.isUpdated()) {
		Q = O
	} else {
		Q = Math.max(O, N - 2)
	}
	var S = J.getIndicator();
	var C, I, H = S.getOutputCount();
	for (I = 0; I < H; I++) {
		C = S.getOutputAt(I);
		var B = C.getStyle();
		if (B == OutputStyle.VolumeStick) {
			this.drawVolumeStick(T, n, G.getDataSource(this.getDataSourceName()), Q, N, P.toItemLeft(Q), v, P.getItemWidth(), K)
		} else {
			if (B == OutputStyle.MACDStick) {
				this.drawMACDStick(T, n, C, Q, N, P.toItemLeft(Q), v, P.getItemWidth(), K)
			} else {
				if (B == OutputStyle.SARPoint) {
					this.drawSARPoint(T, n, C, Q, N, P.toItemCenter(Q), v, P.getItemWidth(), K)
				}
			}
		}
	}
	var R = P.toColumnLeft(Q);
	var i = P.toItemCenter(Q);
	T.save();
	T.rect(R, x.getTop(), x.getRight() - R, x.getHeight());
	T.clip();
	T.translate(0.5, 0.5);
	for (I = 0; I < H; I++) {
		var M = i;
		C = S.getOutputAt(I);
		if (C.getStyle() == OutputStyle.Line) {
			var L, D = [];
			if (Q > O) {
				L = C.execute(Q - 1);
				if (isNaN(L) == false) {
					D.push({
						x: M - v,
						y: K.toY(L)
					})
				}
			}
			for (var E = Q; E < N; E++, M += v) {
				L = C.execute(E);
				if (isNaN(L) == false) {
					D.push({
						x: M,
						y: K.toY(L)
					})
				}
			}
			if (D.length > 0) {
				var F = C.getColor();
				if (F == undefined) {
					F = Theme.Color.Indicator0 + I
				}
				T.strokeStyle = n.getColor(F);
				Plotter.drawLines(T, D)
			}
		}
	}
	T.restore()
};
IndicatorPlotter.prototype.drawVolumeStick = function(K, u, A, G, D, y, x, v, C) {
	var L = is_instance(u, DarkTheme);
	var I = y;
	var E = C.toY(0);
	var w = [];
	var H = [];
	var F = [];
	for (var z = G; z < D; z++) {
		var i = A.getDataAt(z);
		var B = C.toY(i.volume);
		var J = C.toHeight(i.volume);
		if (i.close > i.open) {
			if (J > 1 && v > 1 && L) {
				w.push({
					x: I + 0.5,
					y: B + 0.5,
					w: v - 1,
					h: J - 1
				})
			} else {
				H.push({
					x: I,
					y: B,
					w: Math.max(v, 1),
					h: Math.max(J, 1)
				})
			}
		} else {
			if (i.close == i.open) {
				if (z > 0 && i.close >= A.getDataAt(z - 1).close) {
					if (J > 1 && v > 1 && L) {
						w.push({
							x: I + 0.5,
							y: B + 0.5,
							w: v - 1,
							h: J - 1
						})
					} else {
						H.push({
							x: I,
							y: B,
							w: Math.max(v, 1),
							h: Math.max(J, 1)
						})
					}
				} else {
					F.push({
						x: I,
						y: B,
						w: Math.max(v, 1),
						h: Math.max(J, 1)
					})
				}
			} else {
				F.push({
					x: I,
					y: B,
					w: Math.max(v, 1),
					h: Math.max(J, 1)
				})
			}
		}
		I += x
	}
	if (w.length > 0) {
		K.strokeStyle = u.getColor(Theme.Color.Positive);
		Plotter.createRectangles(K, w);
		K.stroke()
	}
	if (H.length > 0) {
		K.fillStyle = u.getColor(Theme.Color.Positive);
		Plotter.createRectangles(K, H);
		K.fill()
	}
	if (F.length > 0) {
		K.fillStyle = u.getColor(Theme.Color.Negative);
		Plotter.createRectangles(K, F);
		K.fill()
	}
};
IndicatorPlotter.prototype.drawMACDStick = function(L, v, F, H, E, z, y, w, C) {
	var J = z;
	var u = C.toY(0);
	var x = [];
	var i = [];
	var I = [];
	var G = [];
	var D = (H > 0) ? F.execute(H - 1) : NaN;
	for (var B = H; B < E; B++) {
		var A = F.execute(B);
		if (A >= 0) {
			var K = C.toHeight(A);
			if ((B == 0 || A >= D) && K > 1 && w > 1) {
				x.push({
					x: J + 0.5,
					y: u - K + 0.5,
					w: w - 1,
					h: K - 1
				})
			} else {
				I.push({
					x: J,
					y: u - K,
					w: Math.max(w, 1),
					h: Math.max(K, 1)
				})
			}
		} else {
			var K = C.toHeight(-A);
			if ((B == 0 || A >= D) && K > 1 && w > 1) {
				i.push({
					x: J + 0.5,
					y: u + 0.5,
					w: w - 1,
					h: K - 1
				})
			} else {
				G.push({
					x: J,
					y: u,
					w: Math.max(w, 1),
					h: Math.max(K, 1)
				})
			}
		}
		D = A;
		J += y
	}
	if (x.length > 0) {
		L.strokeStyle = v.getColor(Theme.Color.Positive);
		Plotter.createRectangles(L, x);
		L.stroke()
	}
	if (i.length > 0) {
		L.strokeStyle = v.getColor(Theme.Color.Negative);
		Plotter.createRectangles(L, i);
		L.stroke()
	}
	if (I.length > 0) {
		L.fillStyle = v.getColor(Theme.Color.Positive);
		Plotter.createRectangles(L, I);
		L.fill()
	}
	if (G.length > 0) {
		L.fillStyle = v.getColor(Theme.Color.Negative);
		Plotter.createRectangles(L, G);
		L.fill()
	}
};
IndicatorPlotter.prototype.drawSARPoint = function(B, x, z, v, i, u, r, t, w) {
	var C = t >> 1;
	if (C < 0.5) {
		C = 0.5
	}
	if (C > 4) {
		C = 4
	}
	var D = u;
	var q = D + C;
	var A = 2 * Math.PI;
	B.save();
	B.translate(0.5, 0.5);
	B.strokeStyle = x.getColor(Theme.Color.Indicator3);
	B.beginPath();
	for (var y = v; y < i; y++) {
		var s = w.toY(z.execute(y));
		B.moveTo(q, s);
		B.arc(D, s, C, 0, A);
		D += r;
		q += r
	}
	B.stroke();
	B.restore()
};
var IndicatorInfoPlotter = create_class(Plotter);
IndicatorInfoPlotter.prototype.__construct = function(b) {
	IndicatorInfoPlotter.__super.__construct.call(this, b)
};
IndicatorInfoPlotter.prototype.Draw = function(D) {
	var r = ChartManager.getInstance();
	var E = r.getArea(this.getAreaName());
	var n = r.getTimeline(this.getDataSourceName());
	var y = r.getDataProvider(this.getAreaName() + ".secondary");
	var w = r.getTheme(this.getFrameName());
	D.font = w.getFont(Theme.Font.Default);
	D.textAlign = "left";
	D.textBaseline = "top";
	D.fillStyle = w.getColor(Theme.Color.Text4);
	var u = {
		x: E.getLeft() + 4,
		y: E.getTop() + 2,
		w: E.getWidth() - 8,
		h: 20
	};
	var F = y.getIndicator();
	var t;
	switch (F.getParameterCount()) {
	case 0:
		t = F.getName();
		break;
	case 1:
		t = F.getName() + "(" + F.getParameterAt(0).getValue() + ")";
		break;
	case 2:
		t = F.getName() + "(" + F.getParameterAt(0).getValue() + "," + F.getParameterAt(1).getValue() + ")";
		break;
	case 3:
		t = F.getName() + "(" + F.getParameterAt(0).getValue() + "," + F.getParameterAt(1).getValue() + "," + F.getParameterAt(2).getValue() + ")";
		break;
	case 4:
		t = F.getName() + "(" + F.getParameterAt(0).getValue() + "," + F.getParameterAt(1).getValue() + "," + F.getParameterAt(2).getValue() + "," + F.getParameterAt(3).getValue() + ")";
		break;
	default:
		return
	}
	if (!Plotter.drawString(D, t, u)) {
		return
	}
	var v = n.getSelectedIndex();
	if (v < 0) {
		return
	}
	var x, s, A, z;
	var B, C = F.getOutputCount();
	for (B = 0; B < C; B++) {
		x = F.getOutputAt(B);
		s = x.execute(v);
		if (isNaN(s)) {
			continue
		}
		A = "  " + x.getName() + ": " + s.toFixed(2);
		z = x.getColor();
		if (z === undefined) {
			z = Theme.Color.Indicator0 + B
		}
		D.fillStyle = w.getColor(z);
		if (!Plotter.drawString(D, A, u)) {
			return
		}
	}
};
var MinMaxPlotter = create_class(NamedObject);
MinMaxPlotter.prototype.__construct = function(b) {
	MinMaxPlotter.__super.__construct.call(this, b)
};
MinMaxPlotter.prototype.Draw = function(s) {
	var l = ChartManager.getInstance();
	var q = l.getDataSource(this.getDataSourceName());
	if (q.getDataCount() < 1) {
		return
	}
	var k = l.getTimeline(this.getDataSourceName());
	if (k.getInnerWidth() < k.getColumnWidth()) {
		return
	}
	var m = l.getRange(this.getAreaName());
	if (m.getRange() == 0) {
		return
	}
	var p = l.getDataProvider(this.getAreaName() + ".main");
	var n = k.getFirstIndex();
	var t = (n + k.getLastIndex()) >> 1;
	var o = l.getTheme(this.getFrameName());
	s.font = o.getFont(Theme.Font.Default);
	s.textBaseline = "middle";
	s.fillStyle = o.getColor(Theme.Color.Text4);
	s.strokeStyle = o.getColor(Theme.Color.Text4);
	var r = q.getDecimalDigits();
	this.drawMark(s, p.getMinValue(), r, m.toY(p.getMinValue()), n, t, p.getMinValueIndex(), k);
	this.drawMark(s, p.getMaxValue(), r, m.toY(p.getMaxValue()), n, t, p.getMaxValueIndex(), k)
};
MinMaxPlotter.prototype.drawMark = function(u, n, t, o, r, x, q, m) {
	var s, v, w;
	var p;
	if (q > x) {
		u.textAlign = "right";
		s = m.toItemCenter(q) - 4;
		v = s - 7;
		w = s - 3;
		p = v - 4
	} else {
		u.textAlign = "left";
		s = m.toItemCenter(q) + 4;
		v = s + 7;
		w = s + 3;
		p = v + 4
	}
	Plotter.drawLine(u, s, o, v, o);
	Plotter.drawLine(u, s, o, w, o + 2);
	Plotter.drawLine(u, s, o, w, o - 2);
	u.fillText(String.fromFloat(n, t), p, o)
};
var TimelinePlotter = create_class(Plotter);
TimelinePlotter.prototype.__construct = function(b) {
	TimelinePlotter.__super.__construct.call(this, b)
};
TimelinePlotter.TP_MINUTE = 60 * 1000;
TimelinePlotter.TP_HOUR = 60 * TimelinePlotter.TP_MINUTE;
TimelinePlotter.TP_DAY = 24 * TimelinePlotter.TP_HOUR;
TimelinePlotter.TIME_INTERVAL = [5 * TimelinePlotter.TP_MINUTE, 10 * TimelinePlotter.TP_MINUTE, 15 * TimelinePlotter.TP_MINUTE, 30 * TimelinePlotter.TP_MINUTE, TimelinePlotter.TP_HOUR, 2 * TimelinePlotter.TP_HOUR, 3 * TimelinePlotter.TP_HOUR, 6 * TimelinePlotter.TP_HOUR, 12 * TimelinePlotter.TP_HOUR, TimelinePlotter.TP_DAY, 2 * TimelinePlotter.TP_DAY];
TimelinePlotter.MonthConvert = {
	1: "Jan.",
	2: "Feb.",
	3: "Mar.",
	4: "Apr.",
	5: "May.",
	6: "Jun.",
	7: "Jul.",
	8: "Aug.",
	9: "Sep.",
	10: "Oct.",
	11: "Nov.",
	12: "Dec."
};
TimelinePlotter.prototype.Draw = function(ag) {
	var K = ChartManager.getInstance();
	var d = K.getArea(this.getAreaName());
	var ae = K.getTimeline(this.getDataSourceName());
	if (!d.isChanged() && !ae.isUpdated()) {
		return
	}
	var Q = K.getDataSource(this.getDataSourceName());
	if (Q.getDataCount() < 2) {
		return
	}
	var O = Q.getDataAt(1).date - Q.getDataAt(0).date;
	var M, x = TimelinePlotter.TIME_INTERVAL.length;
	for (M = 0; M < x; M++) {
		if (O < TimelinePlotter.TIME_INTERVAL[M]) {
			break
		}
	}
	for (; M < x; M++) {
		if (TimelinePlotter.TIME_INTERVAL[M] % O == 0) {
			if ((TimelinePlotter.TIME_INTERVAL[M] / O) * ae.getColumnWidth() > 60) {
				break
			}
		}
	}
	var ab = ae.getFirstIndex();
	var Z = ae.getLastIndex();
	var W = new Date();
	var af = W.getTimezoneOffset() * 60 * 1000;
	var S = K.getTheme(this.getFrameName());
	ag.font = S.getFont(Theme.Font.Default);
	ag.textAlign = "center";
	ag.textBaseline = "middle";
	var m = K.getLanguage();
	var aa = [];
	var U = d.getTop();
	var n = d.getMiddle();
	for (var i = ab; i < Z; i++) {
		var L = Q.getDataAt(i).date;
		var ah = L - af;
		var ac = new Date(L);
		var X = ac.getFullYear();
		var N = ac.getMonth() + 1;
		var T = ac.getDate();
		var ad = ac.getHours();
		var R = ac.getMinutes();
		var V = "";
		if (M < x) {
			var J = Math.max(TimelinePlotter.TP_DAY, TimelinePlotter.TIME_INTERVAL[M]);
			if (ah % J == 0) {
				if (m == "zh-cn") {
					V = N.toString() + "月" + T.toString() + "日"
				} else {
					if (m == "zh-tw") {
						V = N.toString() + "月" + T.toString() + "日"
					} else {
						if (m == "en-us") {
							V = TimelinePlotter.MonthConvert[N] + " " + T.toString()
						}
					}
				}
				ag.fillStyle = S.getColor(Theme.Color.Text4)
			} else {
				if (ah % TimelinePlotter.TIME_INTERVAL[M] == 0) {
					var P = R.toString();
					if (R < 10) {
						P = "0" + P
					}
					V = ad.toString() + ":" + P;
					ag.fillStyle = S.getColor(Theme.Color.Text2)
				}
			}
		} else {
			if (T == 1 && (ad < (O / TimelinePlotter.TP_HOUR))) {
				if (N == 1) {
					V = X.toString();
					if (m == "zh-cn") {
						V += "年"
					} else {
						if (m == "zh-tw") {
							V += "年"
						}
					}
				} else {
					if (m == "zh-cn") {
						V = N.toString() + "月"
					} else {
						if (m == "zh-tw") {
							V = N.toString() + "月"
						} else {
							if (m == "en-us") {
								V = TimelinePlotter.MonthConvert[N]
							}
						}
					}
				}
				ag.fillStyle = S.getColor(Theme.Color.Text4)
			}
		}
		if (V.length > 0) {
			var Y = ae.toItemCenter(i);
			aa.push({
				x: Y,
				y: U,
				w: 1,
				h: 4
			});
			ag.fillText(V, Y, n)
		}
	}
	if (aa.length > 0) {
		ag.fillStyle = S.getColor(Theme.Color.Grid1);
		Plotter.createRectangles(ag, aa);
		ag.fill()
	}
};
var RangePlotter = create_class(NamedObject);
RangePlotter.prototype.__construct = function(b) {
	RangePlotter.__super.__construct.call(this, b)
};
RangePlotter.prototype.getRequiredWidth = function(e, f) {
	var h = ChartManager.getInstance();
	var g = h.getTheme(this.getFrameName());
	e.font = g.getFont(Theme.Font.Default);
	return e.measureText((Math.floor(f) + 0.88).toString()).width + 16
};
RangePlotter.prototype.Draw = function(B) {
	var n = ChartManager.getInstance();
	var w = this.getAreaName();
	var C = n.getArea(w);
	var r = w.substring(0, w.lastIndexOf("Range"));
	var v = n.getRange(r);
	if (v.getRange() == 0) {
		return
	}
	var u = v.getNameObject().getCompAt(2) == "main";
	if (u) {} else {
		if (!C.isChanged() && !v.isUpdated()) {
			return
		}
	}
	var s = v.getGradations();
	if (s.length == 0) {
		return
	}
	var z = C.getLeft();
	var q = C.getRight();
	var D = C.getCenter();
	var x = n.getTheme(this.getFrameName());
	B.font = x.getFont(Theme.Font.Default);
	B.textAlign = "center";
	B.textBaseline = "middle";
	B.fillStyle = x.getColor(Theme.Color.Text2);
	var y = [];
	for (var A in s) {
		var t = v.toY(s[A]);
		y.push({
			x: z,
			y: t,
			w: 6,
			h: 1
		});
		y.push({
			x: q - 6,
			y: t,
			w: 6,
			h: 1
		});
		B.fillText(String.fromFloat(s[A], 2), D, t)
	}
	if (y.length > 0) {
		B.fillStyle = x.getColor(Theme.Color.Grid1);
		Plotter.createRectangles(B, y);
		B.fill()
	}
};
var COrderGraphPlotter = create_class(NamedObject);
COrderGraphPlotter.prototype.__construct = function(b) {
	COrderGraphPlotter.__super.__construct.call(this, b)
};
COrderGraphPlotter.prototype.Draw = function(b) {
	return this._Draw_(b)
};
COrderGraphPlotter.prototype._Draw_ = function(f) {
	if (this.Update() == false) {
		return
	}
	if (this.updateData() == false) {
		return
	}
	this.m_top = this.m_pArea.getTop();
	this.m_bottom = this.m_pArea.getBottom();
	this.m_left = this.m_pArea.getLeft();
	this.m_right = this.m_pArea.getRight();
	f.save();
	f.rect(this.m_left, this.m_top, this.m_right - this.m_left, this.m_bottom - this.m_top);
	f.clip();
	var h = ChartManager.getInstance().getChart()._depthData;
	this.x_offset = 0;
	this.y_offset = 0;
	var e = {};
	var g = {};
	e.x = this.m_left + h.array[this.m_ask_si].amounts * this.m_Step;
	e.y = this.m_pRange.toY(h.array[this.m_ask_si].rate);
	g.x = this.m_left + h.array[this.m_bid_si].amounts * this.m_Step;
	g.y = this.m_pRange.toY(h.array[this.m_bid_si].rate);
	if (Math.abs(e.y - g.y) < 1) {
		this.y_offset = 1
	}
	this.x_offset = 1;
	this.DrawBackground(f);
	this.UpdatePoints();
	this.FillBlack(f);
	this.DrawGradations(f);
	this.DrawLine(f);
	f.restore()
};
COrderGraphPlotter.prototype.DrawBackground = function(g) {
	g.fillStyle = this.m_pTheme.getColor(Theme.Color.Background);
	g.fillRect(this.m_left, this.m_top, this.m_right - this.m_left, this.m_bottom - this.m_top);
	var l = ChartManager.getInstance().getChart()._depthData;
	if (this.m_mode == 0) {
		var i = this.m_pRange.toY(l.array[this.m_ask_si].rate) - this.y_offset;
		var h = this.m_pRange.toY(l.array[this.m_bid_si].rate) + this.y_offset;
		var j = g.createLinearGradient(this.m_left, 0, this.m_right, 0);
		j.addColorStop(0, this.m_pTheme.getColor(Theme.Color.Background));
		j.addColorStop(1, this.m_pTheme.getColor(Theme.Color.PositiveDark));
		g.fillStyle = j;
		g.fillRect(this.m_left, this.m_top, this.m_right - this.m_left, i - this.m_top);
		var k = g.createLinearGradient(this.m_left, 0, this.m_right, 0);
		k.addColorStop(0, this.m_pTheme.getColor(Theme.Color.Background));
		k.addColorStop(1, this.m_pTheme.getColor(Theme.Color.NegativeDark));
		g.fillStyle = k;
		g.fillRect(this.m_left, h, this.m_right - this.m_left, this.m_bottom - h)
	} else {
		if (this.m_mode == 1) {
			var j = g.createLinearGradient(this.m_left, 0, this.m_right, 0);
			j.addColorStop(0, this.m_pTheme.getColor(Theme.Color.Background));
			j.addColorStop(1, this.m_pTheme.getColor(Theme.Color.PositiveDark));
			g.fillStyle = j;
			g.fillRect(this.m_left, this.m_top, this.m_right - this.m_left, this.m_bottom - this.m_top)
		} else {
			if (this.m_mode == 2) {
				var k = g.createLinearGradient(this.m_left, 0, this.m_right, 0);
				k.addColorStop(0, this.m_pTheme.getColor(Theme.Color.Background));
				k.addColorStop(1, this.m_pTheme.getColor(Theme.Color.NegativeDark));
				g.fillStyle = k;
				g.fillRect(this.m_left, this.m_top, this.m_right - this.m_left, this.m_bottom - this.m_top)
			}
		}
	}
};
COrderGraphPlotter.prototype.DrawLine = function(c) {
	if (this.m_mode == 0 || this.m_mode == 1) {
		c.strokeStyle = this.m_pTheme.getColor(Theme.Color.Positive);
		c.beginPath();
		c.moveTo(Math.floor(this.m_ask_points[0].x) + 0.5, Math.floor(this.m_ask_points[0].y) + 0.5);
		for (var d = 1; d < this.m_ask_points.length; d++) {
			c.lineTo(Math.floor(this.m_ask_points[d].x) + 0.5, Math.floor(this.m_ask_points[d].y) + 0.5)
		}
		c.stroke()
	}
	if (this.m_mode == 0 || this.m_mode == 2) {
		c.strokeStyle = this.m_pTheme.getColor(Theme.Color.Negative);
		c.beginPath();
		c.moveTo(this.m_bid_points[0].x + 0.5, this.m_bid_points[0].y + 0.5);
		for (var d = 1; d < this.m_bid_points.length; d++) {
			c.lineTo(this.m_bid_points[d].x + 0.5, this.m_bid_points[d].y + 0.5)
		}
		c.stroke()
	}
};
COrderGraphPlotter.prototype.UpdatePoints = function() {
	var m = ChartManager.getInstance().getChart()._depthData;
	this.m_ask_points = [];
	var l = {};
	l.x = Math.floor(this.m_left);
	l.y = Math.floor(this.m_pRange.toY(m.array[this.m_ask_si].rate) - this.y_offset);
	this.m_ask_points.push(l);
	var i = 0;
	for (var n = this.m_ask_si; n >= this.m_ask_ei; n--) {
		var j = {};
		var k = {};
		if (n == this.m_ask_si) {
			j.x = Math.floor(this.m_left + m.array[n].amounts * this.m_Step + this.x_offset);
			j.y = Math.floor(this.m_pRange.toY(m.array[n].rate) - this.y_offset);
			this.m_ask_points.push(j);
			i = 1
		} else {
			j.x = Math.floor(this.m_left + m.array[n].amounts * this.m_Step + this.x_offset);
			j.y = Math.floor(this.m_ask_points[i].y);
			k.x = Math.floor(j.x);
			k.y = Math.floor(this.m_pRange.toY(m.array[n].rate) - this.y_offset);
			this.m_ask_points.push(j);
			i++;
			this.m_ask_points.push(k);
			i++
		}
	}
	this.m_bid_points = [];
	var p = {};
	p.x = Math.floor(this.m_left);
	p.y = Math.ceil(this.m_pRange.toY(m.array[this.m_bid_si].rate) + this.y_offset);
	this.m_bid_points.push(p);
	var o = 0;
	for (var n = this.m_bid_si; n <= this.m_bid_ei; n++) {
		var j = {};
		var k = {};
		if (n == this.m_bid_si) {
			j.x = Math.floor(this.m_left + m.array[n].amounts * this.m_Step + this.x_offset);
			j.y = Math.ceil(this.m_pRange.toY(m.array[n].rate) + this.y_offset);
			this.m_bid_points.push(j);
			o = 1
		} else {
			j.x = Math.floor(this.m_left + m.array[n].amounts * this.m_Step + this.x_offset);
			j.y = Math.ceil(this.m_bid_points[o].y);
			k.x = Math.floor(j.x);
			k.y = Math.ceil(this.m_pRange.toY(m.array[n].rate) + this.x_offset);
			this.m_bid_points.push(j);
			o++;
			this.m_bid_points.push(k);
			o++
		}
	}
};
COrderGraphPlotter.prototype.updateData = function() {
	var g = ChartManager.getInstance().getChart()._depthData;
	if (g.array == null) {
		return false
	}
	if (g.array.length <= 50) {
		return false
	}
	var f = this.m_pRange.getOuterMinValue();
	var e = this.m_pRange.getOuterMaxValue();
	this.m_ask_si = g.asks_si;
	this.m_ask_ei = g.asks_si;
	for (var h = g.asks_si; h >= g.asks_ei; h--) {
		if (g.array[h].rate < e) {
			this.m_ask_ei = h
		} else {
			break
		}
	}
	this.m_bid_si = g.bids_si;
	this.m_bid_ei = g.bids_si;
	for (var h = g.bids_si; h <= g.bids_ei; h++) {
		if (g.array[h].rate > f) {
			this.m_bid_ei = h
		} else {
			break
		}
	}
	if (this.m_ask_ei == this.m_ask_si) {
		this.m_mode = 2
	} else {
		if (this.m_bid_ei == this.m_bid_si) {
			this.m_mode = 1
		} else {
			this.m_mode = 0
		}
	}
	this.m_Step = this.m_pArea.getWidth();
	if (this.m_mode == 0) {
		if (this.m_ask_ei == g.asks_ei && this.m_bid_ei == g.bids_ei) {
			this.m_Step /= Math.min(g.array[this.m_ask_ei].amounts, g.array[this.m_bid_ei].amounts)
		} else {
			if (this.m_ask_ei != g.asks_ei && this.m_bid_ei == g.bids_ei) {
				this.m_Step /= g.array[this.m_bid_ei].amounts
			} else {
				if (this.m_ask_ei == g.asks_ei && this.m_bid_ei != g.bids_ei) {
					this.m_Step /= g.array[this.m_ask_ei].amounts
				} else {
					if (this.m_ask_ei != g.asks_ei && this.m_bid_ei != g.bids_ei) {
						this.m_Step /= Math.max(g.array[this.m_ask_ei].amounts, g.array[this.m_bid_ei].amounts)
					}
				}
			}
		}
	} else {
		if (this.m_mode == 1) {
			this.m_Step /= g.array[this.m_ask_ei].amounts
		} else {
			if (this.m_mode == 2) {
				this.m_Step /= g.array[this.m_bid_ei].amounts
			}
		}
	}
	return true
};
COrderGraphPlotter.prototype.Update = function() {
	this.m_pMgr = ChartManager.getInstance();
	var c = this.getAreaName();
	this.m_pArea = this.m_pMgr.getArea(c);
	if (this.m_pArea == null) {
		return false
	}
	var d = c.substring(0, c.lastIndexOf("Range"));
	this.m_pRange = this.m_pMgr.getRange(d);
	if (this.m_pRange == null || this.m_pRange.getRange() == 0) {
		return false
	}
	this.m_pTheme = this.m_pMgr.getTheme(this.getFrameName());
	if (this.m_pTheme == null) {
		return false
	}
	return true
};
COrderGraphPlotter.prototype.DrawGradations = function(y) {
	var n = ChartManager.getInstance();
	var t = this.getAreaName();
	var z = n.getArea(t);
	var p = t.substring(0, t.lastIndexOf("Range"));
	var s = n.getRange(p);
	if (s.getRange() == 0) {
		return
	}
	var q = s.getGradations();
	if (q.length == 0) {
		return
	}
	var w = z.getLeft();
	var o = z.getRight();
	var v = [];
	for (var x in q) {
		var r = s.toY(q[x]);
		v.push({
			x: w,
			y: r,
			w: 6,
			h: 1
		});
		v.push({
			x: o - 6,
			y: r,
			w: 6,
			h: 1
		})
	}
	if (v.length > 0) {
		var u = n.getTheme(this.getFrameName());
		y.fillStyle = u.getColor(Theme.Color.Grid1);
		Plotter.createRectangles(y, v);
		y.fill()
	}
};
COrderGraphPlotter.prototype.FillBlack = function(m) {
	var p = this.m_ask_points;
	var l = this.m_bid_points;
	var j = {};
	var o = {};
	j.x = this.m_right;
	j.y = p[0].y;
	o.x = this.m_right;
	o.y = p[p.length - 1].y;
	var k = {};
	var i = {};
	k.x = this.m_right;
	k.y = l[0].y - 1;
	i.x = this.m_right;
	i.y = l[l.length - 1].y;
	p.unshift(j);
	p.push(o);
	l.unshift(k);
	l.push(i);
	m.fillStyle = this.m_pTheme.getColor(Theme.Color.Background);
	m.beginPath();
	m.moveTo(Math.floor(p[0].x) + 0.5, Math.floor(p[0].y) + 0.5);
	for (var n = 1; n < p.length; n++) {
		m.lineTo(Math.floor(p[n].x) + 0.5, Math.floor(p[n].y) + 0.5)
	}
	m.fill();
	m.beginPath();
	m.moveTo(Math.floor(l[0].x) + 0.5, Math.floor(l[0].y) + 0.5);
	for (var n = 1; n < l.length; n++) {
		m.lineTo(Math.floor(l[n].x) + 0.5, Math.floor(l[n].y) + 0.5)
	}
	m.fill();
	p.shift();
	p.pop();
	l.shift();
	l.pop()
};
COrderGraphPlotter.prototype.DrawTickerGraph = function(r) {
	return;
	var k = ChartManager.getInstance();
	var q = k.getDataSource(this.getDataSourceName());
	var l = q._dataItems[q._dataItems.length - 1].close;
	var s = this.m_left + 1;
	var t = this.m_pRange.toY(l);
	var m = s + 5;
	var n = t + 2.5;
	var o = s + 5;
	var p = t - 2.5;
	r.fillStyle = this.m_pTheme.getColor(Theme.Color.Mark);
	r.strokeStyle = this.m_pTheme.getColor(Theme.Color.Mark)
};
var LastVolumePlotter = create_class(Plotter);
LastVolumePlotter.prototype.__construct = function(b) {
	LastVolumePlotter.__super.__construct.call(this, b)
};
//最高价和最低价
LastVolumePlotter.prototype.Draw = function(w) {
	var n = ChartManager.getInstance();
	var m = n.getTimeline(this.getDataSourceName());
	var s = this.getAreaName();
	var x = n.getArea(s);
	var p = s.substring(0, s.lastIndexOf("Range"));
	var r = n.getRange(p);
	if (r.getRange() == 0) {
		return
	}
	var v = n.getDataSource(this.getDataSourceName());
	if (v.getDataCount() < 1) {
		return
	}
	var t = n.getTheme(this.getFrameName());
	w.font = t.getFont(Theme.Font.Default);
	w.textAlign = "left";
	w.textBaseline = "middle";
	w.fillStyle = t.getColor(Theme.Color.RangeMark);
	w.strokeStyle = t.getColor(Theme.Color.RangeMark);
	var o = v.getDataAt(v.getDataCount() - 1).volume;
	var q = r.toY(o);
	var u = x.getLeft() + 1;
	Plotter.drawLine(w, u, q, u + 7, q);
	Plotter.drawLine(w, u, q, u + 3, q + 2);
	Plotter.drawLine(w, u, q, u + 3, q - 2);
	w.fillText(o, u + 10, q)
};
var LastClosePlotter = create_class(Plotter);
LastClosePlotter.prototype.__construct = function(b) {
	LastClosePlotter.__super.__construct.call(this, b)
};
//右边的价格
LastClosePlotter.prototype.Draw = function(w) {
	var n = ChartManager.getInstance();
	var m = n.getTimeline(this.getDataSourceName());
	var s = this.getAreaName();
	var x = n.getArea(s);
	var p = s.substring(0, s.lastIndexOf("Range"));
	var r = n.getRange(p);
	if (r.getRange() == 0) {
		return
	}
	var v = n.getDataSource(this.getDataSourceName());
	if (v.getDataCount() < 1) {
		return
	}
	var o = v._dataItems[v._dataItems.length - 1].close;
	if (o <= r.getMinValue() || o >= r.getMaxValue()) {
		return
	}
	var t = n.getTheme(this.getFrameName());
	w.font = t.getFont(Theme.Font.Default);
	w.textAlign = "left";
	w.textBaseline = "middle";
	w.fillStyle = t.getColor(Theme.Color.RangeMark);
	w.strokeStyle = t.getColor(Theme.Color.RangeMark);
	var q = r.toY(o);
	var u = x.getLeft() + 1;
	Plotter.drawLine(w, u, q, u + 7, q);
	Plotter.drawLine(w, u, q, u + 3, q + 2);
	Plotter.drawLine(w, u, q, u + 3, q - 2);
	w.fillText(o, u + 10, q)
};
var SelectionPlotter = create_class(Plotter);
SelectionPlotter.prototype.__construct = function(b) {
	SelectionPlotter.__super.__construct.call(this, b)
};
SelectionPlotter.prototype.Draw = function(p) {
	var m = ChartManager.getInstance();
	if (m._drawingTool != ChartManager.DrawingTool.CrossCursor) {
		return
	}
	var n = m.getArea(this.getAreaName());
	var o = m.getTimeline(this.getDataSourceName());
	if (o.getSelectedIndex() < 0) {
		return
	}
	var i = m.getRange(this.getAreaName());
	var l = m.getTheme(this.getFrameName());
	p.strokeStyle = l.getColor(Theme.Color.Cursor);
	var j = o.toItemCenter(o.getSelectedIndex());
	Plotter.drawLine(p, j, n.getTop() - 1, j, n.getBottom());
	var k = i.getSelectedPosition();
	if (k >= 0) {
		Plotter.drawLine(p, n.getLeft(), k, n.getRight(), k)
	}
};
var TimelineSelectionPlotter = create_class(NamedObject);
TimelineSelectionPlotter.MonthConvert = {
	1: "Jan.",
	2: "Feb.",
	3: "Mar.",
	4: "Apr.",
	5: "May.",
	6: "Jun.",
	7: "Jul.",
	8: "Aug.",
	9: "Sep.",
	10: "Oct.",
	11: "Nov.",
	12: "Dec."
};
TimelineSelectionPlotter.prototype.__construct = function(b) {
	TimelineSelectionPlotter.__super.__construct.call(this, b)
};
TimelineSelectionPlotter.prototype.Draw = function(I) {
	var t = ChartManager.getInstance();
	var y = t.getArea(this.getAreaName());
	var H = t.getTimeline(this.getDataSourceName());
	if (H.getSelectedIndex() < 0) {
		return
	}
	var A = t.getDataSource(this.getDataSourceName());
	if (!is_instance(A, MainDataSource)) {
		return
	}
	var x = t.getTheme(this.getFrameName());
	var s = t.getLanguage();
	var D = H.toItemCenter(H.getSelectedIndex());
	I.fillStyle = x.getColor(Theme.Color.Background);
	I.fillRect(D - 52.5, y.getTop() + 2.5, 106, 18);
	I.strokeStyle = x.getColor(Theme.Color.Grid3);
	I.strokeRect(D - 52.5, y.getTop() + 2.5, 106, 18);
	I.font = x.getFont(Theme.Font.Default);
	I.textAlign = "center";
	I.textBaseline = "middle";
	I.fillStyle = x.getColor(Theme.Color.Text4);
	var E = new Date(A.getDataAt(H.getSelectedIndex()).date);
	var v = E.getMonth() + 1;
	var w = E.getDate();
	var F = E.getHours();
	var B = E.getMinutes();
	var J = v.toString();
	var G = w.toString();
	var z = F.toString();
	var u = B.toString();
	if (B < 10) {
		u = "0" + u
	}
	var C = "";
	if (s == "zh-cn") {
		C = J + "月" + G + "日  " + z + ":" + u
	} else {
		if (s == "zh-tw") {
			C = J + "月" + G + "日  " + z + ":" + u
		} else {
			if (s == "en-us") {
				C = TimelineSelectionPlotter.MonthConvert[v] + " " + G + "  " + z + ":" + u
			}
		}
	}
	I.fillText(C, D, y.getMiddle())
};
var RangeSelectionPlotter = create_class(NamedObject);
RangeSelectionPlotter.prototype.__construct = function(b) {
	RangeSelectionPlotter.__super.__construct.call(this, b)
};
RangeSelectionPlotter.prototype.Draw = function(u) {
	var m = ChartManager.getInstance();
	var r = this.getAreaName();
	var v = m.getArea(r);
	var l = m.getTimeline(this.getDataSourceName());
	if (l.getSelectedIndex() < 0) {
		return
	}
	var o = r.substring(0, r.lastIndexOf("Range"));
	var q = m.getRange(o);
	if (q.getRange() == 0 || q.getSelectedPosition() < 0) {
		return
	}
	var n = q.getSelectedValue();
	if (n == -Number.MAX_VALUE) {
		return
	}
	var p = q.getSelectedPosition();
	Plotter.createPolygon(u, [{
		x: v.getLeft(),
		y: p
	}, {
		x: v.getLeft() + 5,
		y: p + 10
	}, {
		x: v.getRight() - 3,
		y: p + 10
	}, {
		x: v.getRight() - 3,
		y: p - 10
	}, {
		x: v.getLeft() + 5,
		y: p - 10
	}]);
	var s = m.getTheme(this.getFrameName());
	u.fillStyle = s.getColor(Theme.Color.Background);
	u.fill();
	u.strokeStyle = s.getColor(Theme.Color.Grid4);
	u.stroke();
	u.font = s.getFont(Theme.Font.Default);
	u.textAlign = "center";
	u.textBaseline = "middle";
	u.fillStyle = s.getColor(Theme.Color.Text3);
	var t = 2;
	if (q.getNameObject().getCompAt(2) == "main") {
		t = m.getDataSource(this.getDataSourceName()).getDecimalDigits()
	}
	u.fillText(String.fromFloat(n, t), v.getCenter(), p)
};
var ChartSettings = {};
ChartSettings.checkVersion = function() {
	if (ChartSettings._data.ver < 2) {
		ChartSettings._data.ver = 2;
		var b = ChartSettings._data.charts;
		b.period_weight = {};
		b.period_weight.line = 8;
		b.period_weight["1min"] = 7;
		b.period_weight["5min"] = 6;
		b.period_weight["15min"] = 5;
		b.period_weight["30min"] = 4;
		b.period_weight["1hour"] = 3;
		b.period_weight["1day"] = 2;
		b.period_weight["1week"] = 1;
		b.period_weight["3min"] = 0;
		b.period_weight["2hour"] = 0;
		b.period_weight["4hour"] = 0;
		b.period_weight["6hour"] = 0;
		b.period_weight["12hour"] = 0;
		b.period_weight["3day"] = 0
	}
	if (ChartSettings._data.ver < 3) {
		ChartSettings._data.ver = 3;
		var b = ChartSettings._data.charts;
		b.areaHeight = []
	}
};
ChartSettings.get = function() {
	if (ChartSettings._data == undefined) {
		ChartSettings.init();
		ChartSettings.load();
		ChartSettings.checkVersion()
	}
	return ChartSettings._data
};
ChartSettings.init = function() {
	var q = {};
	var n = new Array("MA", "EMA", "VOLUME", "MACD", "KDJ", "StochRSI", "RSI", "DMI", "OBV", "BOLL", "DMA", "TRIX", "BRAR", "VR", "EMV", "WR", "ROC", "MTM", "PSY");
	for (var p = 0; p < n.length; p++) {
		var i = ChartManager.getInstance().createIndicatorAndRange("", n[p], true);
		if (i == null) {
			continue
		}
		q[n[p]] = [];
		var u = i.indic.getParameters();
		for (var r = 0; r < u.length; r++) {
			q[n[p]].push(u[r])
		}
	}
	var o = "CandleStick";
	var t = "MA";
	var v = new Array("VOLUME", "MACD");
	var j = "15m";
	var s = {};
	s.chartStyle = o;
	s.mIndic = t;
	s.indics = v;
	s.indicsStatus = "open";
	s.period = j;
	ChartSettings._data = {
		ver: 1,
		charts: s,
		indics: q,
		theme: "Dark"
	};
	ChartSettings.checkVersion()
};
ChartSettings.load = function() {
	if (document.cookie.length <= 0) {
		return
	}
	var f = document.cookie.indexOf("chartSettings=");
	if (f < 0) {
		return
	}
	f += "chartSettings=".length;
	var e = document.cookie.indexOf(";", f);
	if (e < 0) {
		e = document.cookie.length
	}
	var d = unescape(document.cookie.substring(f, e));
	ChartSettings._data = JSON.parse(d)
};
ChartSettings.save = function() {
	var b = new Date();
	b.setDate(b.getDate() + 2);
	document.cookie = "chartSettings=" + escape(JSON.stringify(ChartSettings._data)) + ";expires=" + b.toGMTString()
};
var CPoint = create_class(NamedObject);
CPoint.state = {
	Hide: 0,
	Show: 1,
	Highlight: 2
};
CPoint.prototype.__construct = function(b) {
	CPoint.__super.__construct.call(this, b);
	this.pos = {
		index: -1,
		value: -1
	};
	this.state = CPoint.state.Hide
};
CPoint.prototype.getChartObjects = function() {
	var e = ChartManager.getInstance();
	var g = e.getDataSource("frame0.k0");
	if (g == null || !is_instance(g, MainDataSource)) {
		return null
	}
	var h = e.getTimeline("frame0.k0");
	if (h == null) {
		return null
	}
	var f = e.getRange("frame0.k0.main");
	if (f == null) {
		return null
	}
	return {
		pMgr: e,
		pCDS: g,
		pTimeline: h,
		pRange: f
	}
};
CPoint.prototype.setPosXY = function(g, i) {
	var j = this.getChartObjects();
	var k = j.pTimeline.toIndex(g);
	var l = j.pRange.toValue(i);
	var h = this.snapValue(k, l);
	if (h != null) {
		l = h
	}
	this.setPosIV(k, l)
};
CPoint.prototype.setPosXYNoSnap = function(g, h) {
	var i = this.getChartObjects();
	var j = i.pTimeline.toIndex(g);
	var f = i.pRange.toValue(h);
	this.setPosIV(j, f)
};
CPoint.prototype.setPosIV = function(c, d) {
	this.pos = {
		index: c,
		value: d
	}
};
CPoint.prototype.getPosXY = function() {
	var f = this.getChartObjects();
	var d = f.pTimeline.toItemCenter(this.pos.index);
	var e = f.pRange.toY(this.pos.value);
	return {
		x: d,
		y: e
	}
};
CPoint.prototype.getPosIV = function() {
	return {
		i: this.pos.index,
		v: this.pos.value
	}
};
CPoint.prototype.setState = function(b) {
	this.state = b
};
CPoint.prototype.getState = function() {
	return this.state
};
CPoint.prototype.isSelected = function(e, f) {
	var d = this.getPosXY();
	if (e < d.x - 4 || e > d.x + 4 || f < d.y - 4 || f > d.y + 4) {
		return false
	}
	this.setState(CPoint.state.Highlight);
	return true
};
CPoint.prototype.snapValue = function(w, B) {
	var A = this.getChartObjects();
	var C = null;
	var H = Math.floor(A.pTimeline.getFirstIndex());
	var F = Math.floor(A.pTimeline.getLastIndex());
	if (w < H || w > F) {
		return C
	}
	var E = A.pRange.toY(B);
	var G = A.pCDS.getDataAt(w);
	if (G == null || G == undefined) {
		return C
	}
	var v = null;
	if (w > 0) {
		v = A.pCDS.getDataAt(w - 1)
	} else {
		v = A.pCDS.getDataAt(w)
	}
	var I = A.pMgr.getChartStyle(A.pCDS.getFrameName());
	var z = A.pRange.toY(G.open);
	var D = A.pRange.toY(G.high);
	var t = A.pRange.toY(G.low);
	var y = A.pRange.toY(G.close);
	if (I === "CandleStickHLC") {
		z = A.pRange.toY(v.close)
	}
	var u = Math.abs(z - E);
	var x = Math.abs(D - E);
	var i = Math.abs(t - E);
	var J = Math.abs(y - E);
	if (u <= x && u <= i && u <= J) {
		if (u < 6) {
			C = G.open
		}
	}
	if (x <= u && x <= i && x <= J) {
		if (x < 6) {
			C = G.high
		}
	}
	if (i <= u && i <= x && i <= J) {
		if (i < 6) {
			C = G.low
		}
	}
	if (J <= u && J <= x && J <= i) {
		if (J < 6) {
			C = G.close
		}
	}
	return C
};
var CToolObject = create_class(NamedObject);
CToolObject.state = {
	BeforeDraw: 0,
	Draw: 1,
	AfterDraw: 2
};
CToolObject.prototype.__construct = function(b) {
	CToolObject.__super.__construct.call(this, b);
	this.drawer = null;
	this.state = CToolObject.state.BeforeDraw;
	this.points = [];
	this.step = 0
};
CToolObject.prototype.getChartObjects = function() {
	var j = ChartManager.getInstance();
	var h = j.getDataSource("frame0.k0");
	if (h == null || !is_instance(h, MainDataSource)) {
		return null
	}
	var i = j.getTimeline("frame0.k0");
	if (i == null) {
		return null
	}
	var g = j.getArea("frame0.k0.main");
	if (g == null) {
		return null
	}
	var f = j.getRange("frame0.k0.main");
	if (f == null) {
		return null
	}
	return {
		pMgr: j,
		pCDS: h,
		pTimeline: i,
		pArea: g,
		pRange: f
	}
};
CToolObject.prototype.isValidMouseXY = function(f, g) {
	var e = this.getChartObjects();
	var h = {
		left: e.pArea.getLeft(),
		top: e.pArea.getTop(),
		right: e.pArea.getRight(),
		bottom: e.pArea.getBottom()
	};
	if (f < h.left || f > h.right || g < h.top || g > h.bottom) {
		return false
	}
	return true
};
CToolObject.prototype.getPlotter = function() {
	return this.drawer
};
CToolObject.prototype.setState = function(b) {
	this.state = b
};
CToolObject.prototype.getState = function() {
	return this.state
};
CToolObject.prototype.addPoint = function(b) {
	this.points.push(b)
};
CToolObject.prototype.getPoint = function(b) {
	return this.points[b]
};
CToolObject.prototype.acceptMouseMoveEvent = function(d, c) {
	if (this.isValidMouseXY(d, c) == false) {
		return false
	}
	if (this.state == CToolObject.state.BeforeDraw) {
		this.setBeforeDrawPos(d, c)
	} else {
		if (this.state == CToolObject.state.Draw) {
			this.setDrawPos(d, c)
		} else {
			if (this.state == CToolObject.state.AfterDraw) {
				this.setAfterDrawPos(d, c)
			}
		}
	}
	return true
};
CToolObject.prototype.acceptMouseDownEvent = function(d, c) {
	if (this.isValidMouseXY(d, c) == false) {
		return false
	}
	if (this.state == CToolObject.state.BeforeDraw) {
		this.setDrawPos(d, c);
		this.setState(CToolObject.state.Draw)
	} else {
		if (this.state == CToolObject.state.Draw) {
			this.setAfterDrawPos(d, c);
			if (this.step == 0) {
				this.setState(CToolObject.state.AfterDraw)
			}
		} else {
			if (this.state == CToolObject.state.AfterDraw) {
				if (CToolObject.prototype.isSelected.call(this, d, c)) {
					this.setDrawPos(d, c);
					this.setState(CToolObject.state.Draw)
				} else {
					this.oldx = d;
					this.oldy = c
				}
			}
		}
	}
	return true
};
CToolObject.prototype.acceptMouseDownMoveEvent = function(r, t) {
	if (this.isValidMouseXY(r, t) == false) {
		return false
	}
	if (this.state == CToolObject.state.Draw) {
		this.setDrawPos(r, t)
	} else {
		if (this.state == CToolObject.state.AfterDraw) {
			var x = this.getChartObjects();
			var w = x.pTimeline.getItemWidth();
			var v = x.pRange;
			if (Math.abs(r - this.oldx) < w && Math.abs(t - this.oldy) == 0) {
				return true
			}
			var p = x.pTimeline.toIndex(this.oldx);
			var s = x.pRange.toValue(this.oldy);
			var o = x.pTimeline.toIndex(r);
			var q = x.pRange.toValue(t);
			this.oldx = r;
			this.oldy = t;
			var m = o - p;
			var n = q - s;
			for (var u in this.points) {
				this.points[u].pos.index += m;
				this.points[u].pos.value += n
			}
		}
	}
	return true
};
CToolObject.prototype.acceptMouseUpEvent = function(d, c) {
	if (this.isValidMouseXY(d, c) == false) {
		return false
	}
	if (this.state == CToolObject.state.Draw) {
		this.setAfterDrawPos(d, c);
		if (this.step == 0) {
			this.setState(CToolObject.state.AfterDraw)
		}
		return true
	}
	return false
};
CToolObject.prototype.setBeforeDrawPos = function(e, f) {
	for (var d in this.points) {
		this.points[d].setPosXY(e, f);
		this.points[d].setState(CPoint.state.Show)
	}
};
CToolObject.prototype.setDrawPos = function(e, f) {
	for (var d in this.points) {
		if (this.points[d].getState() == CPoint.state.Highlight) {
			this.points[d].setPosXY(e, f)
		}
	}
};
CToolObject.prototype.setAfterDrawPos = function(f, g) {
	if (this.step != 0) {
		this.step -= 1
	}
	for (var e in this.points) {
		this.points[e].setState(CPoint.state.Hide)
	}
	if (this.step == 0) {
		var h = this.getChartObjects();
		h.pMgr.setNormalMode()
	}
};
CToolObject.prototype.isSelected = function(f, g) {
	var h = false;
	for (var e in this.points) {
		if (this.points[e].isSelected(f, g)) {
			this.points[e].setState(CPoint.state.Highlight);
			h = true;
			break
		}
	}
	if (h == true) {
		this.select();
		return true
	}
	return false
};
CToolObject.prototype.select = function() {
	for (var b in this.points) {
		if (this.points[b].getState() == CPoint.state.Hide) {
			this.points[b].setState(CPoint.state.Show)
		}
	}
};
CToolObject.prototype.unselect = function() {
	for (var b in this.points) {
		if (this.points[b].getState() != CPoint.state.Hide) {
			this.points[b].setState(CPoint.state.Hide)
		}
	}
};
CToolObject.prototype.calcDistance = function(s, v, x) {
	var D = s.getPosXY().x;
	var u = s.getPosXY().y;
	var p = v.getPosXY().x;
	var w = v.getPosXY().y;
	var q = x.getPosXY().x;
	var y = x.getPosXY().y;
	var A = D - q;
	var C = u - y;
	var r = p - q;
	var t = w - y;
	var B = Math.abs(A * t - C * r);
	var z = Math.sqrt(Math.pow((p - D), 2) + Math.pow((w - u), 2));
	return B / z
};
CToolObject.prototype.calcGap = function(C, r, t) {
	var D = C.sx;
	var v = C.sy;
	var p = C.ex;
	var w = C.ey;
	var q = r;
	var x = t;
	var z = D - q;
	var B = v - x;
	var s = p - q;
	var u = w - x;
	var A = Math.abs(z * u - B * s);
	var y = Math.sqrt(Math.pow((p - D), 2) + Math.pow((w - v), 2));
	return A / y
};
CToolObject.prototype.isWithRect = function(l, o, p) {
	var k = l.getPosXY().x;
	var m = l.getPosXY().y;
	var q = o.getPosXY().x;
	var r = o.getPosXY().y;
	var j = p.getPosXY().x;
	var n = p.getPosXY().y;
	if (k > q) {
		k += 4;
		q -= 4
	} else {
		k -= 4;
		q += 4
	}
	if (m > r) {
		m += 4;
		r -= 4
	} else {
		m -= 4;
		r += 4
	}
	if (k <= j && q >= j && m <= n && r >= n) {
		return true
	}
	if (k >= j && q <= j && m <= n && r >= n) {
		return true
	}
	if (k <= j && q >= j && m >= n && r <= n) {
		return true
	}
	if (k >= j && q <= j && m >= n && r <= n) {
		return true
	}
	return false
};
CBiToolObject = create_class(CToolObject);
CBiToolObject.prototype.__construct = function(b) {
	CBiToolObject.__super.__construct.call(this, b);
	this.addPoint(new CPoint(b));
	this.addPoint(new CPoint(b))
};
CBiToolObject.prototype.setBeforeDrawPos = function(d, c) {
	this.step = 1;
	CBiToolObject.__super.setBeforeDrawPos.call(this, d, c);
	this.getPoint(0).setState(CPoint.state.Show);
	this.getPoint(1).setState(CPoint.state.Highlight)
};
CTriToolObject = create_class(CToolObject);
CTriToolObject.prototype.__construct = function(b) {
	CTriToolObject.__super.__construct.call(this, b);
	this.addPoint(new CPoint(b));
	this.addPoint(new CPoint(b));
	this.addPoint(new CPoint(b))
};
CTriToolObject.prototype.setBeforeDrawPos = function(d, c) {
	this.step = 2;
	CBiToolObject.__super.setBeforeDrawPos.call(this, d, c);
	this.getPoint(0).setState(CPoint.state.Show);
	this.getPoint(1).setState(CPoint.state.Show);
	this.getPoint(2).setState(CPoint.state.Highlight)
};
CTriToolObject.prototype.setAfterDrawPos = function(f, g) {
	if (this.step != 0) {
		this.step -= 1
	}
	if (this.step == 0) {
		for (var e in this.points) {
			this.points[e].setState(CPoint.state.Hide)
		}
	} else {
		this.getPoint(0).setState(CPoint.state.Show);
		this.getPoint(1).setState(CPoint.state.Highlight);
		this.getPoint(2).setState(CPoint.state.Show)
	}
	if (this.step == 0) {
		var h = this.getChartObjects();
		h.pMgr.setNormalMode()
	}
};
var CBandLineObject = create_class(CBiToolObject);
CBandLineObject.prototype.__construct = function(b) {
	CBandLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawBandLinesPlotter(b, this)
};
CBandLineObject.prototype.isSelected = function(c, n) {
	if (CBandLineObject.__super.isSelected.call(this, c, n) == true) {
		return true
	}
	var o = new CPoint("frame0.k0");
	o.setPosXY(c, n);
	var i = this.getPoint(0).getPosXY().x;
	var m = this.getPoint(0).getPosXY().y;
	var p = this.getPoint(1).getPosXY().x;
	var q = this.getPoint(1).getPosXY().y;
	var t = [100, 87.5, 75, 62.5, 50, 37.5, 25, 12.5, 0];
	for (var r = 0; r < t.length; r++) {
		var s = m + (100 - t[r]) / 100 * (q - m);
		if (s < n + 4 && s > n - 4) {
			this.select();
			return true
		}
	}
	return false
};
var CBiParallelLineObject = create_class(CTriToolObject);
CBiParallelLineObject.prototype.__construct = function(b) {
	CBiParallelLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawBiParallelLinesPlotter(b, this)
};
CBiParallelLineObject.prototype.isSelected = function(q, s) {
	if (CTriParallelLineObject.__super.isSelected.call(this, q, s) == true) {
		return true
	}
	var r = this.getPoint(0).getPosXY().x;
	var u = this.getPoint(0).getPosXY().y;
	var z = this.getPoint(1).getPosXY().x;
	var A = this.getPoint(1).getPosXY().y;
	var D = this.getPoint(2).getPosXY().x;
	var p = this.getPoint(2).getPosXY().y;
	var t = {
		x: r - z,
		y: u - A
	};
	var v = {
		x: r - D,
		y: u - p
	};
	var x = {
		x: t.x + v.x,
		y: t.y + v.y
	};
	var w = r - x.x;
	var y = u - x.y;
	var B = {
		sx: r,
		sy: u,
		ex: D,
		ey: p
	};
	var C = {
		sx: z,
		sy: A,
		ex: w,
		ey: y
	};
	if (this.calcGap(B, q, s) > 4 && this.calcGap(C, q, s) > 4) {
		return false
	}
	return true
};
var CBiParallelRayLineObject = create_class(CTriToolObject);
CBiParallelRayLineObject.prototype.__construct = function(b) {
	CBiParallelRayLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawBiParallelRayLinesPlotter(b, this)
};
CBiParallelRayLineObject.prototype.isSelected = function(q, s) {
	if (CTriParallelLineObject.__super.isSelected.call(this, q, s) == true) {
		return true
	}
	var r = this.getPoint(0).getPosXY().x;
	var u = this.getPoint(0).getPosXY().y;
	var z = this.getPoint(1).getPosXY().x;
	var A = this.getPoint(1).getPosXY().y;
	var D = this.getPoint(2).getPosXY().x;
	var p = this.getPoint(2).getPosXY().y;
	var t = {
		x: r - z,
		y: u - A
	};
	var v = {
		x: r - D,
		y: u - p
	};
	var x = {
		x: t.x + v.x,
		y: t.y + v.y
	};
	var w = r - x.x;
	var y = u - x.y;
	var B = {
		sx: r,
		sy: u,
		ex: D,
		ey: p
	};
	var C = {
		sx: z,
		sy: A,
		ex: w,
		ey: y
	};
	if ((B.ex > B.sx && q > B.sx - 4) || (B.ex < B.sx && q < B.sx + 4) || (C.ex > C.sx && q > C.sx - 4) || (C.ex < C.sx && q < C.sx + 4)) {
		if (this.calcGap(B, q, s) > 4 && this.calcGap(C, q, s) > 4) {
			return false
		}
	} else {
		return false
	}
	this.select();
	return true
};
var CFibFansObject = create_class(CBiToolObject);
CFibFansObject.prototype.__construct = function(b) {
	CFibFansObject.__super.__construct.call(this, b);
	this.drawer = new DrawFibFansPlotter(b, this)
};
CFibFansObject.prototype.isSelected = function(J, K) {
	if (CFibFansObject.__super.isSelected.call(this, J, K) == true) {
		return true
	}
	var y = new CPoint("frame0.k0");
	y.setPosXY(J, K);
	var E = this.getPoint(0).getPosXY().x;
	var F = this.getPoint(0).getPosXY().y;
	var A = this.getPoint(1).getPosXY().x;
	var B = this.getPoint(1).getPosXY().y;
	var I = this.getChartObjects();
	var N = {
		left: I.pArea.getLeft(),
		top: I.pArea.getTop(),
		right: I.pArea.getRight(),
		bottom: I.pArea.getBottom()
	};
	var L = [0, 38.2, 50, 61.8];
	for (var C = 0; C < L.length; C++) {
		var D = F + (100 - L[C]) / 100 * (B - F);
		var G = {
			x: E,
			y: F
		};
		var a = {
			x: A,
			y: D
		};
		var M = getRectCrossPt(N, G, a);
		var b = Math.pow((M[0].x - E), 2) + Math.pow((M[0].y - F), 2);
		var c = Math.pow((M[0].x - A), 2) + Math.pow((M[0].y - B), 2);
		var H = b > c ? {
			x: M[0].x,
			y: M[0].y
		} : {
			x: M[1].x,
			y: M[1].y
		};
		if (H.x > E && J < E) {
			continue
		}
		if (H.x < E && J > E) {
			continue
		}
		var i = new CPoint("frame0.k0");
		i.setPosXY(E, F);
		var x = new CPoint("frame0.k0");
		x.setPosXY(H.x, H.y);
		if (this.calcDistance(i, x, y) > 4) {
			continue
		}
		this.select();
		return true
	}
	return false
};
var CFibRetraceObject = create_class(CBiToolObject);
CFibRetraceObject.prototype.__construct = function(b) {
	CFibRetraceObject.__super.__construct.call(this, b);
	this.drawer = new DrawFibRetracePlotter(b, this)
};
CFibRetraceObject.prototype.isSelected = function(c, n) {
	if (CFibRetraceObject.__super.isSelected.call(this, c, n) == true) {
		return true
	}
	var o = new CPoint("frame0.k0");
	o.setPosXY(c, n);
	var i = this.getPoint(0).getPosXY().x;
	var m = this.getPoint(0).getPosXY().y;
	var p = this.getPoint(1).getPosXY().x;
	var q = this.getPoint(1).getPosXY().y;
	var t = [100, 78.6, 61.8, 50, 38.2, 23.6, 0];
	for (var r = 0; r < t.length; r++) {
		var s = m + (100 - t[r]) / 100 * (q - m);
		if (s < n + 4 && s > n - 4) {
			this.select();
			return true
		}
	}
	return false
};
var CHoriRayLineObject = create_class(CBiToolObject);
CHoriRayLineObject.prototype.__construct = function(b) {
	CHoriRayLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawHoriRayLinesPlotter(b, this)
};
CHoriRayLineObject.prototype.setDrawPos = function(d, c) {
	if (this.points[0].getState() == CPoint.state.Highlight) {
		this.points[0].setPosXY(d, c);
		this.points[1].setPosXYNoSnap(this.points[1].getPosXY().x, this.points[0].getPosXY().y);
		return
	}
	if (this.points[1].getState() == CPoint.state.Highlight) {
		this.points[1].setPosXY(d, c);
		this.points[0].setPosXYNoSnap(this.points[0].getPosXY().x, this.points[1].getPosXY().y)
	}
};
CHoriRayLineObject.prototype.isSelected = function(h, i) {
	if (CHoriRayLineObject.__super.isSelected.call(this, h, i) == true) {
		return true
	}
	var j = new CPoint("frame0.k0");
	j.setPosXY(h, i);
	var l = this.getPoint(0).getPosXY().y;
	var k = this.getPoint(0).getPosXY().x;
	var c = this.getPoint(1).getPosXY().x;
	if (i > l + 4 || i < l - 4) {
		return false
	}
	if (c > k && h < k - 4) {
		return false
	}
	if (c < k && h > k + 4) {
		return false
	}
	this.select();
	return true
};
var CHoriSegLineObject = create_class(CBiToolObject);
CHoriSegLineObject.prototype.__construct = function(b) {
	CHoriSegLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawHoriSegLinesPlotter(b, this)
};
CHoriSegLineObject.prototype.setDrawPos = function(d, c) {
	if (this.points[0].getState() == CPoint.state.Highlight) {
		this.points[0].setPosXY(d, c);
		this.points[1].setPosXYNoSnap(this.points[1].getPosXY().x, this.points[0].getPosXY().y);
		return
	}
	if (this.points[1].getState() == CPoint.state.Highlight) {
		this.points[1].setPosXY(d, c);
		this.points[0].setPosXYNoSnap(this.points[0].getPosXY().x, this.points[1].getPosXY().y)
	}
};
CHoriSegLineObject.prototype.isSelected = function(h, i) {
	if (CHoriSegLineObject.__super.isSelected.call(this, h, i) == true) {
		return true
	}
	var j = new CPoint("frame0.k0");
	j.setPosXY(h, i);
	var l = this.getPoint(0).getPosXY().y;
	var k = this.getPoint(0).getPosXY().x;
	var c = this.getPoint(1).getPosXY().x;
	if (i > l + 4 || i < l - 4) {
		return false
	}
	if (k > c && (h > k + 4 || h < c - 4)) {
		return false
	}
	if (k < c && (h < k - 4 || h > c + 4)) {
		return false
	}
	this.select();
	return true
};
var CHoriStraightLineObject = create_class(CBiToolObject);
CHoriStraightLineObject.prototype.__construct = function(b) {
	CHoriStraightLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawHoriStraightLinesPlotter(b, this)
};
CHoriStraightLineObject.prototype.setDrawPos = function(e, f) {
	for (var d in this.points) {
		this.points[d].setPosXY(e, f)
	}
};
CHoriStraightLineObject.prototype.isSelected = function(f, g) {
	if (CHoriStraightLineObject.__super.isSelected.call(this, f, g) == true) {
		return true
	}
	var h = new CPoint("frame0.k0");
	h.setPosXY(f, g);
	var c = this.getPoint(0).getPosXY().y;
	if (g > c + 4 || g < c - 4) {
		return false
	}
	this.select();
	return true
};
var CRayLineObject = create_class(CBiToolObject);
CRayLineObject.prototype.__construct = function(b) {
	CRayLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawRayLinesPlotter(b, this)
};
CRayLineObject.prototype.isSelected = function(g, h) {
	if (CRayLineObject.__super.isSelected.call(this, g, h) == true) {
		return true
	}
	var i = new CPoint("frame0.k0");
	i.setPosXY(g, h);
	var j = this.getPoint(0).getPosXY().x;
	var c = this.getPoint(1).getPosXY().x;
	if (c > j && g < j - 4) {
		return false
	}
	if (c < j && g > j + 4) {
		return false
	}
	if (this.calcDistance(this.getPoint(0), this.getPoint(1), i) < 4) {
		this.select();
		return true
	}
	return false
};
var CSegLineObject = create_class(CBiToolObject);
CSegLineObject.prototype.__construct = function(b) {
	CSegLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawSegLinesPlotter(b, this)
};
CSegLineObject.prototype.isSelected = function(e, f) {
	if (CSegLineObject.__super.isSelected.call(this, e, f) == true) {
		return true
	}
	var c = new CPoint("frame0.k0");
	c.setPosXY(e, f);
	if (this.isWithRect(this.getPoint(0), this.getPoint(1), c) == false) {
		return false
	}
	if (this.calcDistance(this.getPoint(0), this.getPoint(1), c) < 4) {
		this.select();
		return true
	}
	return false
};
var CStraightLineObject = create_class(CBiToolObject);
CStraightLineObject.prototype.__construct = function(b) {
	CStraightLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawStraightLinesPlotter(b, this)
};
CStraightLineObject.prototype.isSelected = function(e, f) {
	if (CStraightLineObject.__super.isSelected.call(this, e, f) == true) {
		return true
	}
	var c = new CPoint("frame0.k0");
	c.setPosXY(e, f);
	if (this.calcDistance(this.getPoint(0), this.getPoint(1), c) < 4) {
		this.select();
		return true
	}
	return false
};
var CTriParallelLineObject = create_class(CTriToolObject);
CTriParallelLineObject.prototype.__construct = function(b) {
	CTriParallelLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawTriParallelLinesPlotter(b, this)
};
CTriParallelLineObject.prototype.isSelected = function(I, J) {
	if (CTriParallelLineObject.__super.isSelected.call(this, I, J) == true) {
		return true
	}
	var G = this.getChartObjects();
	var M = this.getPoint(0).getPosXY().x;
	var N = this.getPoint(0).getPosXY().y;
	var D = this.getPoint(1).getPosXY().x;
	var F = this.getPoint(1).getPosXY().y;
	var W = this.getPoint(2).getPosXY().x;
	var H = this.getPoint(2).getPosXY().y;
	var O = {
		x: M - D,
		y: N - F
	};
	var P = {
		x: M - W,
		y: N - H
	};
	var Q = {
		x: O.x + P.x,
		y: O.y + P.y
	};
	var K = M - Q.x;
	var L = N - Q.y;
	var x = {
		sx: M,
		sy: N,
		ex: W,
		ey: H
	};
	var y = {
		sx: D,
		sy: F,
		ex: K,
		ey: L
	};
	var S = {
		x: M - D,
		y: N - F
	};
	var U = {
		x: W - K,
		y: H - L
	};
	var R = {
		x: D - M,
		y: F - N
	};
	var T = {
		x: K - W,
		y: L - H
	};
	var C = Math.abs(R.x - M);
	var E = Math.abs(R.y - N);
	var V = Math.abs(T.x - W);
	var X = Math.abs(T.y - H);
	var B = {
		sx: C,
		sy: E,
		ex: V,
		ey: X
	};
	if (this.calcGap(x, I, J) > 4 && this.calcGap(y, I, J) > 4 && this.calcGap(B, I, J) > 4) {
		return false
	}
	this.select();
	return true
};
var CVertiStraightLineObject = create_class(CBiToolObject);
CVertiStraightLineObject.prototype.__construct = function(b) {
	CVertiStraightLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawVertiStraightLinesPlotter(b, this)
};
CVertiStraightLineObject.prototype.setDrawPos = function(e, f) {
	for (var d in this.points) {
		this.points[d].setPosXY(e, f)
	}
};
CVertiStraightLineObject.prototype.isSelected = function(f, g) {
	if (CVertiStraightLineObject.__super.isSelected.call(this, f, g) == true) {
		return true
	}
	var h = new CPoint("frame0.k0");
	h.setPosXY(f, g);
	var c = this.getPoint(0).getPosXY().x;
	if (f > c + 4 || f < c - 4) {
		return false
	}
	this.select();
	return true
};
var CPriceLineObject = create_class(CSegLineObject);
CPriceLineObject.prototype.__construct = function(b) {
	CPriceLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawPriceLinesPlotter(b, this)
};
CPriceLineObject.prototype.setDrawPos = function(e, f) {
	for (var d in this.points) {
		this.points[d].setPosXY(e, f)
	}
};
CPriceLineObject.prototype.isSelected = function(i, j) {
	if (CFibRetraceObject.__super.isSelected.call(this, i, j) == true) {
		return true
	}
	var k = new CPoint("frame0.k0");
	k.setPosXY(i, j);
	var l = this.getPoint(0).getPosXY().x;
	var m = this.getPoint(0).getPosXY().y;
	var n = this.getPoint(1).getPosXY().x;
	var c = this.getPoint(1).getPosXY().y;
	if (i < l - 4) {
		return false
	}
	if (j >= m + 4 || j <= m - 4) {
		return false
	}
	this.select();
	return true
};
var CArrowLineObject = create_class(CSegLineObject);
CArrowLineObject.prototype.__construct = function(b) {
	CArrowLineObject.__super.__construct.call(this, b);
	this.drawer = new DrawArrowLinesPlotter(b, this)
};
var CToolManager = create_class(NamedObject);
CToolManager.prototype.__construct = function(b) {
	CToolManager.__super.__construct.call(this, b);
	this.selectedObject = -1;
	this.toolObjects = []
};
CToolManager.prototype.getToolObjectCount = function() {
	return this.toolObjects.length
};
CToolManager.prototype.addToolObject = function(b) {
	this.toolObjects.push(b)
};
CToolManager.prototype.getToolObject = function(b) {
	if (b < this.toolObjects.length && b >= 0) {
		return this.toolObjects[b]
	}
	return null
};
CToolManager.prototype.getCurrentObject = function() {
	return this.getToolObject(this.getToolObjectCount() - 1)
};
CToolManager.prototype.getSelectedObject = function() {
	return this.getToolObject(this.selectedObject)
};
CToolManager.prototype.delCurrentObject = function() {
	this.toolObjects.splice(this.getToolObjectCount() - 1, 1)
};
CToolManager.prototype.delSelectedObject = function() {
	this.toolObjects.splice(this.selectedObject, 1);
	this.selectedObject = -1
};
CToolManager.prototype.acceptMouseMoveEvent = function(g, h) {
	if (this.selectedObject == -1) {
		var i = this.toolObjects[this.getToolObjectCount() - 1];
		if (i != null && i.getState() != CToolObject.state.AfterDraw) {
			return i.acceptMouseMoveEvent(g, h)
		}
	} else {
		var j = this.toolObjects[this.selectedObject];
		if (j.getState() == CToolObject.state.Draw) {
			return j.acceptMouseMoveEvent(g, h)
		}
		j.unselect();
		this.selectedObject = -1
	}
	for (var f in this.toolObjects) {
		if (this.toolObjects[f].isSelected(g, h)) {
			this.selectedObject = f;
			return false
		}
	}
	return false
};
CToolManager.prototype.acceptMouseDownEvent = function(f, g) {
	this.mouseDownMove = false;
	if (this.selectedObject == -1) {
		var h = this.toolObjects[this.getToolObjectCount() - 1];
		if (h != null && h.getState() != CToolObject.state.AfterDraw) {
			return h.acceptMouseDownEvent(f, g)
		}
	} else {
		var e = this.toolObjects[this.selectedObject];
		if (e.getState() != CToolObject.state.BeforeDraw) {
			return e.acceptMouseDownEvent(f, g)
		}
	}
	return false
};
CToolManager.prototype.acceptMouseDownMoveEvent = function(g, i) {
	this.mouseDownMove = true;
	if (this.selectedObject == -1) {
		var j = this.toolObjects[this.getToolObjectCount() - 1];
		if (j != null && j.getState() == CToolObject.state.Draw) {
			return j.acceptMouseDownMoveEvent(g, i)
		}
		return false
	} else {
		var k = this.toolObjects[this.selectedObject];
		if (k.getState() != CToolObject.state.BeforeDraw) {
			if (k.acceptMouseDownMoveEvent(g, i) == true) {
				var h = this.toolObjects[this.selectedObject].points;
				for (var l = 0; l < h.length; l++) {
					if (h[l].state == CPoint.state.Highlight || h[l].state == CPoint.state.Show) {
						return true
					}
				}
			}
			return true
		}
	}
};
CToolManager.prototype.acceptMouseUpEvent = function(f, g) {
	if (this.mouseDownMove == true) {
		if (this.selectedObject == -1) {
			var h = this.toolObjects[this.getToolObjectCount() - 1];
			if (h != null && h.getState() == CToolObject.state.Draw) {
				return h.acceptMouseUpEvent(f, g)
			}
			return true
		} else {
			var e = this.toolObjects[this.selectedObject];
			if (e.getState() != CToolObject.state.BeforeDraw) {
				return e.acceptMouseUpEvent(f, g)
			}
		}
	}
	if (this.selectedObject != -1) {
		return true
	}
	var h = this.toolObjects[this.getToolObjectCount() - 1];
	if (h != null) {
		if (h.getState() == CToolObject.state.Draw) {
			return true
		}
		if (!h.isValidMouseXY(f, g)) {
			return false
		}
		if (h.isSelected(f, g)) {
			return true
		}
	}
	return false
};
var CToolPlotter = create_class(NamedObject);
CToolPlotter.prototype.__construct = function(f, g) {
	CToolPlotter.__super.__construct.call(this, f);
	this.toolObject = g;
	var h = ChartManager.getInstance();
	var e = h.getArea("frame0.k0.main");
	if (e == null) {
		this.areaPos = {
			left: 0,
			top: 0,
			right: 0,
			bottom: 0
		};
		return
	}
	this.areaPos = {
		left: e.getLeft(),
		top: e.getTop(),
		right: e.getRight(),
		bottom: e.getBottom()
	};
	this.crossPt = {};
	this.normalSize = 4;
	this.selectedSize = 6;
	this.cursorLen = 4;
	this.cursorGapLen = 3;
	this.theme = ChartManager.getInstance().getTheme(this.getFrameName())
};
CToolPlotter.prototype.drawCursor = function(b) {
	this.drawCrossCursor(b)
};
CToolPlotter.prototype.drawCrossCursor = function(j) {
	j.strokeStyle = this.theme.getColor(Theme.Color.LineColorNormal);
	j.fillStyle = this.theme.getColor(Theme.Color.LineColorNormal);
	var k = this.toolObject.getPoint(0).getPosXY();
	if (k == null) {
		return
	}
	var l = k.x;
	var i = k.y;
	var g = this.cursorLen;
	var h = this.cursorGapLen;
	j.fillRect(l, i, 1, 1);
	Plotter.drawLine(j, l - g - h, i, l - h, i);
	Plotter.drawLine(j, l + g + h, i, l + h, i);
	Plotter.drawLine(j, l, i - g - h, l, i - h);
	Plotter.drawLine(j, l, i + g + h, l, i + h)
};
CToolPlotter.prototype.drawCircle = function(j, f, g) {
	var h = f.x;
	var i = f.y;
	j.beginPath();
	j.arc(h, i, g, 0, 2 * Math.PI, false);
	j.fillStyle = this.theme.getColor(Theme.Color.CircleColorFill);
	j.fill();
	j.stroke()
};
CToolPlotter.prototype.drawCtrlPt = function(c) {
	c.strokeStyle = this.theme.getColor(Theme.Color.CircleColorStroke);
	for (var d = 0; d < this.ctrlPtsNum; d++) {
		this.drawCircle(c, this.ctrlPts[1][d], this.normalSize)
	}
};
CToolPlotter.prototype.highlightCtrlPt = function(c) {
	c.strokeStyle = this.theme.getColor(Theme.Color.CircleColorStroke);
	for (var d = 0; d < this.ctrlPtsNum; d++) {
		if (this.toolObject.getPoint(d).getState() == CPoint.state.Highlight) {
			this.drawCircle(c, this.ctrlPts[1][d], this.selectedSize)
		}
	}
};
CToolPlotter.prototype.drawFibRayLines = function(m, j, n) {
	for (var h = 0; h < this.fiboFansSequence.length; h++) {
		var i = j.y + (100 - this.fiboFansSequence[h]) / 100 * (n.y - j.y);
		var k = {
			x: j.x,
			y: j.y
		};
		var l = {
			x: n.x,
			y: i
		};
		this.drawRayLines(m, k, l)
	}
};
CToolPlotter.prototype.drawRayLines = function(n, j, h) {
	this.getAreaPos();
	var l = {
		x: j.x,
		y: j.y
	};
	var m = {
		x: h.x,
		y: h.y
	};
	var k = getRectCrossPt(this.areaPos, l, m);
	var i;
	if (h.x == j.x) {
		if (h.y == j.y) {
			i = h
		} else {
			i = h.y > j.y ? {
				x: k[1].x,
				y: k[1].y
			} : {
				x: k[0].x,
				y: k[0].y
			}
		}
	} else {
		i = h.x > j.x ? {
			x: k[1].x,
			y: k[1].y
		} : {
			x: k[0].x,
			y: k[0].y
		}
	}
	Plotter.drawLine(n, j.x, j.y, i.x, i.y)
};
CToolPlotter.prototype.lenBetweenPts = function(c, d) {
	return Math.sqrt(Math.pow((d.x - c.x), 2) + Math.pow((d.y - c.y), 2))
};
CToolPlotter.prototype.getCtrlPts = function() {
	for (var b = 0; b < this.ctrlPtsNum; b++) {
		this.ctrlPts[0][b] = this.toolObject.getPoint(b)
	}
};
CToolPlotter.prototype.updateCtrlPtPos = function() {
	for (var b = 0; b < this.ctrlPtsNum; b++) {
		this.ctrlPts[1][b] = this.ctrlPts[0][b].getPosXY()
	}
};
CToolPlotter.prototype.getAreaPos = function() {
	var c = ChartManager.getInstance();
	var d = c.getArea("frame0.k0.main");
	if (d == null) {
		this.areaPos = {
			left: 0,
			top: 0,
			right: 0,
			bottom: 0
		};
		return
	}
	this.areaPos = {
		left: Math.floor(d.getLeft()),
		top: Math.floor(d.getTop()),
		right: Math.floor(d.getRight()),
		bottom: Math.floor(d.getBottom())
	}
};
CToolPlotter.prototype.updateDraw = function(b) {
	b.strokeStyle = this.theme.getColor(Theme.Color.LineColorNormal);
	this.draw(b);
	this.drawCtrlPt(b)
};
CToolPlotter.prototype.finishDraw = function(b) {
	b.strokeStyle = this.theme.getColor(Theme.Color.LineColorNormal);
	this.draw(b)
};
CToolPlotter.prototype.highlight = function(b) {
	b.strokeStyle = this.theme.getColor(Theme.Color.LineColorSelected);
	this.draw(b);
	this.drawCtrlPt(b);
	this.highlightCtrlPt(b)
};
var DrawStraightLinesPlotter = create_class(CToolPlotter);
DrawStraightLinesPlotter.prototype.__construct = function(d, c) {
	DrawStraightLinesPlotter.__super.__construct.call(this, d, c);
	this.toolObject = c;
	this.ctrlPtsNum = 2;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawStraightLinesPlotter.prototype.draw = function(b) {
	this.updateCtrlPtPos();
	this.getAreaPos();
	this.startPoint = this.ctrlPts[1][0];
	this.endPoint = this.ctrlPts[1][1];
	if (this.startPoint.x == this.endPoint.x && this.startPoint.y == this.endPoint.y) {
		Plotter.drawLine(b, this.areaPos.left, this.startPoint.y, this.areaPos.right, this.startPoint.y)
	} else {
		this.crossPt = getRectCrossPt(this.areaPos, this.startPoint, this.endPoint);
		Plotter.drawLine(b, this.crossPt[0].x, this.crossPt[0].y, this.crossPt[1].x, this.crossPt[1].y)
	}
};
var DrawSegLinesPlotter = create_class(CToolPlotter);
DrawSegLinesPlotter.prototype.__construct = function(d, c) {
	DrawSegLinesPlotter.__super.__construct.call(this, d, c);
	this.toolObject = c;
	this.ctrlPtsNum = 2;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawSegLinesPlotter.prototype.draw = function(b) {
	this.updateCtrlPtPos();
	this.startPoint = this.ctrlPts[1][0];
	this.endPoint = this.ctrlPts[1][1];
	if (this.startPoint.x == this.endPoint.x && this.startPoint.y == this.endPoint.y) {
		this.endPoint.x += 1
	}
	Plotter.drawLine(b, this.startPoint.x, this.startPoint.y, this.endPoint.x, this.endPoint.y)
};
var DrawRayLinesPlotter = create_class(CToolPlotter);
DrawRayLinesPlotter.prototype.__construct = function(d, c) {
	DrawRayLinesPlotter.__super.__construct.call(this, d);
	this.toolObject = c;
	this.ctrlPtsNum = 2;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawRayLinesPlotter.prototype.draw = function(b) {
	this.updateCtrlPtPos();
	this.getAreaPos();
	this.startPoint = this.ctrlPts[1][0];
	this.endPoint = this.ctrlPts[1][1];
	if (this.startPoint.x == this.endPoint.x && this.startPoint.y == this.endPoint.y) {
		this.endPoint.x += 1
	}
	this.drawRayLines(b, this.startPoint, this.endPoint)
};
var DrawArrowLinesPlotter = create_class(CToolPlotter);
DrawArrowLinesPlotter.prototype.__construct = function(d, c) {
	DrawArrowLinesPlotter.__super.__construct.call(this, d, c);
	this.toolObject = c;
	this.arrowSizeRatio = 0.03;
	this.arrowSize = 4;
	this.crossPt = {
		x: -1,
		y: -1
	};
	this.ctrlPtsNum = 2;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawArrowLinesPlotter.prototype.drawArrow = function(p, q, m) {
	var o = this.lenBetweenPts(q, m);
	var j = [m.x - q.x, m.y - q.y];
	this.crossPt.x = q.x + (1 - this.arrowSize / o) * j[0];
	this.crossPt.y = q.y + (1 - this.arrowSize / o) * j[1];
	var k = [-j[1], j[0]];
	var r = {
		x: k[0],
		y: k[1]
	};
	var l = {
		x: 0,
		y: 0
	};
	k[0] = this.arrowSize * r.x / this.lenBetweenPts(r, l);
	k[1] = this.arrowSize * r.y / this.lenBetweenPts(r, l);
	var n = [this.crossPt.x + k[0], this.crossPt.y + k[1]];
	Plotter.drawLine(p, m.x, m.y, n[0], n[1]);
	n = [this.crossPt.x - k[0], this.crossPt.y - k[1]];
	Plotter.drawLine(p, m.x, m.y, n[0], n[1])
};
DrawArrowLinesPlotter.prototype.draw = function(b) {
	this.updateCtrlPtPos();
	this.startPoint = this.ctrlPts[1][0];
	this.endPoint = this.ctrlPts[1][1];
	if (this.startPoint.x == this.endPoint.x && this.startPoint.y == this.endPoint.y) {
		this.endPoint.x += 1
	}
	Plotter.drawLine(b, this.startPoint.x, this.startPoint.y, this.endPoint.x, this.endPoint.y);
	this.drawArrow(b, this.startPoint, this.endPoint)
};
var DrawHoriStraightLinesPlotter = create_class(CToolPlotter);
DrawHoriStraightLinesPlotter.prototype.__construct = function(d, c) {
	DrawHoriStraightLinesPlotter.__super.__construct.call(this, d);
	this.toolObject = c;
	this.ctrlPtsNum = 1;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawHoriStraightLinesPlotter.prototype.draw = function(b) {
	this.updateCtrlPtPos();
	this.getAreaPos();
	this.startPoint = this.ctrlPts[1][0];
	Plotter.drawLine(b, this.areaPos.left, this.startPoint.y, this.areaPos.right, this.startPoint.y)
};
var DrawHoriRayLinesPlotter = create_class(CToolPlotter);
DrawHoriRayLinesPlotter.prototype.__construct = function(d, c) {
	DrawHoriRayLinesPlotter.__super.__construct.call(this, d);
	this.toolObject = c;
	this.ctrlPtsNum = 2;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawHoriRayLinesPlotter.prototype.draw = function(d) {
	this.updateCtrlPtPos();
	this.getAreaPos();
	this.startPoint = this.ctrlPts[1][0];
	this.endPoint = this.ctrlPts[1][1];
	if (this.startPoint.x == this.endPoint.x) {
		Plotter.drawLine(d, this.startPoint.x, this.startPoint.y, this.areaPos.right, this.startPoint.y)
	} else {
		var c = {
			x: this.endPoint.x,
			y: this.startPoint.y
		};
		this.drawRayLines(d, this.startPoint, c)
	}
};
var DrawHoriSegLinesPlotter = create_class(CToolPlotter);
DrawHoriSegLinesPlotter.prototype.__construct = function(d, c) {
	DrawHoriSegLinesPlotter.__super.__construct.call(this, d, c);
	this.toolObject = c;
	this.ctrlPtsNum = 2;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawHoriSegLinesPlotter.prototype.draw = function(b) {
	this.updateCtrlPtPos();
	this.startPoint = this.ctrlPts[1][0];
	this.endPoint = this.ctrlPts[1][1];
	this.endPoint.y = this.startPoint.y;
	if (this.startPoint.x == this.endPoint.x && this.startPoint.y == this.endPoint.y) {
		Plotter.drawLine(b, this.startPoint.x, this.startPoint.y, this.endPoint.x + 1, this.startPoint.y)
	} else {
		Plotter.drawLine(b, this.startPoint.x, this.startPoint.y, this.endPoint.x, this.startPoint.y)
	}
};
var DrawVertiStraightLinesPlotter = create_class(CToolPlotter);
DrawVertiStraightLinesPlotter.prototype.__construct = function(d, c) {
	DrawVertiStraightLinesPlotter.__super.__construct.call(this, d);
	this.toolObject = c;
	this.ctrlPtsNum = 1;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawVertiStraightLinesPlotter.prototype.draw = function(b) {
	this.updateCtrlPtPos();
	this.getAreaPos();
	this.startPoint = this.ctrlPts[1][0];
	Plotter.drawLine(b, this.startPoint.x, this.areaPos.top, this.startPoint.x, this.areaPos.bottom)
};
var DrawPriceLinesPlotter = create_class(CToolPlotter);
DrawPriceLinesPlotter.prototype.__construct = function(d, c) {
	DrawPriceLinesPlotter.__super.__construct.call(this, d);
	this.toolObject = c;
	this.ctrlPtsNum = 1;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawPriceLinesPlotter.prototype.draw = function(d) {
	d.font = "12px Tahoma";
	d.textAlign = "left";
	d.fillStyle = this.theme.getColor(Theme.Color.LineColorNormal);
	this.updateCtrlPtPos();
	this.getAreaPos();
	this.startPoint = this.ctrlPts[1][0];
	var c = this.ctrlPts[0][0].getPosIV().v;
	Plotter.drawLine(d, this.startPoint.x, this.startPoint.y, this.areaPos.right, this.startPoint.y);
	d.fillText(c.toFixed(2), this.startPoint.x + 2, this.startPoint.y - 15)
};
var ParallelLinesPlotter = create_class(CToolPlotter);
ParallelLinesPlotter.prototype.__construct = function(d, c) {
	ParallelLinesPlotter.__super.__construct.call(this, d);
	this.toolObject = c
};
ParallelLinesPlotter.prototype.getParaPt = function() {
	var c = [];
	c[0] = this.endPoint.x - this.startPoint.x;
	c[1] = this.endPoint.y - this.startPoint.y;
	var d = [];
	d[0] = this.paraStartPoint.x - this.startPoint.x;
	d[1] = this.paraStartPoint.y - this.startPoint.y;
	this.paraEndPoint = {
		x: -1,
		y: -1
	};
	this.paraEndPoint.x = c[0] + d[0] + this.startPoint.x;
	this.paraEndPoint.y = c[1] + d[1] + this.startPoint.y
};
var DrawBiParallelLinesPlotter = create_class(ParallelLinesPlotter);
DrawBiParallelLinesPlotter.prototype.__construct = function(d, c) {
	DrawBiParallelLinesPlotter.__super.__construct.call(this, d, c);
	this.toolObject = c;
	this.ctrlPtsNum = 3;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawBiParallelLinesPlotter.prototype.draw = function(b) {
	this.updateCtrlPtPos();
	this.getAreaPos();
	this.startPoint = this.ctrlPts[1][0];
	this.paraStartPoint = this.ctrlPts[1][1];
	this.endPoint = this.ctrlPts[1][2];
	this.getParaPt();
	this.getAreaPos();
	this.crossPt0 = getRectCrossPt(this.areaPos, this.startPoint, this.endPoint);
	Plotter.drawLine(b, this.crossPt0[0].x, this.crossPt0[0].y, this.crossPt0[1].x, this.crossPt0[1].y);
	this.crossPt1 = getRectCrossPt(this.areaPos, this.paraStartPoint, this.paraEndPoint);
	Plotter.drawLine(b, this.crossPt1[0].x, this.crossPt1[0].y, this.crossPt1[1].x, this.crossPt1[1].y)
};
var DrawBiParallelRayLinesPlotter = create_class(ParallelLinesPlotter);
DrawBiParallelRayLinesPlotter.prototype.__construct = function(d, c) {
	DrawBiParallelRayLinesPlotter.__super.__construct.call(this, d, c);
	this.toolObject = c;
	this.ctrlPtsNum = 3;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawBiParallelRayLinesPlotter.prototype.draw = function(b) {
	this.updateCtrlPtPos();
	this.getAreaPos();
	this.startPoint = this.ctrlPts[1][0];
	this.paraStartPoint = this.ctrlPts[1][1];
	this.endPoint = this.ctrlPts[1][2];
	if (this.startPoint.x == this.endPoint.x && this.startPoint.y == this.endPoint.y) {
		this.endPoint.x += 1
	}
	this.getParaPt();
	this.drawRayLines(b, this.startPoint, this.endPoint);
	this.drawRayLines(b, this.paraStartPoint, this.paraEndPoint)
};
var DrawTriParallelLinesPlotter = create_class(ParallelLinesPlotter);
DrawTriParallelLinesPlotter.prototype.__construct = function(d, c) {
	DrawTriParallelLinesPlotter.__super.__construct.call(this, d, c);
	this.toolObject = c;
	this.ctrlPtsNum = 3;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawTriParallelLinesPlotter.prototype.draw = function(f) {
	this.updateCtrlPtPos();
	this.getAreaPos();
	this.startPoint = this.ctrlPts[1][0];
	this.paraStartPoint = this.ctrlPts[1][1];
	this.endPoint = this.ctrlPts[1][2];
	var d = [];
	d[0] = this.endPoint.x - this.startPoint.x;
	d[1] = this.endPoint.y - this.startPoint.y;
	var e = [];
	e[0] = this.paraStartPoint.x - this.startPoint.x;
	e[1] = this.paraStartPoint.y - this.startPoint.y;
	this.para1EndPoint = {
		x: -1,
		y: -1
	};
	this.para2EndPoint = {
		x: -1,
		y: -1
	};
	this.para2StartPoint = {
		x: -1,
		y: -1
	};
	this.para1EndPoint.x = d[0] + e[0] + this.startPoint.x;
	this.para1EndPoint.y = d[1] + e[1] + this.startPoint.y;
	this.para2StartPoint.x = this.startPoint.x - e[0];
	this.para2StartPoint.y = this.startPoint.y - e[1];
	this.para2EndPoint.x = this.endPoint.x - e[0];
	this.para2EndPoint.y = this.endPoint.y - e[1];
	this.getAreaPos();
	this.crossPt0 = getRectCrossPt(this.areaPos, this.startPoint, this.endPoint);
	Plotter.drawLine(f, this.crossPt0[0].x, this.crossPt0[0].y, this.crossPt0[1].x, this.crossPt0[1].y);
	this.crossPt1 = getRectCrossPt(this.areaPos, this.paraStartPoint, this.para1EndPoint);
	Plotter.drawLine(f, this.crossPt1[0].x, this.crossPt1[0].y, this.crossPt1[1].x, this.crossPt1[1].y);
	this.crossPt2 = getRectCrossPt(this.areaPos, this.para2StartPoint, this.para2EndPoint);
	Plotter.drawLine(f, this.crossPt2[0].x, this.crossPt2[0].y, this.crossPt2[1].x, this.crossPt2[1].y)
};
var BandLinesPlotter = create_class(CToolPlotter);
BandLinesPlotter.prototype.__construct = function(d, c) {
	BandLinesPlotter.__super.__construct.call(this, d);
	this.toolObject = c;
	this.ctrlPtsNum = 2;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
BandLinesPlotter.prototype.drawLinesAndInfo = function(m, k, n) {
	m.font = "12px Tahoma";
	m.textAlign = "left";
	m.fillStyle = this.theme.getColor(Theme.Color.LineColorNormal);
	var j;
	if (this.toolObject.state == CToolObject.state.Draw) {
		this.startPtValue = this.toolObject.getPoint(0).getPosIV().v;
		this.endPtValue = this.toolObject.getPoint(1).getPosIV().v
	}
	this.getAreaPos();
	for (var h = 0; h < this.fiboSequence.length; h++) {
		var i = k.y + (100 - this.fiboSequence[h]) / 100 * (n.y - k.y);
		if (i > this.areaPos.bottom) {
			continue
		}
		var l = this.startPtValue + (100 - this.fiboSequence[h]) / 100 * (this.endPtValue - this.startPtValue);
		Plotter.drawLine(m, this.areaPos.left, i, this.areaPos.right, i);
		j = this.fiboSequence[h].toFixed(1) + "% " + l.toFixed(1);
		m.fillText(j, this.areaPos.left + 2, i - 15)
	}
};
BandLinesPlotter.prototype.draw = function(b) {
	this.updateCtrlPtPos();
	this.getAreaPos();
	this.startPoint = this.ctrlPts[1][0];
	this.endPoint = this.ctrlPts[1][1];
	this.drawLinesAndInfo(b, this.startPoint, this.endPoint)
};
var DrawFibRetracePlotter = create_class(BandLinesPlotter);
DrawFibRetracePlotter.prototype.__construct = function(d, c) {
	DrawFibRetracePlotter.__super.__construct.call(this, d, c);
	this.toolObject = c;
	this.fiboSequence = [100, 78.6, 61.8, 50, 38.2, 23.6, 0]
};
var DrawBandLinesPlotter = create_class(BandLinesPlotter);
DrawBandLinesPlotter.prototype.__construct = function(d, c) {
	DrawBandLinesPlotter.__super.__construct.call(this, d, c);
	this.toolObject = c;
	this.fiboSequence = [0, 12.5, 25, 37.5, 50, 62.5, 75, 87.5, 100]
};
var DrawFibFansPlotter = create_class(CToolPlotter);
DrawFibFansPlotter.prototype.__construct = function(d, c) {
	DrawFibFansPlotter.__super.__construct.call(this, d);
	this.toolObject = c;
	this.fiboFansSequence = [0, 38.2, 50, 61.8];
	this.ctrlPtsNum = 2;
	this.ctrlPts = new Array(new Array(this.ctrlPtsNum), new Array(2));
	this.getCtrlPts()
};
DrawFibFansPlotter.prototype.drawLinesAndInfo = function(d, f, e) {
	this.drawFibRayLines(d, f, e)
};
DrawFibFansPlotter.prototype.draw = function(b) {
	this.updateCtrlPtPos();
	this.getAreaPos();
	this.startPoint = this.ctrlPts[1][0];
	this.endPoint = this.ctrlPts[1][1];
	if (this.startPoint.x == this.endPoint.x && this.startPoint.y == this.endPoint.y) {
		this.endPoint.x += 1
	}
	this.drawLinesAndInfo(b, this.startPoint, this.endPoint)
};
var CDynamicLinePlotter = create_class(NamedObject);
CDynamicLinePlotter.prototype.__construct = function(b) {
	CDynamicLinePlotter.__super.__construct.call(this, b);
	this.flag = true;
	this.context = ChartManager.getInstance()._overlayContext
};
CDynamicLinePlotter.prototype.getAreaPos = function() {
	var c = ChartManager.getInstance();
	var d = c.getArea("frame0.k0.main");
	if (d == null) {
		this.areaPos = {
			left: 0,
			top: 0,
			right: 0,
			bottom: 0
		};
		return
	}
	this.areaPos = {
		left: Math.floor(d.getLeft()),
		top: Math.floor(d.getTop()),
		right: Math.floor(d.getRight()),
		bottom: Math.floor(d.getBottom())
	}
};
CDynamicLinePlotter.prototype.Draw = function(i) {
	this.getAreaPos();
	var l = ChartManager.getInstance();
	var k = l.getDataSource(this.getDataSourceName());
	if (k == null || !is_instance(k, MainDataSource)) {
		return
	}
	this.context.save();
	this.context.rect(this.areaPos.left, this.areaPos.top, this.areaPos.right - this.areaPos.left, this.areaPos.bottom - this.areaPos.top);
	this.context.clip();
	var p = k.getToolObjectCount();
	for (var j = 0; j < p; j++) {
		var m = k.getToolObject(j);
		var n = m.getState();
		switch (n) {
		case CToolObject.state.BeforeDraw:
			m.getPlotter().theme = ChartManager.getInstance().getTheme(this.getFrameName());
			m.getPlotter().drawCursor(this.context);
			break;
		case CToolObject.state.Draw:
			m.getPlotter().theme = ChartManager.getInstance().getTheme(this.getFrameName());
			m.getPlotter().updateDraw(this.context);
			break;
		case CToolObject.state.AfterDraw:
			m.getPlotter().theme = ChartManager.getInstance().getTheme(this.getFrameName());
			m.getPlotter().finishDraw(this.context);
			break;
		default:
			break
		}
	}
	var o = k.getSelectToolObjcet();
	if (o != null && o != CToolObject.state.Draw) {
		o.getPlotter().highlight(this.context)
	}
	this.context.restore();
	return
};

function KLineMouseEvent() {
	$(document).ready(function() {
		function b() {
			if (navigator.userAgent.indexOf("Firefox") >= 0) {
				setTimeout(on_size, 200)
			} else {
				on_size()
			}
		}
		b();
		$(window).resize(b);
		$("#chart_overlayCanvas").bind("contextmenu", function(a) {
			a.cancelBubble = true;
			a.returnValue = false;
			a.preventDefault();
			a.stopPropagation();
			return false
		});
		$("#chart_input_interface").submit(function(j) {
			j.preventDefault();
			var e = $("#chart_input_interface_text").val();
			var h = JSON.parse(e);
			var i = h.command;
			var a = h.content;
			switch (i) {
			case "set current depth":
				ChartManager.getInstance().getChart().updateDepth(a);
				break;
			case "set current future":
				break;
			case "set current language":
				chart_switch_language(a);
				break;
			case "set current theme":
				break;
			default:
				break
			}
		});
		$("#chart_container .chart_dropdown .chart_dropdown_t").mouseover(function() {
			var r = $("#chart_container");
			var m = $(this);
			var a = m.next();
			var q = r.offset().left;
			var p = m.offset().left;
			var l = r.width();
			var d = m.width();
			var n = a.width();
			var o = ((n - d) / 2) << 0;
			if (p - o < q + 4) {
				o = p - q - 4
			} else {
				if (p + d + o > q + l - 4) {
					o += p + d + o - (q + l - 4) + 19
				} else {
					o += 4
				}
			}
			a.css({
				"margin-left": -o
			});
			m.addClass("chart_dropdown-hover");
			a.addClass("chart_dropdown-hover")
		}).mouseout(function() {
			$(this).next().removeClass("chart_dropdown-hover");
			$(this).removeClass("chart_dropdown-hover")
		});
		$(".chart_dropdown_data").mouseover(function() {
			$(this).addClass("chart_dropdown-hover");
			$(this).prev().addClass("chart_dropdown-hover")
		}).mouseout(function() {
			$(this).prev().removeClass("chart_dropdown-hover");
			$(this).removeClass("chart_dropdown-hover")
		});
		$("#chart_btn_parameter_settings").click(function() {
			$("#chart_parameter_settings").addClass("clicked");
			$(".chart_dropdown_data").removeClass("chart_dropdown-hover");
			$("#chart_parameter_settings").find("th").each(function() {
				var h = $(this).html();
				var a = 0;
				var g = ChartSettings.get();
				var f = g.indics[h];
				$(this.nextElementSibling).find("input").each(function() {
					if (f != null && a < f.length) {
						$(this).val(f[a])
					}
					a++
				})
			})
		});
		$("#close_settings").click(function() {
			$("#chart_parameter_settings").removeClass("clicked")
		});
		$("#chart_container .chart_toolbar_tabgroup a").click(function() {
			switch_period($(this).parent().attr("name"))
		});
		$("#chart_toolbar_periods_vert ul a").click(function() {
			switch_period($(this).parent().attr("name"))
		});
		$(".market_chooser ul a").click(function() {
			switch_market($(this).attr("name"))
		});
		$("#chart_show_tools").click(function() {
			if ($(this).hasClass("selected")) {
				switch_tools("off")
			} else {
				switch_tools("on")
			}
		});
		$("#chart_toolpanel .chart_toolpanel_button").click(function() {
			$(".chart_dropdown_data").removeClass("chart_dropdown-hover");
			$("#chart_toolpanel .chart_toolpanel_button").removeClass("selected");
			$(this).addClass("selected");
			var a = $(this).children().attr("name");
			GLOBAL_VAR.chartMgr.setRunningMode(ChartManager.DrawingTool[a])
		});
		$("#chart_show_indicator").click(function() {
			if ($(this).hasClass("selected")) {
				switch_indic("off")
			} else {
				switch_indic("on")
			}
		});
		$("#chart_tabbar li a").click(function() {
			$("#chart_tabbar li a").removeClass("selected");
			$(this).addClass("selected");
			var a = $(this).attr("name");
			var d = ChartSettings.get();
			d.charts.indics[1] = a;
			ChartSettings.save();
			if (Template.displayVolume == false) {
				ChartManager.getInstance().getChart().setIndicator(1, a)
			} else {
				ChartManager.getInstance().getChart().setIndicator(2, a)
			}
		});
		$("#chart_select_chart_style a").click(function() {
			$("#chart_select_chart_style a").removeClass("selected");
			$(this).addClass("selected");
			var a = ChartSettings.get();
			a.charts.chartStyle = $(this)[0].innerHTML;
			ChartSettings.save();
			var d = ChartManager.getInstance();
			d.setChartStyle("frame0.k0", $(this).html());
			d.redraw()
		});
		$("#chart_dropdown_themes li").click(function() {
			$("#chart_dropdown_themes li a").removeClass("selected");
			var a = $(this).attr("name");
			if (a == "chart_themes_dark") {
				switch_theme("dark")
			} else {
				if (a == "chart_themes_light") {
					switch_theme("light")
				}
			}
		});
		$("#chart_select_main_indicator a").click(function() {
			$("#chart_select_main_indicator a").removeClass("selected");
			$(this).addClass("selected");
			var a = $(this).attr("name");
			var f = ChartSettings.get();
			f.charts.mIndic = a;
			ChartSettings.save();
			var e = ChartManager.getInstance();
			if (!e.setMainIndicator("frame0.k0", a)) {
				e.removeMainIndicator("frame0.k0")
			}
			e.redraw()
		});
		$("#chart_toolbar_theme a").click(function() {
			$("#chart_toolbar_theme a").removeClass("selected");
			if ($(this).attr("name") == "dark") {
				switch_theme("dark")
			} else {
				if ($(this).attr("name") == "light") {
					switch_theme("light")
				}
			}
		});
		$("#chart_select_theme li a").click(function() {
			$("#chart_select_theme a").removeClass("selected");
			if ($(this).attr("name") == "dark") {
				switch_theme("dark")
			} else {
				if ($(this).attr("name") == "light") {
					switch_theme("light")
				}
			}
		});
		$("#chart_enable_tools li a").click(function() {
			$("#chart_enable_tools a").removeClass("selected");
			if ($(this).attr("name") == "on") {
				switch_tools("on")
			} else {
				if ($(this).attr("name") == "off") {
					switch_tools("off")
				}
			}
		});
		$("#chart_enable_indicator li a").click(function() {
			$("#chart_enable_indicator a").removeClass("selected");
			if ($(this).attr("name") == "on") {
				switch_indic("on")
			} else {
				if ($(this).attr("name") == "off") {
					switch_indic("off")
				}
			}
		});
		$("#chart_language_setting_div li a").click(function() {
			$("#chart_language_setting_div a").removeClass("selected");
			if ($(this).attr("name") == "zh-cn") {
				chart_switch_language("zh-cn")
			} else {
				if ($(this).attr("name") == "en-us") {
					chart_switch_language("en-us")
				} else {
					if ($(this).attr("name") == "zh-tw") {
						chart_switch_language("zh-tw")
					}
				}
			}
		});
		$(document).keyup(function(a) {
			if (a.keyCode == 46) {
				ChartManager.getInstance().deleteToolObject();
				ChartManager.getInstance().redraw("OverlayCanvas", false)
			}
		});
		$("#clearCanvas").click(function() {
			var e = ChartManager.getInstance().getDataSource("frame0.k0");
			var a = e.getToolObjectCount();
			for (var f = 0; f < a; f++) {
				e.delToolObject()
			}
			ChartManager.getInstance().redraw("OverlayCanvas", false)
		});
		$("#chart_overlayCanvas").mousemove(function(h) {
			var j = h.target.getBoundingClientRect();
			var a = h.clientX - j.left;
			var e = h.clientY - j.top;
			var i = ChartManager.getInstance();
			if (GLOBAL_VAR.button_down == true) {
				i.onMouseMove("frame0", a, e, true);
				i.redraw("All", false)
			} else {
				i.onMouseMove("frame0", a, e, false);
				i.redraw("OverlayCanvas")
			}
		}).mouseleave(function(h) {
			var j = h.target.getBoundingClientRect();
			var a = h.clientX - j.left;
			var e = h.clientY - j.top;
			var i = ChartManager.getInstance();
			i.onMouseLeave("frame0", a, e, false);
			i.redraw("OverlayCanvas")
		}).mouseup(function(h) {
			if (h.which != 1) {
				return
			}
			GLOBAL_VAR.button_down = false;
			var j = h.target.getBoundingClientRect();
			var a = h.clientX - j.left;
			var e = h.clientY - j.top;
			var i = ChartManager.getInstance();
			i.onMouseUp("frame0", a, e);
			i.redraw("All")
		}).mousedown(function(g) {
			if (g.which != 1) {
				ChartManager.getInstance().deleteToolObject();
				ChartManager.getInstance().redraw("OverlayCanvas", false);
				return
			}
			GLOBAL_VAR.button_down = true;
			var h = g.target.getBoundingClientRect();
			var a = g.clientX - h.left;
			var e = g.clientY - h.top;
			ChartManager.getInstance().onMouseDown("frame0", a, e)
		});
		$("#chart_parameter_settings :input").change(function() {
			var m = $(this).attr("name");
			var n = 0;
			var k = [];
			var i = ChartManager.getInstance();
			$("#chart_parameter_settings :input").each(function() {
				if ($(this).attr("name") == m) {
					if ($(this).val() != "" && $(this).val() != null && $(this).val() != undefined) {
						var c = parseInt($(this).val());
						k.push(c)
					}
					n++
				}
			});
			if (k.length != 0) {
				i.setIndicatorParameters(m, k);
				var j = i.getIndicatorParameters(m);
				var a = [];
				n = 0;
				$("#chart_parameter_settings :input").each(function() {
					if ($(this).attr("name") == m) {
						if ($(this).val() != "" && $(this).val() != null && $(this).val() != undefined) {
							$(this).val(j[n].getValue());
							a.push(j[n].getValue())
						}
						n++
					}
				});
				var l = ChartSettings.get();
				l.indics[m] = a;
				ChartSettings.save();
				i.redraw("All", false)
			}
		});
		$("#chart_parameter_settings button").click(function() {
			var j = $(this).parents("tr").children("th").html();
			var a = 0;
			var g = ChartManager.getInstance().getIndicatorParameters(j);
			var h = [];
			$(this).parent().prev().children("input").each(function() {
				if (g != null && a < g.length) {
					$(this).val(g[a].getDefaultValue());
					h.push(g[a].getDefaultValue())
				}
				a++
			});
			ChartManager.getInstance().setIndicatorParameters(j, h);
			var i = ChartSettings.get();
			i.indics[j] = h;
			ChartSettings.save();
			ChartManager.getInstance().redraw("All", false)
		})
	})
}
var refresh_counter = 0;
var refresh_handler = setInterval(refresh_function, 1000);

function refresh_function() {
	refresh_counter++;
	var c = ChartManager.getInstance().getLanguage();
	if (refresh_counter > 3600) {
		var d = new Number(refresh_counter / 3600);
		if (c == "en-us") {
			$("#chart_updated_time_text").html(d.toFixed(0) + "h")
		} else {
			$("#chart_updated_time_text").html(d.toFixed(0) + "小时")
		}
	} else {
		if (refresh_counter > 60 && refresh_counter <= 3600) {
			var d = new Number(refresh_counter / 60);
			if (c == "en-us") {
				$("#chart_updated_time_text").html(d.toFixed(0) + "m")
			} else {
				$("#chart_updated_time_text").html(d.toFixed(0) + "分钟")
			}
		} else {
			if (refresh_counter <= 60) {
				if (c == "en-us") {
					$("#chart_updated_time_text").html(refresh_counter + "s")
				} else {
					$("#chart_updated_time_text").html(refresh_counter + "秒")
				}
			}
		}
	}
}
function clear_refresh_counter() {
	window.clearInterval(refresh_handler);
	refresh_counter = 0;
	var b = ChartManager.getInstance().getLanguage();
	if (b == "en-us") {
		$("#chart_updated_time_text").html(refresh_counter + "s")
	} else {
		$("#chart_updated_time_text").html(refresh_counter + "秒")
	}
	refresh_handler = setInterval(refresh_function, 1000)
}
var RequestData = function(a) {
		AbortRequest();
		window.clearTimeout(GLOBAL_VAR.TimeOutId);
		if (a == true) {
			$("#chart_loading").addClass("activated")
		}
		$(document).ready(GLOBAL_VAR.G_HTTP_REQUEST = $.ajax({
			type: "post",
			url: GLOBAL_VAR.url,
			dataType: "json",
			data: GLOBAL_VAR.requestParam,
			timeout: 30000,
			created: Date.now(),
			beforeSend: function() {
				this.time = GLOBAL_VAR.time_type;
				this.market = GLOBAL_VAR.market_from
			},
			success: function(b) {
				if (GLOBAL_VAR.G_HTTP_REQUEST) {
					if (this.time != GLOBAL_VAR.time_type || this.market != GLOBAL_VAR.market_from) {
						GLOBAL_VAR.TimeOutId = setTimeout(RequestData, 1000);
						return
					}
					if (!b) {
						return
					}
					if (!b.isSuc) {
						return
					}
					GLOBAL_VAR.market_from_name = b.datas.marketName;
					var c = ChartManager.getInstance().getChart();
					c._contract_unit = b.datas.contractUnit;
					c._money_type = b.datas.moneyType;
					c._usd_cny_rate = b.datas.USDCNY;
					c.setTitle();
					GLOBAL_VAR.KLineData = b.datas.data;
					try {
						if (!GLOBAL_VAR.chartMgr.updateData("frame0.k0", GLOBAL_VAR.KLineData)) {
							return
						}
						clear_refresh_counter()
					} catch (d) {
						if (d == "data error") {
							GLOBAL_VAR.requestParam = setHttpRequestParam(GLOBAL_VAR.market_from, GLOBAL_VAR.time_type, GLOBAL_VAR.limit, null);
							GLOBAL_VAR.TimeOutId = setTimeout(RequestData, 1000);
							return
						}
					}
					GLOBAL_VAR.TimeOutId = setTimeout(TwoSecondThread, 8 * 1000);
					$("#chart_loading").removeClass("activated");
					ChartManager.getInstance().redraw("All", false)
				}
			},
			error: function(c, d, b) {
				if (c.status == 200 && c.readyState == 4) {
					return
				}
				GLOBAL_VAR.TimeOutId = setTimeout(function() {
					RequestData(true)
				}, 1000)
			},
			complete: function() {
				GLOBAL_VAR.G_HTTP_REQUEST = null
			}
		}))
	};

function AbortRequest() {
	if (GLOBAL_VAR.G_HTTP_REQUEST && GLOBAL_VAR.G_HTTP_REQUEST.readyState != 4) {
		GLOBAL_VAR.G_HTTP_REQUEST.abort()
	}
}
function TwoSecondThread() {
	var b = GLOBAL_VAR.chartMgr.getDataSource("frame0.k0").getLastDate();
	if (b == -1) {
		GLOBAL_VAR.requestParam = setHttpRequestParam(GLOBAL_VAR.market_from, GLOBAL_VAR.time_type, GLOBAL_VAR.limit, null)
	} else {
		GLOBAL_VAR.requestParam = setHttpRequestParam(GLOBAL_VAR.market_from, GLOBAL_VAR.time_type, null, b.toString())
	}
	RequestData()
}
function readCookie() {
	ChartSettings.get();
	ChartSettings.save();
	var f = ChartSettings.get();
	ChartManager.getInstance().setChartStyle("frame0.k0", f.charts.chartStyle);
	var h = f.charts.market_from;
	if (!GLOBAL_VAR.init) {
		h = kline.symbol;
		GLOBAL_VAR.init = true
	}
	GLOBAL_VAR.market_from = h;
	switch_market_selected(h);
	var i = f.charts.period;
	switch_period(i);
	$("#chart_period_" + i + "_v a").addClass("selected");
	$("#chart_period_" + i + "_h a").addClass("selected");
	if (f.charts.indicsStatus == "close") {
		switch_indic("off")
	} else {
		if (f.charts.indicsStatus == "open") {
			switch_indic("on")
		}
	}
	//加了一个click()
	var g = $("#chart_select_main_indicator");
	g.find("a").each(function() {
		if ($(this).attr("name") == f.charts.mIndic) {
			$(this).addClass("selected")
			$(this).click()
		}
	});
	var j = $("#chart_select_chart_style");
	j.find("a").each(function() {
		if ($(this)[0].innerHTML == f.charts.chartStyle) {
			$(this).addClass("selected")
		}
	});
	ChartManager.getInstance().getChart().setMainIndicator(f.charts.mIndic);
	ChartManager.getInstance().setThemeName("frame0", f.theme);
	switch_tools("off");
	if (f.theme == "Dark") {
		switch_theme("dark")
	} else {
		if (f.theme == "Light") {
			switch_theme("light")
		}
	}
	chart_switch_language(f.language || "zh-cn")
}
var main = function() {
		window._setMarketFrom = function(b) {
			Template.displayVolume = false;
			refreshTemplate();
			readCookie();
			ChartManager.getInstance().getChart().setMarketFrom(b)
		};
		window._set_current_language = function(b) {
			chart_switch_language(b)
		};
		window._set_current_depth = function(b) {
			ChartManager.getInstance().getChart().updateDepth(b)
		};
		window._set_current_url = function(b) {
			GLOBAL_VAR.url = b
		};
		window._set_current_contract_unit = function(b) {
			ChartManager.getInstance().getChart().setCurrentContractUnit(b)
		};
		window._set_money_type = function(b) {
			ChartManager.getInstance().getChart().setCurrentMoneyType(b)
		};
		window._set_usd_cny_rate = function(b) {
			ChartManager.getInstance().getChart()._usd_cny_rate = b
		};
		window._setCaptureMouseWheelDirectly = function(b) {
			ChartManager.getInstance().setCaptureMouseWheelDirectly(b)
		};
		window._current_future_change = new MEvent();
		window._current_theme_change = new MEvent();
		KLineMouseEvent();
		ChartManager.getInstance().bindCanvas("main", document.getElementById("chart_mainCanvas"));
		ChartManager.getInstance().bindCanvas("overlay", document.getElementById("chart_overlayCanvas"));
		refreshTemplate();
		on_size();
		readCookie();
		$("#chart_container").css({
			visibility: "visible"
		})
	}();

function setHttpRequestParam(f, j, g, h) {
	var i = "needTickers=1&symbol=" + f + "&type=" + j;
	if (g != null) {
		i += "&size=" + g
	} else {
		i += "&since=" + h
	}
	return i
}
function refreshTemplate() {
	GLOBAL_VAR.chartMgr = DefaultTemplate.loadTemplate("frame0.k0", "BTC123");
	ChartManager.getInstance().redraw("All", true)
}
function getRectCrossPt(k, p, m) {
	var q;
	var j = {
		x: -1,
		y: -1
	};
	var r = {
		x: -1,
		y: -1
	};
	var l = m.x - p.x;
	var o = m.y - p.y;
	if (Math.abs(l) < 2) {
		j = {
			x: p.x,
			y: k.top
		};
		r = {
			x: m.x,
			y: k.bottom
		};
		q = [j, r];
		return q
	}
	var n = o / l;
	r.x = k.right;
	r.y = p.y + (k.right - p.x) * n;
	j.x = k.left;
	j.y = p.y + (k.left - p.x) * n;
	q = [j, r];
	return q
}
function chart_switch_language(c) {
	var d = c.replace(/-/, "_");
	$("#chart_language_switch_tmp").find("span").each(function() {
		var b = $(this).attr("name");
		var f = $(this).attr(d);
		b = "." + b;
		var a = $(b)[0];
		if (!a) {
			return
		}
		$(b).each(function() {
			$(this)[0].innerHTML = f
		})
	});
	$("#chart_language_setting_div li a[name='" + c + "']").addClass("selected");
	ChartManager.getInstance().setLanguage(c);
	ChartManager.getInstance().getChart().setTitle();
	var d = ChartSettings.get();
	d.language = c;
	ChartSettings.save()
}
function on_size() {
	var O = window.innerWidth;
	var Q = window.innerHeight;
	var Z = $("#chart_container");
	Z.css({
		width: O + "px",
		height: Q + "px"
	});
	var H = $("#chart_toolbar");
	var Y = $("#chart_toolpanel");
	var S = $("#chart_canvasGroup");
	var W = $("#chart_tabbar");
	var L = Y[0].style.display != "inline" ? false : true;
	var aa = W[0].style.display != "block" ? false : true;
	var M = {};
	M.x = 0;
	M.y = 0;
	M.w = O;
	M.h = 29;
	var ai = {};
	ai.x = 0;
	ai.y = M.h + 1;
	ai.w = L ? 32 : 0;
	ai.h = Q - ai.y;
	var X = {};
	X.w = L ? O - (ai.w + 1) : O;
	X.h = aa ? 22 : -1;
	X.x = O - X.w;
	X.y = Q - (X.h + 1);
	var K = {};
	K.x = X.x;
	K.y = ai.y;
	K.w = X.w;
	K.h = X.y - ai.y;
	H.css({
		left: M.x + "px",
		top: M.y + "px",
		width: M.w + "px",
		height: M.h + "px"
	});
	if (L) {
		Y.css({
			left: ai.x + "px",
			top: ai.y + "px",
			width: ai.w + "px",
			height: ai.h + "px"
		})
	}
	S.css({
		left: K.x + "px",
		top: K.y + "px",
		width: K.w + "px",
		height: K.h + "px"
	});
	var J = $("#chart_mainCanvas")[0];
	var ah = $("#chart_overlayCanvas")[0];
	J.width = K.w;
	J.height = K.h;
	ah.width = K.w;
	ah.height = K.h;
	if (aa) {
		W.css({
			left: X.x + "px",
			top: X.y + "px",
			width: X.w + "px",
			height: X.h + "px"
		})
	}
	var ae = $("#chart_parameter_settings");
	ae.css({
		left: (O - ae.width()) >> 1,
		top: (Q - ae.height()) >> 1
	});
	var al = $("#chart_loading");
	al.css({
		left: (O - al.width()) >> 1,
		top: (Q - al.height()) >> 2
	});
	var ac = $("#chart_dom_elem_cache");
	var an = $("#chart_select_theme")[0];
	var P = $("#chart_enable_tools")[0];
	var ag = $("#chart_enable_indicator")[0];
	var U = $("#chart_toolbar_periods_vert");
	var R = $("#chart_toolbar_periods_horz")[0];
	var V = $("#chart_show_indicator")[0];
	var af = $("#chart_show_tools")[0];
	var aj = $("#chart_toolbar_theme")[0];
	var ad = $("#chart_dropdown_settings");
	var am = U[0].offsetWidth;
	var I = am + R.offsetWidth;
	var ab = I + V.offsetWidth + 4;
	var N = ab + af.offsetWidth + 4;
	var T = N + aj.offsetWidth;
	var ak = ad.find(".chart_dropdown_t")[0].offsetWidth + 150;
	am += ak;
	I += ak;
	ab += ak;
	N += ak;
	T += ak;
	if (O < I) {
		ac.append(R)
	} else {
		U.after(R)
	}
	if (O < ab) {
		ac.append(V);
		ag.style.display = ""
	} else {
		ad.before(V);
		ag.style.display = "none"
	}
	if (O < N) {
		ac.append(af);
		P.style.display = ""
	} else {
		ad.before(af);
		P.style.display = "none"
	}
	if (O < T) {
		ac.append(aj);
		an.style.display = ""
	} else {
		ad.before(aj);
		an.style.display = "none"
	}
	if (O < 850) {
		$("#chart_updated_time").css("display", "none")
	} else {
		$("#chart_updated_time").css("display", "")
	}
	if (O < 750) {
		$("#chart_language_setting_div").css("display", "none")
	} else {
		$("#chart_language_setting_div").css("display", "")
	}
	if (O < 280) {
		$("#chart_exchanges_setting_div").css("display", "none")
	} else {
		$("#chart_exchanges_setting_div").css("display", "")
	}
	ChartManager.getInstance().redraw("All", true)
}
function mouseWheel(d, c) {
	ChartManager.getInstance().scale(c > 0 ? 1 : -1);
	ChartManager.getInstance().redraw("All", true);
	return false
}
function switch_theme(f) {
	$("#chart_toolbar_theme a").removeClass("selected");
	$("#chart_select_theme a").removeClass("selected");
	$("#chart_toolbar_theme").find("a").each(function() {
		if ($(this).attr("name") == f) {
			$(this).addClass("selected")
		}
	});
	$("#chart_select_theme a").each(function() {
		if ($(this).attr("name") == f) {
			$(this).addClass("selected")
		}
	});
	$("#chart_container").attr("class", f);
	$(".marketName_ a").attr("class", f);
	if (f == "dark") {
		$("#trade_container").addClass("dark").removeClass("light");
		ChartManager.getInstance().setThemeName("frame0", "Dark");
		var e = ChartSettings.get();
		e.theme = "Dark";
		ChartSettings.save()
	} else {
		if (f == "light") {
			$("#trade_container").addClass("light").removeClass("dark");
			ChartManager.getInstance().setThemeName("frame0", "Light");
			var e = ChartSettings.get();
			e.theme = "Light";
			ChartSettings.save()
		}
	}
	var a = {};
	a.command = "set current theme";
	a.content = f;
	$("#chart_output_interface_text").val(JSON.stringify(a));
	$("#chart_output_interface_submit").submit();
	window._current_theme_change.raise(f);
	ChartManager.getInstance().redraw()
}
function switch_tools(b) {
	$(".chart_dropdown_data").removeClass("chart_dropdown-hover");
	$("#chart_toolpanel .chart_toolpanel_button").removeClass("selected");
	$("#chart_enable_tools a").removeClass("selected");
	if (b == "on") {
		$("#chart_show_tools").addClass("selected");
		$("#chart_enable_tools a").each(function() {
			if ($(this).attr("name") == "on") {
				$(this).addClass("selected")
			}
		});
		$("#chart_toolpanel")[0].style.display = "inline";
		if (ChartManager.getInstance()._drawingTool == ChartManager.DrawingTool.Cursor) {
			$("#chart_Cursor").parent().addClass("selected")
		} else {
			if (ChartManager.getInstance()._drawingTool == ChartManager.DrawingTool.CrossCursor) {
				$("#chart_CrossCursor").parent().addClass("selected")
			}
		}
	} else {
		if (b == "off") {
			$("#chart_show_tools").removeClass("selected");
			$("#chart_enable_tools a").each(function() {
				if ($(this).attr("name") == "off") {
					$(this).addClass("selected")
				}
			});
			$("#chart_toolpanel")[0].style.display = "none";
			ChartManager.getInstance().setRunningMode(ChartManager.getInstance()._beforeDrawingTool);
			ChartManager.getInstance().redraw("All", true)
		}
	}
	on_size()
}
function switch_indic(e) {
	$("#chart_enable_indicator a").removeClass("selected");
	$("#chart_enable_indicator a[name='" + e + "']").addClass("selected");
	if (e == "on") {
		$("#chart_show_indicator").addClass("selected");
		var d = ChartSettings.get();
		d.charts.indicsStatus = "open";
		ChartSettings.save();
		var f = d.charts.indics[1];
		if (Template.displayVolume == false) {
			ChartManager.getInstance().getChart().setIndicator(2, f)
		} else {
			ChartManager.getInstance().getChart().setIndicator(2, f)
		}
		$("#chart_tabbar").find("a").each(function() {
			if ($(this).attr("name") == f) {
				$(this).addClass("selected")
			}
		});
		$("#chart_tabbar")[0].style.display = "block"
	} else {
		if (e == "off") {
			$("#chart_show_indicator").removeClass("selected");
			ChartManager.getInstance().getChart().setIndicator(2, "NONE");
			var d = ChartSettings.get();
			d.charts.indicsStatus = "close";
			ChartSettings.save();
			$("#chart_tabbar")[0].style.display = "none";
			$("#chart_tabbar a").removeClass("selected")
		}
	}
	on_size()
}
function switch_period(e) {
	$("#chart_container .chart_toolbar_tabgroup a").removeClass("selected");
	$("#chart_toolbar_periods_vert ul a").removeClass("selected");
	$("#chart_container .chart_toolbar_tabgroup a").each(function() {
		if ($(this).parent().attr("name") == e) {
			$(this).addClass("selected")
		}
	});
	$("#chart_toolbar_periods_vert ul a").each(function() {
		if ($(this).parent().attr("name") == e) {
			$(this).addClass("selected")
		}
	});
	ChartManager.getInstance().showCursor();
	calcPeriodWeight(e);
	if (e == "line") {
		ChartManager.getInstance().getChart().strIsLine = true;
		ChartManager.getInstance().setChartStyle("frame0.k0", "Line");
		ChartManager.getInstance().getChart().setCurrentPeriod("01m");
		var d = ChartSettings.get();
		d.charts.period = e;
		ChartSettings.save();
		return
	}
	ChartManager.getInstance().getChart().strIsLine = false;
	var f = GLOBAL_VAR.tagMapPeriod[e];
	ChartManager.getInstance().setChartStyle("frame0.k0", ChartSettings.get().charts.chartStyle);
	ChartManager.getInstance().getChart().setCurrentPeriod(f);
	var d = ChartSettings.get();
	d.charts.period = e;
	ChartSettings.save()
}
function switch_market_selected(c) {
	kline.reset(c);
	$(".market_chooser ul a").removeClass("selected");
	$(".market_chooser ul a[name='" + c + "']").addClass("selected");
	ChartManager.getInstance().getChart()._market_from = c;
	var d = ChartSettings.get();
	d.charts.market_from = c;
	ChartSettings.save()
}
function switch_market(c) {
	switch_market_selected(c);
	var d = ChartSettings.get();
	if (d.charts.period == "line") {
		ChartManager.getInstance().getChart().strIsLine = true;
		ChartManager.getInstance().setChartStyle("frame0.k0", "Line")
	} else {
		ChartManager.getInstance().getChart().strIsLine = false;
		ChartManager.getInstance().setChartStyle("frame0.k0", ChartSettings.get().charts.chartStyle)
	}
	ChartManager.getInstance().getChart().setMarketFrom(c)
}
function IsSupportedBrowers() {
	function b() {
		var a = document.createElement("canvas");
		return !!(a.getContext && a.getContext("2d"))
	}
	if (!b()) {
		return false
	}
	return true
}
function calcPeriodWeight(g) {
	var f = g;
	if (g != "line") {
		f = GLOBAL_VAR.periodMap[GLOBAL_VAR.tagMapPeriod[g]]
	}
	var h = ChartSettings.get().charts.period_weight;
	for (var e in h) {
		if (h[e] > h[f]) {
			h[e] -= 1
		}
	}
	h[f] = 8;
	ChartSettings.save();
	$("#chart_toolbar_periods_horz").find("li").each(function() {
		var b = $(this).attr("name");
		var a = b;
		if (b != "line") {
			a = GLOBAL_VAR.periodMap[GLOBAL_VAR.tagMapPeriod[b]]
		}
		if (h[a] == 0) {
			$(this).css("display", "none")
		} else {
			$(this).css("display", "inline-block")
		}
	})
};