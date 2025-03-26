import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import './style.css'
import App from './App.vue'
import ProductList from './views/ProductList.vue'
import ProductDetail from './views/ProductDetail.vue'
import ProductForm from './views/ProductForm.vue'

const routes = [
  { path: '/', component: ProductList },
  { path: '/product/:id', component: ProductDetail },
  { path: '/product/new', component: ProductForm },
  { path: '/product/edit/:id', component: ProductForm },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

const app = createApp(App)
app.use(router)
app.mount('#app')
