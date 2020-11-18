@push('js')
<script type="text/javascript">
	Dropzone.autoDiscover = false;
	var id = {{ isset($product) ? $product->id : $pid }};

	$(document).ready(function(){
		$('#dropzoneFileUpload').dropzone({
			url:'{{ url("mall-manager/upload/image") }}/' + id,
			paramName:'file',
			uploadMultiple:false,
			maxFiles:15,
			maxFilessize:2,
			acceptedFiles:'image/*',
			dictDefaultMessage:'{{ trans("admin.drop_files_here") }}',
			dictRemoveFile:'{{ trans("admin.remove_file") }}',
			params: {
				_token:'{{ csrf_token() }}'
			},
			addRemoveLinks: true,
			removedfile:function(file){
				$.ajax({
					url: '{{ url("mall-manager/product/delete-file") }}',
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						id: file.id,
					},
				});

				var fmock;
				return (fmock = file.previewElement) != null ? fmock.parentNode.removeChild(file.previewElement) : void 0;
			},
			init:function(){
				@if(isset($product))
					@foreach($product->files as $file)
						var mock = {name: '{{ $file->name }}', id: '{{ $file->id }}', size: '{{ $file->size }}', type: '{{ $file->mimeType }}'};
						this.emit('addedfile', mock);
						this.options.thumbnail.call(this, mock, '{{ Storage::url("storage/" . $file->fullFile) }}');
					@endforeach
				@endif

				this.on('sending', function(file, xhr, formData){
					formData.append('id', '');
					file.id = '';
				});

				this.on('success', function(file, data){
					file.id = data;
				});
			}
		});
	});

	/*Dropzone.options.myoption = {

	};*/
</script>
@endpush

<div id="product_media" class="tab-pane fade">
	<h3>{{ trans('admin.media') }}</h3>
	<div class="row">
	    <div class="col-md-12">
	      <div class="form-group">
	        <div class="dropzone" id="dropzoneFileUpload"></div>
	      </div>
	    </div>
  	</div>
</div>