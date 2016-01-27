<tr class="escalation_event_form">
	<td class="escalation_level">
		{{ 'Event #'.$counter }}
	</td>
	<td>
		{!! Form::BSLabel("delay_time[]", "After", ['bclass' => 'col-xs-2']) !!}
		{!! Form::BSSelect("delay_time[]", $delays, null, ['bclass' => 'col-xs-10']) !!}
	</td>
	<td>
		{!! Form::BSLabel("event_id[]", "Remind", ['bclass' => 'col-xs-2']) !!}
		{!! Form::BSSelect("event_id[]", $escalation_events, null, ['key' => 'id', 'value' => 'label', 'bclass' => 'col-xs-10']) !!}
	</td>
	<td>
		{!! Form::BSLabel("fallback_company_contact_id[]", "OR", ['bclass' => 'col-xs-1']) !!}
		{!! Form::BSSelect("fallback_company_contact_id[]", $fallbacks, null, ['key' => 'id', 'value' => 'person.name', 'bclass' => 'col-xs-11']) !!}
	</td>
	<td>
		<i class='fa fa-trash delete_escalation_event'></i>
	</td>
</tr>