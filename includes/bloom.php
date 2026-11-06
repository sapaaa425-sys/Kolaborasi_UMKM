<?php
// Ilustrasi buket berbentuk SVG (tanpa emoji). $v memilih variasi warna dan susunan bunga.
function bloom_svg(int $v = 0): string {
    $pal = [['#E6397A','#F7E37A'],['#8B6FD6','#FFF3B0'],['#FF8FB1','#14281D'],['#F7E37A','#E6397A']];
    $sets = [
        [[100,64,36],[58,104,27],[142,100,29],[100,124,24]],
        [[74,74,32],[130,64,29],[100,118,34],[50,130,21],[150,126,22]],
        [[100,58,30],[62,92,30],[138,92,30],[84,132,24],[120,132,24]],
    ];
    $f = $sets[$v % 3]; $o = '';
    foreach ($f as [$x, $y, $r]) {
        $o .= "<path d='M100 214 Q" . (($x + 100) / 2) . ' ' . (($y + 214) / 2) . " $x " . ($y + $r * .4) . "' stroke='#2F6B4A' stroke-width='4' fill='none' stroke-linecap='round'/>";
    }
    foreach ([[62,170,-40],[138,172,40],[78,150,-65],[124,152,65]] as [$x, $y, $a]) {
        $o .= "<ellipse cx='$x' cy='$y' rx='9' ry='24' fill='#3E8A5E' transform='rotate($a $x $y)'/>";
    }
    foreach ($f as $i => [$x, $y, $r]) {
        [$pc, $cc] = $pal[($v + ($i % 2)) % 4];
        for ($k = 0; $k < 7; $k++) {
            $o .= "<ellipse cx='$x' cy='" . ($y - $r * .62) . "' rx='" . ($r * .36) . "' ry='" . ($r * .62) . "' fill='$pc' transform='rotate(" . ($k * 360 / 7) . " $x $y)'/>";
        }
        $o .= "<circle cx='$x' cy='$y' r='" . ($r * .32) . "' fill='$cc'/>";
    }
    $o .= "<path d='M48 150 L152 150 L118 238 L82 238 Z' fill='#FFFBF6'/><path d='M48 150 L152 150 L142 172 L58 172 Z' fill='#14281D' opacity='.1'/><path d='M84 150 Q100 166 116 150' stroke='#E6397A' stroke-width='6' fill='none' stroke-linecap='round'/>";
    return "<svg viewBox='0 0 200 240' xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Ilustrasi buket bunga'>$o</svg>";
}
