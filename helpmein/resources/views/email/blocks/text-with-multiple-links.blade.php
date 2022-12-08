@php
foreach ($links as $k => $link) {
    $link = "<a href=\"{$link['href']}\" style='font-family: Arial,serif;font-size:16px;line-height:20px;color:#6977D2;'>{$link['linkText']}</a>";
    $text = str_replace("{{$k}}", $link, $text);
}
@endphp
<div style='font-family: Arial,serif;font-size:16px;line-height:24px;color:#161616;-webkit-text-size-adjust:none;display:block'>
    {!! $text !!}
</div>
