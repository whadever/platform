/**
 * @license Fuse - https://github.com/krisk/Fuse/blob/master/LICENSE
 * @license fuzzyDropdown - https://github.com/zeusdeux/fuzzyDropdown/blob/master/LICENSE
 **/
!function(e,t){function i(t){var i,s=[];return e.each(t,function(){i=e(this),s.push({value:i.val(),text:i.text().trim()})}),s}e.fn.fuzzyDropdown=function(s){function l(){y.children("."+d.selectedClass).removeClass(d.selectedClass)}var n,o,a,d=e.extend({enableBrowserDefaultScroll:!1},s),r=e(this),c=e(d.mainContainer),h=e(c.children("div")[0]),v=h.children("span:first"),u=e(h.children("span")[1]),f=e(c.children("div")[1]),p=f.children("input:first"),y=f.children("ul:first"),C=r.children("option"),g=i(C),k=+new Date+"-no-results-found",w=new t(g,{keys:["text"],id:"value",location:d.location||0,threshold:d.threshold||.61,distance:d.distance||100,maxPatternLength:d.maxPatternLength||64,caseSensitive:d.caseSensitive||!1,includeScore:d.includeScore||!1,shouldSort:d.shouldSort||!0});if(r.children("option").length){console.debug("fuzzyDropdown: threshold is "+d.threshold),r.hide(),e(d.mainContainer+":hidden").length&&c.show(),n=r.children("option[selected]"),n=n.length?n:r.children("option:first"),v.attr("data-val",n.val()),v.text(n.text());for(var b=0;b<C.length;b++)a='<li data-val="'+C[b].value+'">'+C[b].text+"</li>",y.append(a);y.append('<li id="'+k+'" style="display:none;">No results found yet. Keep typing for more matches.</li>'),o=y.children("li"),p.keyup(function(){var t,i=e(this),s=i.val();""===s?(o.css("display","list-item"),e("#"+k).css("display","none")):(t=w.search(s),t.length?(o.css("display","none"),o.each(function(){for(var i=e(this),s=0;s<t.length;s++)i.attr("data-val")===""+t[s]&&i.css("display","list-item")})):(o.css("display","none"),e("#"+k).css("display","list-item")))}),h.click(function(e){e.preventDefault(),e.stopPropagation(),u.toggleClass(d.arrowUpClass),f.slideToggle(100),f.is(":visible")&&p.focus().select(),l()}),f.on("click","li",function(){var t=e(this);v.attr("data-val",t.attr("data-val")),v.text(t.text()),r.find("option:selected").prop("selected",!1),r.children("option[value="+t.attr("data-val")+"]").prop("selected",!0).change()}),e("body").click(function(){f.is(":visible")&&!p.is(":focus")&&h.click()}),p.keydown(function(t){return t.stopPropagation(),l(),y.children(":visible:first").get(0)===e("#"+k).get(0)?void l():void(40===t.keyCode&&(y.children(":visible:first").addClass(d.selectedClass),p.blur()))}),y.on("keydown","li",function(t){var i=e(this),s=y.children(":visible:first").get(0)===i.get(0),l=y.children(":visible:last").get(0)===i.get(0),n=i.next(),o=i.prev();if(t.preventDefault(),t.stopPropagation(),s&&38===t.keyCode)return i.removeClass(d.selectedClass),void p.focus().select();if(!l||40!==t.keyCode){if(40===t.keyCode){for(i.removeClass(d.selectedClass);!n.is(":visible");)n=n.next();return void n.addClass(d.selectedClass)}if(38===t.keyCode){for(i.removeClass(d.selectedClass);!o.is(":visible");)o=o.prev();return void o.addClass(d.selectedClass)}13===t.keyCode&&i.click()}}),e("body").on("keydown",function(t){var i;!f.is(":visible")||38!==t.keyCode&&40!==t.keyCode&&13!==t.keyCode||(d.enableBrowserDefaultScroll||t.preventDefault(),t.stopPropagation(),i=e.Event("keydown"),i.keyCode=t.keyCode,y.children("."+d.selectedClass).trigger(i))})}}}(window.jQuery,window.Fuse);
//# sourceMappingURL=src/fuzzyDropdown.min.map