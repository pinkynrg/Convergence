<tr class="escalation_event_form">
	<td class="escalation_level">
	</td>
	<td>
		{!! Form::BSLabel("level_id[".$counter."]", " ", ['class' => 'delay_time']) !!}
		{!! Form::BSSelect("level_id[".$counter."]", $levels, null, ['key' => 'id', 'value' => 'name', 'bclass' => 'col-xs-12', 'class' => 'level_id']) !!}
	</td>
	<td>
		{!! Form::BSLabel("delay_time[".$counter."]", "After", ['bclass' => 'col-xs-2', 'class' => 'delay_time']) !!}
		{!! Form::BSSelect("delay_time[".$counter."]", $delays, null, ['bclass' => 'col-xs-10', 'class' => 'delay_time']) !!}
	</td>
	<td>
		{!! Form::BSLabel("priority_id[".$counter."]", "When", ['bclass' => 'col-xs-2', 'class' => 'priority_id']) !!}
		{!! Form::BSSelect("priority_id[".$counter."]", $priorities, null, ['key' => 'id', 'value' => 'name', 'bclass' => 'col-xs-10', 'class' => 'priority_id']) !!}
	</td>
	<td>
		{!! Form::BSLabel("event_id[".$counter."]", "Remind", ['bclass' => 'col-xs-2', 'class' => 'event_id']) !!}
		{!! Form::BSSelect("event_id[".$counter."]", $escalation_events, null, ['key' => 'id', 'value' => 'label', 'bclass' => 'col-xs-10', 'class' => 'event_id']) !!}
	</td>
	<td>
		<i class='fa fa-trash delete_escalation_event'></i>
	</td>
</tr>