@extends("layout.content")

@section("content")

<v-container >

	@if (Auth::check())
		<home :user-data='@json(Auth::user())'></home>
	@endif
	

</v-container>

@endsection

@vite(['resources/js/app.js', 'resources/css/app.css'])