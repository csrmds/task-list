@extends("layout.content")

@section("content")
<v-app>
<nav-bar></nav-bar>
{{-- <v-date-picker theme="dark" ></v-date-picker> --}}
<v-btn class="v-btn--icon">
    <v-icon color="success" icon="mdi-alert-circle"></v-icon>
</v-btn>

<div class="d-flex align-items-center mt-15">
  
  <div>
	<p>Message: {{ session('message') }}</p>
	<p>UserData: {{ session('userData') }}</p>
	<p>Success: {{ session('success') }}</p>
    <pre>
		
	</pre>
  </div>
</div>

</v-app>
@endsection

@vite(['resources/js/app.js', 'resources/css/app.css'])