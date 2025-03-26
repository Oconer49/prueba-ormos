<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const product = ref({
  description: '',
  reference: '',
  stock: 0,
  currency: 'USD',
  price: 1.0,
})

// Observar el precio y redondearlo automáticamente a dos decimales
watch(
  () => product.value.price,
  (newValue) => {
    if (newValue !== null && newValue !== undefined) {
      product.value.price = parseFloat(Number(newValue).toFixed(2))
    }
  }
)

const fetchProduct = async () => {
  if (route.params.id) {
    const response = await fetch(`http://localhost:8080/products/${route.params.id}`)
    product.value = await response.json()
  }
}

const saveProduct = async () => {
  // Redondear el precio a dos decimales antes de enviarlo
  product.value.price = parseFloat(product.value.price.toFixed(2))

  const method = route.params.id ? 'PUT' : 'POST'
  const url = route.params.id
    ? `http://localhost:8080/products/${route.params.id}`
    : 'http://localhost:8080/products'

  await fetch(url, {
    method,
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(product.value),
  })
  router.push('/')
}

// Función para manejar el input del precio
const handlePriceInput = (event) => {
  let value = event.target.value
  // Reemplazar ',' por '.'
  value = value.replace(',', '.')
  // Validar y limitar a dos decimales
  if (!isNaN(value) && value !== '') {
    const numericValue = parseFloat(value).toFixed(2)
    product.value.price = parseFloat(numericValue)
  } else {
    product.value.price = ''
  }
}

onMounted(fetchProduct)
</script>

<template>
  <div class="p-6 bg-gray-900 text-white min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md bg-gray-800 p-6 rounded shadow-md">
      <h1 class="text-2xl font-bold mb-4 text-center">
        {{ route.params.id ? 'Editar Producto' : 'Nuevo Producto' }}
      </h1>
      <form @submit.prevent="saveProduct" class="space-y-4">
        <div>
          <label for="description" class="block font-medium mb-1">Descripción</label>
          <input id="description" v-model="product.description" required maxlength="248"
            class="w-full border border-gray-700 bg-gray-700 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label for="reference" class="block font-medium mb-1">Referencia</label>
          <input id="reference" v-model="product.reference" required maxlength="30"
            class="w-full border border-gray-700 bg-gray-700 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label for="stock" class="block font-medium mb-1">Stock</label>
          <input id="stock" type="number" v-model="product.stock" required min="0"
            class="w-full border border-gray-700 bg-gray-700 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label for="currency" class="block font-medium mb-1">Moneda</label>
          <select id="currency" v-model="product.currency" required
            class="w-full border border-gray-700 bg-gray-700 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="USD">USD</option>
            <option value="COP">COP</option>
          </select>
        </div>
        <div>
          <label for="price" class="block font-medium mb-1">Precio</label>
          <div class="relative">
            <input id="price" type="text" :value="product.price" @input="handlePriceInput" required
              class="w-full border border-gray-700 bg-gray-700 text-white rounded px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <span class="absolute inset-y-0 right-2 flex items-center text-white pointer-events-none">$</span>
          </div>
        </div>
        <div class="flex justify-between space-x-4">
          <!-- Botón de Volver -->
          <button @click="$router.back()" type="button"
            class="w-full border border-white text-white px-4 py-2 rounded hover:bg-white hover:text-gray-900 transition flex justify-center items-center space-x-2">
            Volver
          </button>
          <!-- Botón de Guardar -->
          <button type="submit"
            class="w-full border cursor-pointer border-white text-white px-4 py-2 rounded hover:bg-white hover:text-gray-900 transition flex justify-center items-center space-x-2">
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>