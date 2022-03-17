
			(function($) {
				window.ZionBuilderFrontend = {
					scripts: {},
					registerScript: function (scriptId, scriptCallback) {
						this.scripts[scriptId] = scriptCallback;
					},
					getScript(scriptId) {
						return this.scripts[scriptId]
					},
					unregisterScript: function(scriptId) {
						delete this.scripts[scriptId];
					},
					run: function() {
						var that = this;
						var $scope = $(document)
						Object.keys(this.scripts).forEach(function(scriptId) {
							var scriptObject = that.scripts[scriptId];
							scriptObject.run( $scope );
						})
					}
				};

				(()=>{var t={};t.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(t){if("object"==typeof window)return window}}(),(()=>{var e;t.g.importScripts&&(e=t.g.location+"");var i=t.g.document;if(!e&&i&&(i.currentScript&&(e=i.currentScript.src),!e)){var r=i.getElementsByTagName("script");r.length&&(e=r[r.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),t.p=e+"../../../"})(),t.p=window.zionBuilderPaths[{}.appName],$(document).ready((function(){var t=function(t){return 1==t.which||13==t.which||32==t.which||null==t.which};$(".zb-el-zuBackToTop").each((function(){var e=JSON.parse($(this).attr("data-zuscroll-config")),i=parseInt(e.speed),r=e.easing;if($(this).hasClass("jump-to-section")){var o=e.offset,n=e.selector;$(this).on("click keypress",(function(e){e.preventDefault(),t(e)&&$("html, body").animate({scrollTop:$(n).offset().top-parseInt(o)},i,r)}))}else{var s=e.visibility;$(this).on("click keypress",(function(e){e.preventDefault(),t(e)&&$("html, body").animate({scrollTop:0},i,r)}));var c=$(this);$(window).scroll((function(){$(this).scrollTop()>=s?c.addClass("backtotop-visible"):c.removeClass("backtotop-visible")}))}}))}))})();(()=>{var e={};e.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),(()=>{var n;e.g.importScripts&&(n=e.g.location+"");var t=e.g.document;if(!n&&t&&(t.currentScript&&(n=t.currentScript.src),!n)){var i=t.getElementsByTagName("script");i.length&&(n=i[i.length-1].src)}if(!n)throw new Error("Automatic publicPath is not supported in this browser");n=n.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),e.p=n+"../../../"})(),e.p=window.zionBuilderPaths[{}.appName],(()=>{"use strict";var e;e=window.jQuery,window.ZionBuilderFrontend.registerScript("menu",{run(n){const t=e(n).find(".zb-menu").addBack(".zb-menu");t.length>0&&e.each(t,(function(n,t){const i=e(t),s=void 0!==i.data("info")&&JSON.parse(i.attr("data-info"));!function(n,t){if(n.attr("zb-menu-enabled"))return;const i=n,s=i.find(".zb-menuPosition--centered"),o=i.find(".zb-menuWidth--full"),r=e(window),u=e("body"),a=i.find(".js-zb-mobile-menu-trigger").first(),c=i.find(".zb-menu-container");let m=!1;function l(){e(this).children(".sub-menu").css("max-width",`${u.outerWidth()}px`)}function d(n){const t=e(this),i=t.children(".sub-menu"),s=t.offset().left,o=u.outerWidth()/2-s-i.outerWidth()/2;i.css("left",`${o}px`)}function f(){r.width()<=t.breakpoint?(m||(i.addClass("zb-menu-mobile--active"),i.find(".menu-item-has-children > .menu-link").on("click",b),m=!0),s.off("mouseover",d),o.off("mouseover",l),s.children(".sub-menu").css("left",""),o.children(".sub-menu").css("max-width",""),m&&t.mobile_menu_fullwidth&&p()):(s.on("mouseover",d),o.on("mouseover",l),i.removeClass("zb-menu-mobile--active"),i.removeClass("zb-menu-trigger--active"),c.css("left",""),i.find(".menu-item-has-children > .menu-link").off("click",b),i.find(".zb-menu--item--expand").removeClass("zb-menu--item--expand"),m=!1)}function b(n){const t=e(this).next(".sub-menu");t&&(t.hasClass("zb-menu--item--expand")?t.slideUp("fast",(function(){e(this).find(".zb-menu--item--expand").slideUp("fast",(function(){e(this).removeClass("zb-menu--item--expand")})),e(this).removeClass("zb-menu--item--expand")})):t.slideDown("fast").addClass("zb-menu--item--expand"),n.preventDefault())}function h(e){e.preventDefault(),e.stopPropagation(),i.hasClass("zb-menu-trigger--active")?(i.removeClass("zb-menu-trigger--active"),c.css("left","")):(i.addClass("zb-menu-trigger--active"),t.mobile_menu_fullwidth&&p())}function p(){const e=i.offset().left;c.css("left",`-${e}px`)}function v(e){e.preventDefault()}n.attr("zb-menu-enabled",!0),r.on("resize",f),a.on("click",h),i.find('a.main-menu-link[href="#"]').on("click",v),f()}(e(t),s)}))}})})()})();(()=>{var t,r={};r.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(t){if("object"==typeof window)return window}}(),(()=>{var t;r.g.importScripts&&(t=r.g.location+"");var e=r.g.document;if(!t&&e&&(e.currentScript&&(t=e.currentScript.src),!t)){var n=e.getElementsByTagName("script");n.length&&(t=n[n.length-1].src)}if(!t)throw new Error("Automatic publicPath is not supported in this browser");t=t.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),r.p=t+"../../../"})(),r.p=window.zionBuilderPaths[{}.appName],t=window.jQuery,window.ZionBuilderFrontend.registerScript("animatedBurger",{run(r){const e=t(r).find(".zb-el-zuBurger").addBack(".zb-el-zuBurger");e.length>0&&e.each(((r,e)=>{const n=t(e);this.initAnimatedBurger(n)}))},initAnimatedBurger(r){let e="click";("ontouchstart"in window||window.navigator.msPointerEnabled||"ontouchstart"in document.documentElement)&&(e="touchstart"),r.on(e,(function(r){r.stopPropagation(),t(this).children(".hamburger").toggleClass("is-active"),t(this).find(".zu-burger-sub-menu").length>0&&t(this).find(".zu-burger-sub-menu").slideToggle()}))}})})();(()=>{var t,e={};e.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(t){if("object"==typeof window)return window}}(),(()=>{var t;e.g.importScripts&&(t=e.g.location+"");var o=e.g.document;if(!t&&o&&(o.currentScript&&(t=o.currentScript.src),!t)){var n=o.getElementsByTagName("script");n.length&&(t=n[n.length-1].src)}if(!t)throw new Error("Automatic publicPath is not supported in this browser");t=t.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),e.p=t+"../../../"})(),e.p=window.zionBuilderPaths[{}.appName],t=window.jQuery,window.ZionBuilderFrontend.registerScript("zuOffCanvas",{run(e){const o=t(e)[0].querySelectorAll(".zb-el-zuOffCanvas");o.length>0&&o.forEach((t=>{this.doOffCanvas(t)}))},doOffCanvas(e){var o=e.querySelector(".zu-off-canvas-panel"),n=e.getAttribute("data-trigger-selector"),r=e.getAttribute("data-ocp-disable-scroll"),i=e.getAttribute("data-ocp-reveal"),s=0,a=parseInt(e.getAttribute("data-ocp-delay-in")),c=e.querySelector(".zu-oc-backdrop"),d=e.getAttribute("data-ocp-position"),l=e.getAttribute("data-ocpanel-td"),u=t(document).height()>t(window).height(),m="ontouchstart"in document.documentElement;window.addEventListener("load",(function(t){g()})),window.addEventListener("keyup",(function(t){27===t.keyCode&&p()})),null!=c&&["click","touchstart"].forEach((function(t){c.addEventListener(t,(function(t){t.preventDefault(),p()}))}),!1);var b=function(){if(document.body.classList.contains("admin-bar"))if(e.classList.contains("zu-push-content")){let t=document.querySelector("#wpadminbar").offsetHeight;document.body.style.paddingTop=t+"px"}else wpadminbar.classList.add("hide-wp-admin-bar");if(e.classList.contains("zu-push-content")){let n=e.getAttribute("id")+"-"+d;document.body.classList.contains("admin-bar")&&document.getElementsByTagName("html")[0].classList.add("zu-remove-spacing"),t("#"+n).length<=0&&("right"==d&&t("head").append('<style type="text/css" id="'+n+'">body.'+n+"{-webkit-transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);-moz-transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);overflow-x: hidden}body."+n+".zu-ocp-toggled {-webkit-transform: translate(-"+t(o).outerWidth()+"px, 0);-moz-transform: translate(-"+t(o).outerWidth()+"px, 0);transform: translate(-"+t(o).outerWidth()+"px, 0);}</style>"),"left"==d&&t("head").append('<style type="text/css" id="'+n+'">body.'+n+"{-webkit-transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);-moz-transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);overflow-x: hidden}body."+n+".zu-ocp-toggled {-webkit-transform: translate("+t(o).outerWidth()+"px, 0);-moz-transform: translate("+t(o).outerWidth()+"px, 0);transform: translate("+t(o).outerWidth()+"px, 0);}</style>"),"top"==d&&t("head").append('<style type="text/css" id="'+n+'">body.'+n+"{-webkit-transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);-moz-transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);overflow-x: hidden}body."+n+".zu-ocp-toggled {-webkit-transform: translate(0, -"+t(o).outerHeight()+"px);-moz-transform: translate(0, -"+t(o).outerHeight()+"px);transform: translate(0, -"+t(o).outerHeight()+"px);}</style>"),"bottom"==d&&t("head").append('<style type="text/css" id="'+n+'">body.'+n+"{-webkit-transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);-moz-transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);transition: transform "+l+"ms cubic-bezier(0.77, 0, 0.175, 1);overflow-x: hidden}body."+n+".zu-ocp-toggled {-webkit-transform: translate(0,"+t(o).outerHeight()+"px);-moz-transform: translate(0,"+t(o).outerHeight()+"px);transform: translate(0,"+t(o).outerHeight()+"px);}</style>")),document.body.classList.add(n),document.body.classList.add("zu-ocp-toggled")}e.classList.remove("zu-hide-panel"),"yes"==r&&h()},p=function(){document.body.classList.remove("zu-ocp-toggled"),e.classList.add("zu-hide-panel");var o=!1;("yes"==r||document.body.classList.contains("admin-bar"))&&(o=!0),o&&(clearTimeout(s),s=setTimeout((function(){document.body.classList.remove("zu-disable-scroll"),document.body.classList.contains("admin-bar")&&(document.querySelector("#wpadminbar").classList.remove("hide-wp-admin-bar"),document.getElementsByTagName("html")[0].classList.remove("zu-remove-spacing"),document.body.style.paddingTop="0px"),e.classList.contains("zu-push-content")&&document.body.classList.remove(e.getAttribute("id")+"-"+d)}),e.getAttribute("data-ocpanel-td"))),t(".zb-el-zuBurger").length>0&&t(".zb-el-zuBurger").children(".hamburger").removeClass("is-active")},f=function(t){t.preventDefault(),e.classList.contains("zu-hide-panel")?b():p()},h=function(){u&&(m||t("#zu-disable-scroll").length<=0&&t("head").append('<style type="text/css" id="zu-disable-scroll">body.zu-disable-scroll{margin-right: '+(window.innerWidth-document.body.clientWidth)+"px!important;}</style>"),document.body.classList.add("zu-disable-scroll"))},g=function(){"yes"==i&&(clearTimeout(s),s=setTimeout((function(){b()}),a))};void 0!==n&&null!=n&&n.toString().split(",").forEach((function(t){document.querySelector(t).addEventListener("click",f),document.querySelector(t).addEventListener("touchstart",f)}))}})})();(()=>{var e,t={};t.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),(()=>{var e;t.g.importScripts&&(e=t.g.location+"");var n=t.g.document;if(!e&&n&&(n.currentScript&&(e=n.currentScript.src),!e)){var r=n.getElementsByTagName("script");r.length&&(e=r[r.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),t.p=e+"../../../"})(),t.p=window.zionBuilderPaths[{}.appName],"undefined"!=typeof zb&&zb.hooks.on("zionbuilder/server_component/rendered",(function(e,t,n){const r=window.ZionBuilderFrontend.getScript("zuAccordionMenu");r&&r.initAccordionMenu(jQuery(e).find("nav"))})),e=window.jQuery,expandSubMenuItems=function(t){t.stopPropagation(),t.preventDefault(),doExpandItems(e(this))},doExpandItems=function(e){let t=e.closest("nav").attr("data-acrd-toggle-duration"),n="false"==e.attr("aria-expanded")?"true":"false",r="false"==e.attr("aria-pressed")?"true":"false",o="true"==e.attr("aria-hidden")?"false":"true";e.attr("aria-expanded",n),e.attr("aria-pressed",r),e.attr("aria-hidden",o),e.closest("li.menu-item-has-children").children(".sub-menu").eq(0).slideToggle(parseInt(t)),"false"==o?e.addClass("acrd-menu-open"):e.removeClass("acrd-menu-open")},expandHashLinkSubMenuItems=function(t){t.stopPropagation(),t.preventDefault(),arrowBtn=e(this).children(".zu-menu-items-arrow"),doExpandItems(arrowBtn)},getParents=function(e,t){Element.prototype.matches||(Element.prototype.matches=Element.prototype.matchesSelector||Element.prototype.mozMatchesSelector||Element.prototype.msMatchesSelector||Element.prototype.oMatchesSelector||Element.prototype.webkitMatchesSelector||function(e){for(var t=(this.document||this.ownerDocument).querySelectorAll(e),n=t.length;--n>=0&&t.item(n)!==this;);return n>-1});for(var n=[];e&&e!==document;e=e.parentNode)t?e.matches(t)&&n.push(e):n.push(e);return n},window.ZionBuilderFrontend.registerScript("zuAccordionMenu",{run(t){const n=e(t).find(".zb-el-zuAccordionMenu");n.length>0&&(e("li:not(.menu-item-has-children) .zu-menu-items-arrow").remove(),n.each(((t,n)=>{const r=e(n);this.initAccordionMenu(r)})))},initAccordionMenu(t){t.find(".menu-item-has-children > a").each(((t,n)=>{let r=e(n).children(".zu-menu-items-arrow");void 0===r&&null==r||r.attr("aria-label","Sub Menu of "+e(n).attr("data-title")),"#"===e(n).attr("href")?e(n).on("click",expandHashLinkSubMenuItems):r.on("click",expandSubMenuItems)}));const n=t.children("nav").find(".current-menu-item");n&&n.each(((e,t)=>{const n=getParents(t,".current-menu-ancestor");n&&n.forEach((e=>{link=e.querySelector("a"),arrowBtn=link.closest(".menu-item-has-children > a").querySelector(".zu-menu-items-arrow"),arrowBtn.click()}))}))}})})();

				window.ZionBuilderFrontend.run();

			})(jQuery);
			