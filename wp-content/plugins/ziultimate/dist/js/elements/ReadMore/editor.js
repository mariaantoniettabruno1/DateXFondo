(()=>{var e={};e.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),(()=>{var t;e.g.importScripts&&(t=e.g.location+"");var o=e.g.document;if(!t&&o&&(o.currentScript&&(t=o.currentScript.src),!t)){var n=o.getElementsByTagName("script");n.length&&(t=n[n.length-1].src)}if(!t)throw new Error("Automatic publicPath is not supported in this browser");t=t.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),e.p=t+"../../../"})(),e.p=window.zionBuilderPaths[{}.appName],(()=>{"use strict";const e=zb.editor,t=zb.vue,o={class:"zu-read-more-btns"},n={class:"zu-read-more-btn",role:"button"},s={class:"rm-link-wrapper"},r={class:"zu-more-text zu-text"},i={class:"zu-read-less-btn zu-rm-link-toggle",role:"button"},a={class:"rm-link-wrapper"},l={class:"zu-less-text zu-text"},c={name:"zu_read_more",props:["options","api","element"],computed:{getElementOptions(){return JSON.stringify({height:this.options.collapsed_height,speed:this.options.transition_speed,show_shadow:this.options.show_shadow})}},render:function(e,c,p,u,m,d){const g=(0,t.resolveComponent)("SortableContent"),_=(0,t.resolveComponent)("ElementIcon");return"zu"!=p.options.el_valid&&null!=p.options.el_valid?((0,t.openBlock)(),(0,t.createBlock)("div",{key:0,class:{"ru-expand-content":p.options.builder_preview}},[(0,t.renderSlot)(e.$slots,"start"),(0,t.createVNode)(g,{key:p.element.uid,element:p.element,class:["zu-rm-content zu-ru-overflow",{"zurm-show-shadow":p.options.show_shadow}],"data-ru-config":d.getElementOptions},null,8,["element","class","data-ru-config"]),(0,t.createVNode)("span",o,[(0,t.createVNode)("span",n,[(0,t.createVNode)("span",s,[p.options.more_btn_icon?((0,t.openBlock)(),(0,t.createBlock)(_,(0,t.mergeProps)({key:0,class:["more-btn-icon zu-rm-icon",p.api.getStyleClasses("more_btn_icon")],iconConfig:p.options.more_btn_icon},p.api.getAttributesForTag("more_btn_icon")),null,16,["class","iconConfig"])):(0,t.createCommentVNode)("",!0),(0,t.createVNode)("span",r,(0,t.toDisplayString)(p.options.more_text),1)])]),(0,t.createVNode)("span",i,[(0,t.createVNode)("span",a,[p.options.less_btn_icon?((0,t.openBlock)(),(0,t.createBlock)(_,(0,t.mergeProps)({key:0,class:["less-btn-icon zu-rm-icon",p.api.getStyleClasses("less_btn_icon")],iconConfig:p.options.less_btn_icon},p.api.getAttributesForTag("less_btn_icon")),null,16,["class","iconConfig"])):(0,t.createCommentVNode)("",!0),(0,t.createVNode)("span",l,(0,t.toDisplayString)(p.options.less_text),1)])])]),(0,t.renderSlot)(e.$slots,"end")],2)):(0,t.createCommentVNode)("",!0)}};(0,e.registerElementComponent)({elementType:"zu_read_more",component:c})})()})();