<tbody class="escalation_event_form">
	<tr>
		<td class="escalation_level">
		</td>
		<td>
			{!! Form::BSLabel("level_id[".$counter."]", " ", ['class' => 'delay_time']) !!}
			{!! Form::BSSelect("level_id[".$counter."]", $levels, null, ['key' => 'id', 'value' => '!name', 'bclass' => 'col-xs-12', 'class' => 'level_id']) !!}
		</td>
		<td>
			{!! Form::BSLabel("delay_time[".$counter."]", "After", ['bclass' => 'col-xs-2 visible-lg', 'class' => 'delay_time']) !!}
			{!! Form::BSSelect("delay_time[".$counter."]", $delays, null, ['bclass' => 'col-xs-10', 'class' => 'delay_time']) !!}
		</td>
		<td>
			{!! Form::BSLabel("priority_id[".$counter."]", "When", ['bclass' => 'col-xs-2 visible-lg', 'class' => 'priority_id']) !!}
			{!! Form::BSSelect("priority_id[".$counter."]", $priorities, null, ['key' => 'id', 'value' => '!name', 'bclass' => 'col-xs-10', 'class' => 'priority_id']) !!}
		</td>
		<td>
			{!! Form::BSLabel("event_id[".$counter."][]", "Remind", ['bclass' => 'col-xs-2 visible-lg', 'class' => 'event_id']) !!}
			<div class="col-xs-10">

				@if (isset($escalation_profile_events['event_id'][$counter]))
				{!!
					Form::BSMultiSelect("event_id[".$counter."][]", $escalation_events, ["title" => "Select Targets", "selected_text" => "Targets Selected", "value" => "id", "label" => "!label", "class" => "event_id", "selected" => $escalation_profile_events['event_id'][$counter]]);
				!!}
				@else
				{!!
					Form::BSMultiSelect("event_id[".$counter."][]", $escalation_events, ["title" => "Select Targets", "selected_text" => "Targets Selected", "value" => "id", "label" => "!label", "class" => "event_id"]);
				!!}
				@endif

			</div>
		</td>
		<td>
			<i class='fa fa-trash delete_escalation_event'></i>
		</td>
	</tr>
	<tr class="email_text">
		<td colspan="100%">
			<b>Message to include in the email</b>
			{!! Form::BSTextArea("email_text[".$counter."]") !!}
		</td>
	</tr>
</tbody>
