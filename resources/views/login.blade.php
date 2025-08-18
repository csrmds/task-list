@extends("layout.content")

@section("content")

<v-container class="h-screen mt-16 pt-32">

    <v-row class="d-flex justify-center">
        <v-col cols="12" sm="10" md="8" lg="6" xl="4">
            <login></login>
        </v-col>
    </v-row>


</v-container>

  

@endsection

@vite(['resources/js/app.js', 'resources/css/app.css'])