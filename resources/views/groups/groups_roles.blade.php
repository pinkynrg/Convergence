@extends('layouts.default')

@section('content')
		
		<div class="csstransforms">
			<table class="table-striped table-condensed table-hover table-header-rotated unstyled">
				<thead>
					<tr class="orderable">
						<th></th>
						@foreach ($roles as $role)
							<th class="rotate"><div><span>{{ $role->display_name }}</span></div></th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					
					@if ($groups->count())
						
						@foreach($groups as $group)
							<tr>
								<th class="row-header">{{ $group->display_name }}</th>
								@foreach ($roles as $role)
									<td> <input type="checkbox"> </td>
								@endforeach
							</tr>
						@endforeach

					@else 
						<tr><td colspan="1">@include('includes.no-contents')</td></tr>
					@endif 

				</tbody>
			</table>	
		</div>

@endsection