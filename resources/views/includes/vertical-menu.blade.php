<div class="panel-group">

@if (isset($menu_actions) && count($menu_actions))

	<div class="panel panel-default">
    	<div class="panel-heading" href="#actions">
    		<h4 class="panel-title">
				<a data-parent="#accordion">
					<span class="icon"> <i class="fa fa-bars"></i> </span> Actions
    			</a>
    		</h4>
    	</div>

		<div id="actions"> 
			<div class="panel-body">

				@foreach ($menu_actions as $menu_action)
				
					<a>
						<div class="menu-item"> 
							<span class="icon"> {!! $menu_action['icon'] !!} </span> 
							<a href="{!! $menu_action['link'] !!}"> {{ $menu_action['label'] }} </a>
	        			</div>
	        		</a>

				@endforeach

			</div>
		</div>
	</div>
	
@endif

@foreach (Menu::build() as $elem)
	
	@if ($elem->show)
		<div class="panel panel-default">
	    	<div class="panel-heading" data-toggle="collapse" href="#{{ Menu::getId($elem) }}">
	    		<h4 class="panel-title">
					<a data-parent="#accordion">
						<span class="icon"> {!! $elem->icon !!} </span> {{ $elem->label }}
	    			</a>
	    		</h4>
	    	</div>
	    
			<div id="{{ Menu::getId($elem) }}" class="panel-collapse collapse @if (Menu::isExpanded($elem)) in @endif"> 

				<div class="panel-body">

					@foreach ($elem->menu as $subelem)

						@if ($subelem->show)
							<a href="{{ $subelem->link }}">
								<div class="menu-item @if(Menu::isSelected($subelem)) selected @endif" >
									<span class="icon"> {!! $subelem->icon !!} </span> {{ $subelem->label }}
			        			</div>
			        		</a>
			        	@endif

					@endforeach

	    		</div>
	    	</div>
	    </div>
	@endif

@endforeach

</div>