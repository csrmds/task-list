import { createApp } from 'vue'
import axios from 'axios'

//bootstrap
import './bootstrap'


// CSRF Token configuration
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token
    window.csrfToken = token
} else {
    console.error('CSRF token não encontrado!')
}


const app = createApp({})


app.mount("#task-app")