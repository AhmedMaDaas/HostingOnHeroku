@push('js')
<script type="text/javascript">
  Dropzone.autoDiscover = false;
  var id = {{ isset($product) ? $product->id : $pid }};

  $(document).ready(function(){
    $('#dropzoneMainPhoto').dropzone({
      url:'{{ url("admin/upload/main-image") }}/' + id,
      paramName:'file',
      uploadMultiple:false,
      maxFiles:15,
      maxFilessize:5,
      acceptedFiles:'image/*',
      dictDefaultMessage:'{{ trans("admin.drop_files_here") }}',
      dictRemoveFile:'{{ trans("admin.remove_file") }}',
      params: {
        _token:'{{ csrf_token() }}'
      },
      //addRemoveLinks: true,
      removedfile:function(file){
        $('input[name="photo"]').val('');
        var id = {{ isset($product) ? $product->id : $pid }};
        $.ajax({
          url: '{{ url("admin/product/delete-photo") }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            id: id,
          },
        });

        var fmock;
        return (fmock = file.previewElement) != null ? fmock.parentNode.removeChild(file.previewElement) : void 0;
      },
      init:function(){
        @if(isset($product) && isset($product->photo))
          var mock = {name: '{{ $product->photo }}'};
          this.emit('addedfile', mock);
          this.options.thumbnail.call(this, mock, '{{ Storage::url("storage/" . $product->photo) }}');
          this.files.push(mock);
          
          $('input[name="photo"]').val('{{ $product->photo }}');
        @endif

        this.on("sending", function(file, xhr, formData){
          this.removeAllFiles();
        });

        this.on('success', function(file, data){
          $('input[name="photo"]').val(data);
        });
      }
    });
  });

  /*Dropzone.options.myoption = {

  };*/
</script>
@endpush

<div id="home" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label class="bmd-label-floating">{{ trans('admin.name_in_arabic') }}</label>
          {{ Form::text('name_ar', isset($product) && old('department_id') == null ? $product->name_ar : old('name_ar'), ['class'=>'form-control']) }}
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label class="bmd-label-floating">{{ trans('admin.name_in_english') }}</label>
          {{ Form::text('name_en', isset($product) && old('department_id') == null ? $product->name_en : old('name_en'), ['class'=>'form-control']) }}
        </div>
      </div>
    </div>
        <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label class="bmd-label-floating">{{ trans('admin.photo') }}</label>
          <div class="dropzone" id="dropzoneMainPhoto"></div>
          {{ Form::hidden('photo') }}
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
          
        <div class="form-group">
         <label class="bmd-label-floating">{{ trans('admin.description') }}</label>
         {{ Form::textarea('content', isset($product) && old('department_id') == null ? $product->content : old('content'), ['class'=>'form-control']) }}
        </div>
      </div>
    </div>
</div>