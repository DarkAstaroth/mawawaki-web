@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Scallia')
<img src="https://scallia.com/wp-content/uploads/2024/02/logo-equino-1.png" alt="Scallia Logo" width="220px">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
