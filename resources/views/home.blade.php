@extends("layout.content")

@section("content")

<v-container >

	@if (Auth::check())
		<home></home>
	
	@endif
	

</v-container>

@endsection

@vite(['resources/js/app.js', 'resources/css/app.css'])