<template>

    <v-app-bar class="bg-green-lighten-2">

        <v-app-bar-title>Task List</v-app-bar-title>

        <v-btn href="/">Home</v-btn>

        <v-btn icon v-if="checkUser" class="mr-6">
            <v-avatar :image="userData.avatar" size="48"></v-avatar>
            <v-menu activator="parent">
                <v-list>
                    <v-list-item link href="/auth/logout">
                        <v-list-item-title >Logout</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-btn>
        <v-btn v-else href="/login" class="mr-6">Login</v-btn>


    </v-app-bar>



</template>


<script>

export default {
    name: "NavBar",
    props: {
        checkUser: Boolean,
        userData: Object
    },

    data() {
        return {

        }
    },

    methods: {

    },

    mounted() {
        console.log("userData: ", this.userData)
    }
}

</script>