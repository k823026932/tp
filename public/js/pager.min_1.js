$.fn.readyPager=function(a,e){var s;if(s={name:"$.fn.readyPager()",callback:e},a)switch($.type(a)){case"object":$.extend(s,a),s.name="$.fn.readyPager()";break;case"function":s.callback=a}return this.length?this.each(function(){var a;return a=$(this),a.delegate("span.pager:not(.active)","click",function(){return s.callback($(this).data().page)}),s.addon?a.delegate("input.ipt-pager","focus",function(){return $(this).select()}).delegate("input.ipt-pager","keyup",function(){var a,e,s;return a=$(this),e=$.trim(a.val()).length,s=e?32+8*(e-1):32,s=(s>240?240:void 0)-6,a.css({width:s})}).delegate("input.ipt-pager","keydown",function(a){var e,s;return s=$(this),e=s.siblings("button.btn-pager").eq(0),13===a.which?e.click():-1===$.inArray(a.which,[8,35,36,37,39,48,49,50,51,52,53,54,55,56,57,58,96,97,98,99,100,101,102,103,104,105])?!1:void 0}).delegate("button.btn-pager","click",function(){var a,e,n,t,r,i;return a=$(this),e=a.siblings("input.ipt-pager").eq(0),t=null!=(r=parseInt(e.val()))?r:1,n=null!=(i=e.data().max)?i:65535,1>t&&(t=1),t>n&&(t=n),s.callback(t)}):void 0}):$()},$.makePager=function(a){var e,s;if(e={num:1,count:0,size:20,"long":7,haslast:!1,hasfirst:!0,hasjumpPage:!1},a&&$.extend(e,a),s={total:e.totalPage||Math.ceil(e.count/e.size),num:e.num,hasjumpPage:e.hasjumpPage},location.href.indexOf("spn")<0&&a&&!a.hash&&$.Hash({page:e.num}),s.total>1){if(s.fore=s.num>=5?'<span class="pager pager-fore" data-page="'+(s.num-1)+'"><i class="icon icon-chevron-left" title="上一页"></i></span>':"",s.hind=s.num!==s.total?'<span class="pager pager-hind" data-page="'+((0|s.num)+1)+'"><i class="icon icon-arrow-slim-right" title="下一页"></i></span>':"",e.haslast?s.last=s.num!==s.total?'<span class="pager pager-first" data-page="'+s.total+'"><i class="icon icon-step-forward" title="最末"></i></span>':"":s.last="",e.hasfirst?s.first=s.num>=5?'<span class="pager pager-last" data-page="1"><i class="icon icon-step-backward" title="最初"></i></span>':"":s.first="",s.here='<span class="pager pager-here active" data-aa="11" data-page="'+s.num+'">'+s.num+"</span>",s.fores="",s.num<=4)for(var n=1;n<s.num;n++)s.fores=s.fores+'<span class="pager pager-hinds" data-page="'+n+'">'+n+"</span>";else for(var n=s.num-3;n<s.num;n++)s.fores=s.fores+'<span class="pager pager-hinds" data-page="'+n+'">'+n+"</span>";if(s.hinds="",s.total<e["long"]&&(e["long"]=s.total),s.num<=4)for(var n=s.num+1;n<=e["long"];n++)s.hinds=s.hinds+'<span class="pager pager-hinds" data-page="'+n+'">'+n+"</span>";else for(var n=s.num+1;n<=s.num+3;n++)n<=s.total&&(s.hinds=s.hinds+'<span class="pager pager-hinds" data-page="'+n+'">'+n+"</span>");return s.hasjumpPage?s.html='<div id="'+(e.id||"")+'" class="area-pager '+(e["class"]||"")+'">'+(e.before||"")+s.first+s.fore+s.fores+s.here+s.hinds+s.hind+s.last+'<span class="hint">共'+s.total+"页</span>"+(e.after||"")+'<span class="clearfix"></span> </div>':s.html='<div id="'+(e.id||"")+'" class="area-pager '+(e["class"]||"")+'">'+(e.before||"")+s.first+s.fore+s.fores+s.here+s.hinds+s.hind+s.last+'<span class="hint">当前页：'+s.num+"/"+s.total+"页</span>"+(e.after||"")+'<span class="clearfix"></span> </div>'}return""};