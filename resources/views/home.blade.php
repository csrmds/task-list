@extends("layout.content")

@section("content")

<v-container >

	@if (Auth::check())
		<home :user-data='@json(Auth::user())'></home>
	@else
		<script>
			window.location.href= "{{ route('login') }}";
		</script>
	@endif
	

</v-container>

@endsection

@vite(['resources/js/app.js', 'resources/css/app.css'])