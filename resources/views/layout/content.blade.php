@include("layout.top")

<body id="task-app">
    <v-app>
        <nav-bar :check-user='@json(Auth::check())' :user-data='@json(Auth::user())' ></nav-bar>
        
        @yield("content")    

    </v-app>
</body>

@include("layout.footer")
