@extends('layouts.default')

@section('content')

	<div id="open_status_count"> <!-- here goes the chart --> </div>
	<div id="progress_status_count"> <!-- here goes the chart --> </div>
	<div id="wff_status_count"> <!-- here goes the chart --> </div>
	<div id="solved_status_count"> <!-- here goes the chart --> </div>
	<div id="closed_status_count"> <!-- here goes the chart --> </div>

	<script type="text/javascript">
		$('#open_status_count').highcharts('StockChart', {!! $open_status_count !!});
		$('#progress_status_count').highcharts('StockChart', {!! $progress_status_count !!});
		$('#wff_status_count').highcharts('StockChart', {!! $wff_status_count !!});
		$('#solved_status_count').highcharts('StockChart', {!! $solved_status_count !!});
		$('#closed_status_count').highcharts('StockChart', {!! $closed_status_count !!});
	</script>
	
@endsection