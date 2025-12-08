<template>
  <div class="admin-page">
    <section class="section">
      <div class="container">
        <!-- Header -->
        <div class="admin-header">
          <h1>Správa bytov</h1>
          <button class="btn btn-primary" @click="openCreateModal">
            + Pridať byt
          </button>
        </div>

        <!-- Alert Messages -->
        <div v-if="message" class="alert" :class="messageType === 'success' ? 'alert-success' : 'alert-error'">
          {{ message }}
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading">Načítavam...</div>

        <!-- Apartments Table -->
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Názov</th>
              <th>Dispozícia</th>
              <th>Plocha (m²)</th>
              <th>Podlažie</th>
              <th>Cena (€)</th>
              <th>Stav</th>
              <th>Akcie</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="apt in apartments" :key="apt.id">
              <td>{{ apt.id }}</td>
              <td>{{ apt.name }}</td>
              <td>{{ apt.layout }}</td>
              <td>{{ apt.area }}</td>
              <td>{{ apt.floor }}. NP</td>
              <td>{{ Number(apt.price).toLocaleString() }}</td>
              <td>
                <span class="status-badge" :class="apt.status">
                  {{ statusLabels[apt.status] }}
                </span>
              </td>
              <td class="actions">
                <button class="btn btn-outline-dark btn-sm" @click="openEditModal(apt)">
                  Upraviť
                </button>
                <button class="btn btn-danger btn-sm" @click="deleteApartment(apt.id)">
                  Zmazať
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Create/Edit Modal -->
        <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
          <div class="modal">
            <h2>{{ isEditing ? 'Upraviť byt' : 'Pridať nový byt' }}</h2>
            
            <form @submit.prevent="saveApartment">
              <div class="form-group">
                <label>Názov bytu</label>
                <input v-model="form.name" type="text" placeholder="napr. Byt A-101" required>
              </div>

              <div class="form-group">
                <label>Dispozícia</label>
                <select v-model="form.layout" required>
                  <option value="">Vyberte dispozíciu</option>
                  <option value="1-izbový">1-izbový</option>
                  <option value="2-izbový">2-izbový</option>
                  <option value="3-izbový">3-izbový</option>
                  <option value="4-izbový">4-izbový</option>
                </select>
              </div>

              <div class="form-group">
                <label>Plocha (m²)</label>
                <input v-model.number="form.area" type="number" step="0.01" min="10" max="500" required>
              </div>

              <div class="form-group">
                <label>Podlažie</label>
                <input v-model.number="form.floor" type="number" min="1" max="20" required>
              </div>

              <div class="form-group">
                <label>Cena (€)</label>
                <input v-model.number="form.price" type="number" min="1000" required>
              </div>

              <div class="form-group">
                <label>Stav</label>
                <select v-model="form.status" required>
                  <option value="available">Voľný</option>
                  <option value="reserved">Rezervovaný</option>
                  <option value="sold">Predaný</option>
                </select>
              </div>

              <div class="modal-actions">
                <button type="button" class="btn btn-outline-dark" @click="closeModal">
                  Zrušiť
                </button>
                <button type="submit" class="btn btn-primary">
                  {{ isEditing ? 'Uložiť' : 'Vytvoriť' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()
const API_URL = config.public.apiUrl

interface Apartment {
  id: number
  name: string
  layout: string
  area: number
  floor: number
  price: number
  status: 'available' | 'reserved' | 'sold'
}

const apartments = ref<Apartment[]>([])
const loading = ref(true)
const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref<number | null>(null)
const message = ref('')
const messageType = ref<'success' | 'error'>('success')

const statusLabels: Record<string, string> = {
  available: 'Voľný',
  reserved: 'Rezervovaný',
  sold: 'Predaný'
}

const form = ref({
  name: '',
  layout: '',
  area: 0,
  floor: 1,
  price: 0,
  status: 'available'
})

// Fetch all apartments
const fetchApartments = async () => {
  loading.value = true
  try {
    const response = await fetch(`${API_URL}/apartments`)
    apartments.value = await response.json()
  } catch (error) {
    showMessage('Chyba pri načítaní bytov', 'error')
  } finally {
    loading.value = false
  }
}

// Save apartment (create or update)
const saveApartment = async () => {
  try {
    const url = isEditing.value 
      ? `${API_URL}/apartments/${editingId.value}`
      : `${API_URL}/apartments`
    
    const response = await fetch(url, {
      method: isEditing.value ? 'PUT' : 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(form.value)
    })

    const data = await response.json()
    
    if (!response.ok) {
      // Server validation errors
      const errors = Object.values(data.errors || {}).flat().join(', ')
      showMessage(errors || 'Chyba pri ukladaní', 'error')
      return
    }

    showMessage(data.message, 'success')
    closeModal()
    fetchApartments()
  } catch (error) {
    showMessage('Chyba pri ukladaní bytu', 'error')
  }
}

// Delete apartment
const deleteApartment = async (id: number) => {
  if (!confirm('Naozaj chcete zmazať tento byt?')) return

  try {
    const response = await fetch(`${API_URL}/apartments/${id}`, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json' }
    })

    const data = await response.json()
    showMessage(data.message, 'success')
    fetchApartments()
  } catch (error) {
    showMessage('Chyba pri mazaní bytu', 'error')
  }
}

// Modal handlers
const openCreateModal = () => {
  isEditing.value = false
  editingId.value = null
  form.value = { name: '', layout: '', area: 0, floor: 1, price: 0, status: 'available' }
  showModal.value = true
}

const openEditModal = (apt: Apartment) => {
  isEditing.value = true
  editingId.value = apt.id
  form.value = { ...apt }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

// Message helper
const showMessage = (msg: string, type: 'success' | 'error') => {
  message.value = msg
  messageType.value = type
  setTimeout(() => { message.value = '' }, 5000)
}

// Initialize
onMounted(() => {
  fetchApartments()
})
</script>
