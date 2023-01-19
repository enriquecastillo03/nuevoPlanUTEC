/*
Name: Tabbed jQuery Plugin
By: Copyright (c) 2010 Nathan Searles
Web Site: (http://nathansearles.com)
Project Page: (http://nathansearles.com/tabbed/)
GitHub: (https://github.com/nathansearles/Tabbed)
Licence: Released under the MIT and GPL licenses.
*/

if (typeof jQuery != "undefined") {
    jQuery(function (a) {
        a.fn.extend({
            tabbed: function (b) {
                var c = a.extend({}, a.fn.tabbed.defaults, b);
                return this.each(function () {
                    if (a.fn.jquery < "1.3.1") {
                        return
                    }
                    var i = a(this);
                    var h = a.metadata ? a.extend({}, c, i.metadata()) : c;
                    var f = i.find("> div ul li");
                    var g = "." + h.tabcontent;
                    var e = 0,
                        d = 0;
                    a(g, i).hide();
                    a("li:first", i).addClass("active").css({
                        display: "block"
                    });
                    a(g + ":first", i).show();
                    a(f, i).bind("click", function () {
                        if (a(this).hasClass("active")) {
                            return false
                        }
                        a(f, i).removeClass("active");
                        a(this, i).addClass("active");
                        a(g, i).hide();
                        e = a(this, i).find("a").attr("href");
                        a(e, i).fadeIn(h.speed, function () {
                            removeFilter(this)
                        });
                        d = a(e, i).outerHeight();
                        a(e, i).parent().css({
                            height: d
                        });
                        return false
                    })
                })
            }
        });
        a.fn.tabbed.defaults = {
            tabcontent: "tabcontent",
            speed: 0
        }
    });

    function removeFilter(a) {
        if (a.style.removeAttribute) {
            a.style.removeAttribute("filter")
        }
    }
};