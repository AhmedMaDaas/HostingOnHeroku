@if(isManager() && $owner == auth()->guard('web')->user()->id)
<input type="checkbox" name="delete[]" class="item" value="{{ $id }}"></input>
@endif