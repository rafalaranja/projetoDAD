import './assets/main.css'

import { createApp } from 'vue'

import { io } from 'socket.io-client'
import App from './App.vue'
const apiDomain = import.meta.env.VITE_API_DOMAIN
const wsConnection = import.meta.env.VITE_WS_CONNECTION

const app = createApp(App)


app.provide('serverUrl',`${apiDomain}/api`)
app.provide('socket',io(wsConnection))



createApp(App).mount('#app')
