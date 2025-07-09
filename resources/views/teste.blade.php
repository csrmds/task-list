@extends("layout.content")

@section("content")
<p>opaa!!</p>
<v-date-picker theme="dark" ></v-date-picker>
<v-btn class="v-btn--icon">
    <v-icon color="success" icon="mdi-alert-circle"></v-icon>
</v-btn>
<v-btn >xa</v-btn>

@endsection

@vite(['resources/js/app.js', 'resources/css/app.css'])