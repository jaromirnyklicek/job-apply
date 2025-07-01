import 'bootstrap/dist/css/bootstrap.min.css'
import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import JobList from './components/JobList.vue'
import JobDetail from './components/JobDetail.vue'

const routes = [
    { path: '/', component: JobList },
    { path: '/job/:id', component: JobDetail, props: true }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

createApp(App).use(router).mount('#app')
