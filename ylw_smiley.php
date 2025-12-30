<?php 
function smilies_reset() {
    global $wpsmiliestrans, $wp_smiliessearch;
 
    // don't bother setting up smilies if they are disabled
    if ( !get_option( 'use_smilies' ) )
        return;
    $wpsmiliestrans = array(
'[呵呵]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f01.png",
'[哈哈]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f02.png",
'[吐舌]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f03.png",
'[啊]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f04.png",
'[酷]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f05.png",
'[怒]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f06.png",
'[开心]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f07.png",
'[汗]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f08.png",
'[泪]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f09.png",
'[黑线]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f10.png",
'[鄙视]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f11.png",
'[不高兴]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f12.png",
'[真棒]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f13.png",
'[钱]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f14.png",
'[疑问]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f15.png",
'[阴险]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f16.png",
'[吐]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f17.png",
'[咦]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f18.png",
'[委屈]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f19.png",
'[花心]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f20.png",
'[呼]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f21.png",
'[笑眼]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f22.png",
'[冷]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f23.png",
'[太开心]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f24.png",
'[滑稽]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f25.png",
'[勉强]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f26.png",
'[狂汗]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f27.png",
'[乖]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f28.png",
'[睡觉]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f29.png",
'[惊哭]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f30.png",
'[升起]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f31.png",
'[惊讶]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f32.png",
'[喷]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f33.png",
'[爱心]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f34.png",
'[心碎]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f35.png",
'[玫瑰]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f36.png",
'[礼物]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f37.png",
'[彩虹]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f38.png",
'[星星月亮]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f39.png",
'[太阳]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f40.png",
'[钱币]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f41.png",
'[灯泡]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f42.png",
'[茶杯]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f43.png",
'[蛋糕]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f44.png",
'[音乐]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f45.png",
'[haha]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f46.png",
'[胜利]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f47.png",
'[大拇指]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f48.png",
'[弱]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f49.png",
'[OK]' => "http://tb2.bdstatic.com/tb/editor/images/face/i_f50.png",
    );
}

?>