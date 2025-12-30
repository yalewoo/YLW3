function show_smilies(){
document.getElementById("ylw_smilies_box").style.display="block";

if (!document.getElementById("has_loaded_smilies"))
{
    var s = '<span id="has_loaded_smilies"></span><a href="javascript:grin(\'[呵呵]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f01.png"  alt="呵呵" /></a><a href="javascript:grin(\'[哈哈]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f02.png"  alt="哈哈" /></a><a href="javascript:grin(\'[吐舌]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f03.png"  alt="吐舌" /></a><a href="javascript:grin(\'[啊]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f04.png"  alt="啊" /></a><a href="javascript:grin(\'[酷]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f05.png"  alt="酷" /></a><a href="javascript:grin(\'[怒]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f06.png"  alt="怒" /></a><a href="javascript:grin(\'[开心]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f07.png"  alt="开心" /></a><a href="javascript:grin(\'[汗]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f08.png"  alt="汗" /></a><a href="javascript:grin(\'[泪]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f09.png"  alt="泪" /></a><a href="javascript:grin(\'[黑线]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f10.png"  alt="黑线" /></a><a href="javascript:grin(\'[鄙视]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f11.png"  alt="鄙视" /></a><a href="javascript:grin(\'[不高兴]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f12.png"  alt="不高兴" /></a><a href="javascript:grin(\'[真棒]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f13.png"  alt="真棒" /></a><a href="javascript:grin(\'[钱]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f14.png"  alt="钱" /></a><a href="javascript:grin(\'[疑问]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f15.png"  alt="疑问" /></a><a href="javascript:grin(\'[阴险]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f16.png"  alt="阴险" /></a><a href="javascript:grin(\'[吐]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f17.png"  alt="吐" /></a><a href="javascript:grin(\'[咦]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f18.png"  alt="咦" /></a><a href="javascript:grin(\'[委屈]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f19.png"  alt="委屈" /></a><a href="javascript:grin(\'[花心]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f20.png"  alt="花心" /></a><a href="javascript:grin(\'[呼]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f21.png"  alt="呼" /></a><a href="javascript:grin(\'[笑眼]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f22.png"  alt="笑眼" /></a><a href="javascript:grin(\'[冷]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f23.png"  alt="冷" /></a><a href="javascript:grin(\'[太开心]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f24.png"  alt="太开心" /></a><a href="javascript:grin(\'[滑稽]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f25.png"  alt="滑稽" /></a><a href="javascript:grin(\'[勉强]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f26.png"  alt="勉强" /></a><a href="javascript:grin(\'[狂汗]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f27.png"  alt="狂汗" /></a><a href="javascript:grin(\'[乖]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f28.png"  alt="乖" /></a><a href="javascript:grin(\'[睡觉]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f29.png"  alt="睡觉" /></a><a href="javascript:grin(\'[惊哭]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f30.png"  alt="惊哭" /></a><a href="javascript:grin(\'[升起]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f31.png"  alt="升起" /></a><a href="javascript:grin(\'[惊讶]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f32.png"  alt="惊讶" /></a><a href="javascript:grin(\'[喷]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f33.png"  alt="喷" /></a><a href="javascript:grin(\'[爱心]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f34.png"  alt="爱心" /></a><a href="javascript:grin(\'[心碎]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f35.png"  alt="心碎" /></a><a href="javascript:grin(\'[玫瑰]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f36.png"  alt="玫瑰" /></a><a href="javascript:grin(\'[礼物]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f37.png"  alt="礼物" /></a><a href="javascript:grin(\'[彩虹]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f38.png"  alt="彩虹" /></a><a href="javascript:grin(\'[星星月亮]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f39.png"  alt="星星月亮" /></a><a href="javascript:grin(\'[太阳]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f40.png"  alt="太阳" /></a><a href="javascript:grin(\'[钱币]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f41.png"  alt="钱币" /></a><a href="javascript:grin(\'[灯泡]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f42.png"  alt="灯泡" /></a><a href="javascript:grin(\'[茶杯]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f43.png"  alt="茶杯" /></a><a href="javascript:grin(\'[蛋糕]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f44.png"  alt="蛋糕" /></a><a href="javascript:grin(\'[音乐]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f45.png"  alt="音乐" /></a><a href="javascript:grin(\'[haha]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f46.png"  alt="haha" /></a><a href="javascript:grin(\'[胜利]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f47.png"  alt="胜利" /></a><a href="javascript:grin(\'[大拇指]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f48.png"  alt="大拇指" /></a><a href="javascript:grin(\'[弱]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f49.png"  alt="弱" /></a><a href="javascript:grin(\'[OK]\')"  ><img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f50.png"  alt="OK" /></a>';
    document.getElementById("ylw_smilies_box").innerHTML = s;
}

//alert(document.getElementById("div").style.display)
}

function hidden_smilies(){
document.getElementById("ylw_smilies_box").style.display="none";
//alert(document.getElementById("div").style.display)
}

document.onclick = function (event)    
        {       
            var e = event || window.event;    
            var elem = e.srcElement||e.target;    
                     
            while(elem)    
            {     
                if(elem.id == "button-insert-smilies")    
                {    
                        return;    
                }    
                elem = elem.parentNode;         
            }    
            //隐藏div的方法    
            hidden_smilies();    
        }  



function grin(tag) {
      var myField;
      tag = ' ' + tag + ' ';
        if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
        myField = document.getElementById('comment');
      } else {
        return false;
      }
      if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = tag;
        myField.focus();
      }
      else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        var cursorPos = startPos;
        myField.value = myField.value.substring(0, startPos)
                + tag
                + myField.value.substring(endPos, myField.value.length);
        cursorPos += tag.length;
        myField.focus();
        myField.selectionStart = cursorPos;
        myField.selectionEnd = cursorPos;
      }      else {
        myField.value += tag;
        myField.focus();
      }
    }




function show_bdsharebox(){
// document.getElementById("ylw_smilies_box").style.display="block";
    var s = '<div>分享到：</div><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_more" data-cmd="more"></a>';
    document.getElementById("bdsharebuttonbox").innerHTML = s;

    window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"32"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
}

