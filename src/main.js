import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './style.css'

const app = createApp(App)

app.config.globalProperties.$bus = {
  handlers: {},
  on(event, cb) {
    ;(this.handlers[event] = this.handlers[event] || []).push(cb)
  },
  emit(event, ...args) {
    ;(this.handlers[event] || []).forEach(cb => cb(...args))
  }
}

app.use(router).mount('#app')