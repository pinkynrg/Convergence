<tr class="escalation_event_form">
	<td class="escalation_level">
	</td>
	<td>
		{!! Form::BSLabel("delay_time[".$counter."]", "After", ['bclass' => 'col-xs-2', 'class' => 'delay_time']) !!}
		{!! Form::BSSelect("delay_time[".$counter."]", $delays, null, ['bclass' => 'col-xs-10', 'class' => 'delay_time']) !!}
	</td>
	<td>
		{!! Form::BSLabel("event_id[".$counter."]", "Remind", ['bclass' => 'col-xs-2', 'class' => 'event_id']) !!}
		{!! Form::BSSelect("event_id[".$counter."]", $escalation_events, null, ['key' => 'id', 'value' => 'label', 'bclass' => 'col-xs-10', 'class' => 'event_id']) !!}
	</td>
	<td>
		{!! Form::BSLabel("fallback_contact_id[".$counter."]", "OR", ['bclass' => 'col-xs-1', 'class' => 'fallback_contact_id']) !!}
		{!! Form::BSSelect("fallback_contact_id[".$counter."]", $fallbacks, null, ['key' => 'id', 'value' => 'person.name', 'bclass' => 'col-xs-11', 'class' => 'fallback_contact_id']) !!}
	</td>
	<td>
		<i class='fa fa-trash delete_escalation_event'></i>
	</td>
</tr>