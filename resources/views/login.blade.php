@extends("layout.content")

@section("content")

<v-container class="h-screen mt-16 pt-32">

    <v-row >
        <v-col class="d-flex justify-center">
            <login class="w-25"></login>
        </v-col>
    </v-row>


</v-container>

  

@endsection

@vite(['resources/js/app.js', 'resources/css/app.css'])