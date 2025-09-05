@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <a href="{{ $url }}"
               style="display:inline-block; background:#D7CCC8; color:#2E2E2E; font-family:'Playfair Display',serif; font-size:17px; font-weight:700; line-height:1.5; border-radius:8px; padding:14px 36px; text-decoration:none; box-shadow:0 2px 8px rgba(155,139,122,0.10); border: none; letter-spacing:0.5px;">
                {{ $slot }}
            </a>
        </td>
    </tr>
</table>
