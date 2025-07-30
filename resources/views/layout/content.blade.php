@include("layout.top")

<body id="task-app">
    <v-app class="bg-teal-lighten-5">
        <nav-bar :check-user='@json(Auth::check())' :user-data='@json(Auth::user())' ></nav-bar>
        
        @yield("content")    

    </v-app>
</body>

@include("layout.footer")
