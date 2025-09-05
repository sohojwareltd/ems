@props(['url'])
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center" style="padding:32px 0 12px 0;">
            <span style="display:inline-block;vertical-align:middle;">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="7" width="14" height="24" rx="3" fill="#fff" stroke="#D7CCC8" stroke-width="2"/>
                    <rect x="22" y="7" width="14" height="24" rx="3" fill="#fff" stroke="#D7CCC8" stroke-width="2"/>
                    <path d="M19 9 Q24 19 19 29" stroke="#D7CCC8" stroke-width="2" fill="none"/>
                </svg>
            </span>
            <span style="font-family:'Playfair Display',serif; color:#2E2E2E; font-size:1.6rem; font-weight:700; letter-spacing:1px; margin-left:10px;">
                {{ setting('store.name', config('app.name')) }}
            </span>
        </td>
    </tr>
</table>
