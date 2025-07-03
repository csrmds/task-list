import { createApp } from 'vue'
import axios from 'axios'

import './bootstrap'
import 'materialize-css/dist/css/materialize.min.css'
import 'materialize-css/dist/js/materialize.min.js'


// import Teste from './components/Teste.vue'
import TaskNew from './components/TaskNew.vue'
import TaskFilter from './components/TaskFilter.vue'
import TaskList from './components/TaskList.vue'
import TaskEditModal from './components/TaskEditModal.vue'


const token= document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN']= token
    window.csrfToken= token
} else {
    console.error('CSRF token não encontrado!')
}


const app= createApp({})

// app.component('teste', Teste)
app.component('task-new', TaskNew)
app.component('task-filter', TaskFilter)
app.component('task-list', TaskList)
app.component('task-edit-modal', TaskEditModal)


app.mount("#task-app")