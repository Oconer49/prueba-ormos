<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const product = ref(null)

const fetchProduct = async () => {
  const response = await fetch(`http://localhost:8080/products/${route.params.id}`)
  product.value = await response.json()
}

onMounted(fetchProduct)
</script>

<template>
  <div class="p-6 bg-gray-900 text-white min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md bg-gray-800 p-6 rounded shadow-md">
      <h1 class="text-2xl font-bold mb-4 text-center">Detalle del Producto</h1>
      <div v-if="product" class="space-y-4">
        <p class="mb-2"><strong>ID:</strong> {{ product.id }}</p>
        <p class="mb-2"><strong>Descripción:</strong> {{ product.description }}</p>
        <p class="mb-2"><strong>Referencia:</strong> {{ product.reference }}</p>
        <p class="mb-2"><strong>Stock:</strong> {{ product.stock }}</p>
        <p class="mb-2"><strong>Moneda:</strong> {{ product.currency }}</p>
        <p class="mb-2"><strong>Precio:</strong> {{ product.currency }} {{ product.price }}</p>
      </div>
      <button @click="$router.back()"
        class="w-full mt-4 border border-white text-white px-4 py-2 rounded hover:bg-white hover:text-gray-900 transition flex justify-center items-center space-x-2">
        Volver
      </button>
    </div>
  </div>
</template>