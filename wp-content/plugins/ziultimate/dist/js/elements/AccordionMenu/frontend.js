(()=>{var e,t={};t.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),(()=>{var e;t.g.importScripts&&(e=t.g.location+"");var n=t.g.document;if(!e&&n&&(n.currentScript&&(e=n.currentScript.src),!e)){var r=n.getElementsByTagName("script");r.length&&(e=r[r.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),t.p=e+"../../../"})(),t.p=window.zionBuilderPaths[{}.appName],"undefined"!=typeof zb&&zb.hooks.on("zionbuilder/server_component/rendered",(function(e,t,n){const r=window.ZionBuilderFrontend.getScript("zuAccordionMenu");r&&r.initAccordionMenu(jQuery(e).find("nav"))})),e=window.jQuery,expandSubMenuItems=function(t){t.stopPropagation(),t.preventDefault(),doExpandItems(e(this))},doExpandItems=function(e){let t=e.closest("nav").attr("data-acrd-toggle-duration"),n="false"==e.attr("aria-expanded")?"true":"false",r="false"==e.attr("aria-pressed")?"true":"false",o="true"==e.attr("aria-hidden")?"false":"true";e.attr("aria-expanded",n),e.attr("aria-pressed",r),e.attr("aria-hidden",o),e.closest("li.menu-item-has-children").children(".sub-menu").eq(0).slideToggle(parseInt(t)),"false"==o?e.addClass("acrd-menu-open"):e.removeClass("acrd-menu-open")},expandHashLinkSubMenuItems=function(t){t.stopPropagation(),t.preventDefault(),arrowBtn=e(this).children(".zu-menu-items-arrow"),doExpandItems(arrowBtn)},getParents=function(e,t){Element.prototype.matches||(Element.prototype.matches=Element.prototype.matchesSelector||Element.prototype.mozMatchesSelector||Element.prototype.msMatchesSelector||Element.prototype.oMatchesSelector||Element.prototype.webkitMatchesSelector||function(e){for(var t=(this.document||this.ownerDocument).querySelectorAll(e),n=t.length;--n>=0&&t.item(n)!==this;);return n>-1});for(var n=[];e&&e!==document;e=e.parentNode)t?e.matches(t)&&n.push(e):n.push(e);return n},window.ZionBuilderFrontend.registerScript("zuAccordionMenu",{run(t){const n=e(t).find(".zb-el-zuAccordionMenu");n.length>0&&(e("li:not(.menu-item-has-children) .zu-menu-items-arrow").remove(),n.each(((t,n)=>{const r=e(n);this.initAccordionMenu(r)})))},initAccordionMenu(t){t.find(".menu-item-has-children > a").each(((t,n)=>{let r=e(n).children(".zu-menu-items-arrow");void 0===r&&null==r||r.attr("aria-label","Sub Menu of "+e(n).attr("data-title")),"#"===e(n).attr("href")?e(n).on("click",expandHashLinkSubMenuItems):r.on("click",expandSubMenuItems)}));const n=t.children("nav").find(".current-menu-item");n&&n.each(((e,t)=>{const n=getParents(t,".current-menu-ancestor");n&&n.forEach((e=>{link=e.querySelector("a"),arrowBtn=link.closest(".menu-item-has-children > a").querySelector(".zu-menu-items-arrow"),arrowBtn.click()}))}))}})})();