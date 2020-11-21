<div id="other_data" class="tab-pane fade">
	<h3>{{ trans('admin.other_data') }}</h3>
	<table class="editable-table" border="1">
	@if(isset($product) && count($product->otherData) > 0)
		@foreach($table as $row)
			<tr>
				@foreach($row as $cell)
					<td contenteditable='true' rowspan="{{ $cell['rowspan'] }}" colspan="{{ $cell['colspan'] }}">{{ $cell['text'] }}</td>
				@endforeach
			</tr>
		@endforeach
	@else
		<tr>
			<td contenteditable='true' colspan="3"></td>
		</tr>
		<tr>
			<td contenteditable='true'></td>
			<td contenteditable='true'></td>
			<td contenteditable='true'></td>
		</tr>
		<tr>
			<td contenteditable='true'></td>
			<td contenteditable='true'></td>
			<td contenteditable='true'></td>
		</tr>
	@endif
	</table>
</div>

@push('js')
	<script type="text/javascript" src="{{ url('/admin_design') }}/assets/js/editable_table.js"></script>
@endpush