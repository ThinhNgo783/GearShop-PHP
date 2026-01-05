@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('public/img/logo.png') }}" class="logo" />
<br />
{{ $slot }}
@endif
</a>
</td>
</tr>
