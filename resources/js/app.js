import { createApp } from 'vue'

import './bootstrap'
import 'materialize-css/dist/css/materialize.min.css'
import 'materialize-css/dist/js/materialize.min.js'


import TaskList from './components/TaskList.vue'
import Teste from './components/Teste.vue'
import TaskNew from './components/TaskNew.vue'



const app= createApp({})

app.component('task-new', TaskNew)
app.component('teste', Teste)


app.mount("#task-app")