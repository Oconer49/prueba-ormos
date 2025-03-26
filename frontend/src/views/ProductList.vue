<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { IconEye, IconEdit, IconTrash, IconPlus } from '@tabler/icons-vue'

const products = ref([])
const searchQuery = ref('')
const router = useRouter()

// Estado para el modal
const showModal = ref(false)
const productToDelete = ref(null)

const fetchProducts = async () => {
  const response = await fetch('http://localhost:8080/products')
  products.value = await response.json()
}

// Función para filtrar productos según la búsqueda
const filteredProducts = computed(() => {
  const query = searchQuery.value.toLowerCase()
  return products.value.filter((product) => {
    return (
      product.id.toString().includes(query) ||
      product.description.toLowerCase().includes(query) ||
      product.reference.toLowerCase().includes(query) ||
      product.stock.toString().includes(query) ||
      product.price.toString().includes(query) ||
      product.currency.toLowerCase().includes(query)
    )
  })
})

// Función para formatear el precio
const formatPrice = (price, currency) => {
  const formattedPrice = Number(price).toLocaleString('en-US', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2,
  })
  if (currency === 'USD') {
    return `${formattedPrice} US$`
  } else if (currency === 'COP') {
    return `${formattedPrice} COP$`
  }
  return formattedPrice
}

// Mostrar el modal de confirmación
const confirmDelete = (product) => {
  productToDelete.value = product
  showModal.value = true
}

// Eliminar el producto seleccionado
const deleteProduct = async () => {
  if (productToDelete.value) {
    await fetch(`http://localhost:8080/products/${productToDelete.value.id}`, { method: 'DELETE' })
    fetchProducts()
    closeModal()
  }
}

// Cerrar el modal
const closeModal = () => {
  showModal.value = false
  productToDelete.value = null
}

onMounted(fetchProducts)
</script>

<template>
  <div class="p-6 bg-gray-900 text-white min-h-screen">
    <h1 class="text-2xl font-bold mb-4">Listado de Productos</h1>
    <div class="flex justify-between items-center mb-4">
      <!-- Barra de búsqueda -->
      <input v-model="searchQuery" type="text" placeholder="Buscar productos..."
        class="w-1/3 px-4 py-2 rounded border border-gray-700 bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
      <!-- Botón de nuevo producto -->
      <button @click="router.push('/product/new')"
        class="border cursor-pointer border-white text-white px-4 py-2 rounded hover:bg-white hover:text-gray-900 transition flex items-center space-x-2">
        <IconPlus class="w-5 h-5" />
        <span>Nuevo Producto</span>
      </button>
    </div>
    <table class="table-auto w-full mt-4 border-collapse border border-gray-700">
      <thead>
        <tr class="bg-gray-800">
          <th class="border border-gray-700 px-4 py-2 text-center">ID</th>
          <th class="border border-gray-700 px-4 py-2 text-center">Descripción</th>
          <th class="border text-nowrap border-gray-700 px-4 py-2 text-center">Referencia</th>
          <th class="border border-gray-700 px-4 py-2 text-center">Cantidad en inventario</th>
          <th class="border text-nowrap border-gray-700 px-4 py-2 text-center">Precio</th>
          <th class="border border-gray-700 px-4 py-2 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- Mostrar mensaje si no hay productos filtrados -->
        <tr v-if="filteredProducts.length === 0">
          <td colspan="6" class="text-center py-4 text-gray-400">
            No se encontraron productos.
          </td>
        </tr>
        <!-- Mostrar productos filtrados -->
        <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-800">
          <td class="border border-gray-700 px-4 py-2 text-center">{{ product.id }}</td>
          <td class="border border-gray-700 px-4 py-2 text-center">{{ product.description }}</td>
          <td class="border text-nowrap border-gray-700 px-4 py-2 text-center">{{ product.reference }}</td>
          <td class="border border-gray-700 px-4 py-2 text-center">{{ product.stock }}</td>
          <td class="border text-nowrap border-gray-700 px-4 py-2 text-center">
            {{ formatPrice(product.price, product.currency) }}
          </td>
          <td class="border border-gray-700 px-4 py-2 text-center">
            <div class="flex justify-center items-center space-x-2">
              <button @click="router.push(`/product/${product.id}`)"
                class="border cursor-pointer border-green-500 text-green-500 px-2 py-1 rounded hover:bg-green-500 hover:text-white transition flex items-center space-x-1">
                <IconEye class="w-4 h-4" />
              </button>
              <button @click="router.push(`/product/edit/${product.id}`)"
                class="border cursor-pointer border-blue-500 text-blue-500 px-2 py-1 rounded hover:bg-blue-500 hover:text-white transition flex items-center space-x-1">
                <IconEdit class="w-4 h-4" />
              </button>
              <button @click="confirmDelete(product)"
                class="border cursor-pointer border-red-500 text-red-500 px-2 py-1 rounded hover:bg-red-500 hover:text-white transition flex items-center space-x-1">
                <IconTrash class="w-4 h-4" />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal de confirmación -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-gray-800 p-6 rounded shadow-md w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-center">Confirmar eliminación</h2>
        <p class="text-center mb-4">
          ¿Estás seguro de eliminar el producto <strong>{{ productToDelete?.description }}</strong>?
        </p>
        <div class="flex justify-between">
          <button @click="closeModal"
            class="border border-gray-500 text-gray-500 px-4 py-2 rounded hover:bg-gray-500 hover:text-white transition">
            Cancelar
          </button>
          <button @click="deleteProduct"
            class="border border-red-500 text-red-500 px-4 py-2 rounded hover:bg-red-500 hover:text-white transition">
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>