! function(e) {
	function o(o) {
		"use strict";
		var i = e(o),
			r = ["powermail_input", "powermail_textarea", "powermail_select", "powermail_radio", "powermail_checkbox"];
		this.ajaxListener = function() {
			t(), e(_()).on("change", function() {
				t()
			})
		};
		var n = function(o) {
				if (void 0 !== o.todo) {
					for (var i in o.todo) {
						var r = e(".powermail_form_" + i);
						for (var n in o.todo[i]) {
							r.find(".powermail_fieldset_" + n);
							"hide" === o.todo[i][n]["#action"] && f(m(n, r)), "un_hide" === o.todo[i][n]["#action"] && p(m(n, r));
							for (var t in o.todo[i][n]) "hide" === o.todo[i][n][t]["#action"] && c(t, r), "un_hide" === o.todo[i][n][t]["#action"] && u(t, r)
						}
					}
					h()
				}
			},
			t = function() {
				var o = e(i.get(0)),
					r = o.find(":disabled").prop("disabled", !1),
					t = new FormData(i.get(0));
				/* fix for safari remove upload fields start */
				o.find('input[type="file"]').map(function(i, el){
					t.delete(el.name)
				})
				/* fix for safari remove upload fields end */
				r.prop("disabled", !0), e.ajax({
					type: "POST",
					url: l(),
					data: t,
					contentType: !1,
					processData: !1,
					success: function(e) {
						100 === e.loops && q("100 loops reached by parsing conditions and rules. Maybe there are conflicting conditions."), n(e)
					}
				})
			},
			a = function(e) {
				(e.prop("required") || e.data("parsley-required")) && (e.prop("required", !1), e.removeAttr("data-parsley-required"), e.data("powermailcond-required", "required"))
			},
			d = function(e) {
				"required" === e.data("powermailcond-required") && (y() ? e.prop("required", "required") : v() && e.prop("required", "required")), e.removeData("powermailcond-required")
			},
			u = function(e, o) {
				var i = o.find(".powermail_fieldwrap_" + e);
				i.show();
				var r = s(e, o);
				r.prop("disabled", !1), d(r)
			},
			c = function(e, o) {
				var i = o.find(".powermail_fieldwrap_" + e);
				i.hide();
				var r = s(e, o);
				r.prop("disabled", !0), a(r)
			},
			p = function(e) {
				e.show()
			},
			f = function(e) {
				e.hide()
			},
			l = function() {
				var o = e("*[data-condition-uri]").data("condition-uri");
				return void 0 === o && q("Tag with data-condition-uri not found. Maybe TypoScript was not included."), o
			},
			s = function(e, o) {
				return o.find('[name^="tx_powermail_pi1[field][' + e + ']"]').not('[type="hidden"]')
			},
			m = function(e, o) {
				return o.find(".powermail_fieldset_" + e)
			},
			w = function(e, o, i) {
				o = "undefined" != typeof o ? o : "", i = "undefined" != typeof i ? i : ",";
				for (var r = "", n = 0; n < e.length; n++) n > 0 && (r += i), r += o + e[n];
				return r
			},
			_ = function() {
				return w(r, ".")
			},
			v = function() {
				return "data-parsley-validate" === i.data("parsley-validate")
			},
			y = function() {
				return "html5" === i.data("validate")
			},
			h = function() {
				v() && (i.parsley().destroy(), i.parsley())
			},
			q = function(e) {
				"object" == typeof console && ("string" == typeof e && (e = "powermail_cond: " + e), console.log(e))
			}
	}
	e(document).ready(function() {
		e("form.powermail_form").each(function() {
			new o(this).ajaxListener()
		})
	})
}(jQuery);