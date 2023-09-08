@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Scallia')
<img src="https://scallia.com/wp-content/uploads/2022/01/logo-scallia-light.png" alt="Scallia Logo" width="100px">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
