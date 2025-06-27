import { createApp } from 'vue'

import './bootstrap'
import 'materialize-css/dist/css/materialize.min.css'
import 'materialize-css/dist/js/materialize.min.js'


// import Teste from './components/Teste.vue'
import TaskNew from './components/TaskNew.vue'
import TaskFilter from './components/TaskFilter.vue'
import TaskList from './components/TaskList.vue'



const app= createApp({})

// app.component('teste', Teste)
app.component('task-new', TaskNew)
app.component('task-filter', TaskFilter)
app.component('task-list', TaskList)


app.mount("#task-app")