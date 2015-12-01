@extends('layouts.default')

@section('content')
		<div class="extra">
		<div class="csstransforms">
			<table class="table-striped table-condensed table-header-rotated unstyled">
				<thead>
					<tr class="orderable">
						<th></th>
						@foreach ($permissions as $permission)
							<th class="rotate"><div><span>{{ $permission->display_name }}</span></div></th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					
					@if ($roles->count())
						
						@foreach($roles as $role)
							<tr>
								<th class="row-header">{{ $role->display_name }}</th>
								@foreach ($permissions as $permission)
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
		</div>

@endsection