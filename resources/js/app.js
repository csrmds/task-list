import { createApp } from 'vue'
import axios from 'axios'

//bootstrap
import './bootstrap'

// Vue the mask
import VueTheMask from 'vue-the-mask'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css'
// Vuetify configuration
const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'light'
  },
  icons: {
    defaultSet: 'mdi',
  },
})

// CSRF Token configuration
const token= document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN']= token
    window.csrfToken= token
} else {
    console.error('CSRF token não encontrado!')
}


// COMPONENTES
// import Teste from './components/Teste.vue'
import TaskNew from './components/TaskNew.vue'
import TaskFilter from './components/TaskFilter.vue'
import TaskList from './components/TaskList.vue'
import TaskEditModal from './components/TaskEditModal.vue'
import Home from './components/Home.vue'




const app= createApp({}).use(vuetify)
app.use(VueTheMask)

// app.component('teste', Teste)
app.component('task-new', TaskNew)
app.component('task-filter', TaskFilter)
app.component('task-list', TaskList)
app.component('task-edit-modal', TaskEditModal)
app.component('home', Home)


app.mount("#task-app")