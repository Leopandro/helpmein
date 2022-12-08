@php
$link = "<a href=\"{$href}\" style='font-family: Arial,serif;font-size:16px;line-height:20px;color:#6977D2;'>{$linkText}</a>";
$text = str_replace('{link}', $link, $text);
@endphp
<div style='font-family: Arial,serif;font-size:16px;line-height:24px;color:#161616;-webkit-text-size-adjust:none;display:block'>
    {!! $text !!}
</div>
