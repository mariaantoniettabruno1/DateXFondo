(()=>{var e={};e.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),(()=>{var t;e.g.importScripts&&(t=e.g.location+"");var o=e.g.document;if(!t&&o&&(o.currentScript&&(t=o.currentScript.src),!t)){var r=o.getElementsByTagName("script");r.length&&(t=r[r.length-1].src)}if(!t)throw new Error("Automatic publicPath is not supported in this browser");t=t.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),e.p=t+"../../../"})(),e.p=window.zionBuilderPaths[{}.appName],(()=>{"use strict";const e=zb.vue,t={class:"zb-el-search__form"},o={key:1,type:"hidden",name:"post_type",value:"product"},r={name:"search",props:["options","api","element"],computed:{getPlaceholder(){return this.options.placeholder_text||"Search for articles"},getButtonText(){return this.options.search_text||"Search"},showButton(){return!!this.options.show_button&&this.options.show_button}},methods:{},render:function(r,n,s,c,a,l){return(0,e.openBlock)(),(0,e.createBlock)("div",null,[(0,e.renderSlot)(r.$slots,"start"),(0,e.createVNode)("form",t,[(0,e.createVNode)("input",{type:"text",maxlength:"30",name:"s",class:["zb-el-search__input",s.api.getStyleClasses("input_styles")],placeholder:l.getPlaceholder},null,10,["placeholder"]),l.showButton?((0,e.openBlock)(),(0,e.createBlock)("button",{key:0,type:"submit",alt:"Search",class:["zb-el-search__submit",s.api.getStyleClasses("button_styles")],value:"Search"},(0,e.toDisplayString)(l.getButtonText),3)):(0,e.createCommentVNode)("v-if",!0),s.options.woocommerce?((0,e.openBlock)(),(0,e.createBlock)("input",o)):(0,e.createCommentVNode)("v-if",!0)]),(0,e.renderSlot)(r.$slots,"end")])}};(0,zb.editor.registerElementComponent)({elementType:"search",component:r})})()})();