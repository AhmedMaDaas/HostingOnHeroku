<div class="col-md-6 col-sm-4 col-xs-6 store">
	<a href="{{route('storebrand.get',['mallId'=> $mall->id , 'departmentId'=> $departmentId ])}}" class="fill-link"></a>
	<img src="{{url('/storage/'.$mall->icon)}}">
	<div class="store-footer">
	    <a href="{{route('storebrand.get',['mallId'=> $mall->id , 'departmentId'=> $departmentId ])}}">{{$mall->name_en}}</a>
	</div>
</div>