<?php 
function smilies_reset() {
    global $wpsmiliestrans, $wp_smiliessearch;
 
    // don't bother setting up smilies if they are disabled
    if ( !get_option( 'use_smilies' ) )
        return;
    $wpsmiliestrans = array(
'[呵呵]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f01.png",
'[哈哈]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f02.png",
'[吐舌]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f03.png",
'[啊]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f04.png",
'[酷]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f05.png",
'[怒]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f06.png",
'[开心]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f07.png",
'[汗]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f08.png",
'[泪]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f09.png",
'[黑线]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f10.png",
'[鄙视]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f11.png",
'[不高兴]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f12.png",
'[真棒]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f13.png",
'[钱]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f14.png",
'[疑问]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f15.png",
'[阴险]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f16.png",
'[吐]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f17.png",
'[咦]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f18.png",
'[委屈]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f19.png",
'[花心]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f20.png",
'[呼]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f21.png",
'[笑眼]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f22.png",
'[冷]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f23.png",
'[太开心]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f24.png",
'[滑稽]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f25.png",
'[勉强]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f26.png",
'[狂汗]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f27.png",
'[乖]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f28.png",
'[睡觉]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f29.png",
'[惊哭]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f30.png",
'[升起]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f31.png",
'[惊讶]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f32.png",
'[喷]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f33.png",
'[爱心]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f34.png",
'[心碎]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f35.png",
'[玫瑰]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f36.png",
'[礼物]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f37.png",
'[彩虹]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f38.png",
'[星星月亮]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f39.png",
'[太阳]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f40.png",
'[钱币]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f41.png",
'[灯泡]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f42.png",
'[茶杯]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f43.png",
'[蛋糕]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f44.png",
'[音乐]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f45.png",
'[haha]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f46.png",
'[胜利]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f47.png",
'[大拇指]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f48.png",
'[弱]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f49.png",
'[OK]' => "https://tb2.bdstatic.com/tb/editor/images/face/i_f50.png",
    );
}

?>