<template>
  <div class="admin-messages">
    <h1>Kontaktné správy</h1>
    
    <!-- Stats Cards -->
    <div class="stats-grid" v-if="stats">
      <div class="stat-card">
        <div class="stat-value">{{ stats.summary?.total_messages || 0 }}</div>
        <div class="stat-label">Celkom správ</div>
      </div>
      <div class="stat-card highlight">
        <div class="stat-value">{{ stats.summary?.unread_messages || 0 }}</div>
        <div class="stat-label">Neprečítaných</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">{{ stats.summary?.messages_today || 0 }}</div>
        <div class="stat-label">Dnes</div>
      </div>
      <div class="stat-card warning" v-if="stats.violations?.today > 0">
        <div class="stat-value">{{ stats.violations?.today || 0 }}</div>
        <div class="stat-label">Spam pokusy dnes</div>
      </div>
    </div>
    
    <!-- Chart -->
    <div class="chart-section">
      <h2>Správy za posledných 30 dní</h2>
      <div class="chart-container">
        <canvas ref="chartCanvas"></canvas>
      </div>
    </div>
    
    <!-- Spam Alerts -->
    <div class="spam-section" v-if="stats?.violations?.top_spammers?.length">
      <h2>Podozrivé IP adresy</h2>
      <div class="spam-list">
        <div class="spam-item" v-for="spammer in stats.violations.top_spammers" :key="spammer.ip_address">
          <span class="ip">{{ spammer.ip_address }}</span>
          <span class="count">{{ spammer.violation_count }} pokusov</span>
        </div>
      </div>
    </div>
	
	    <!-- Messages Table -->
	    <div class="messages-section">
	      <div class="section-header">
	        <h2>Zoznam správ</h2>
	        <div class="filters">
	          <button
	            :class="{ active: filter === '' }"
	            @click="filter = ''; loadMessages(1)"
	          >
	            Všetky
	          </button>
	          <button
	            :class="{ active: filter === 'unread' }"
	            @click="filter = 'unread'; loadMessages(1)"
	          >
	            Neprečítané
	          </button>
	        </div>
	      </div>
	      
	      <div class="messages-table" v-if="messages.length">
	        <div
	          class="message-row"
	          v-for="msg in messages"
	          :key="msg.id"
	          :class="{ unread: !msg.is_read }"
	          @click="openMessage(msg)"
	        >
	          <div class="message-sender">
	            <strong>{{ msg.first_name }} {{ msg.last_name }}</strong>
	            <span>{{ msg.email }}</span>
	          </div>
	          <div class="message-preview">{{ msg.message.substring(0, 100) }}...</div>
	          <div class="message-date">{{ formatDate(msg.created_at) }}</div>
	          <div class="message-actions">
	            <button @click.stop="confirmDelete(msg)" class="btn-delete" title="Zmazať">
	              <img src="/icons/ui/trash.svg" alt="Zmazať" />
	            </button>
	          </div>
	        </div>
	      </div>
	      <div v-else class="no-messages">Žiadne správy</div>

	      <div v-if="messagesMeta.total > 0" class="messages-pagination">
	        <div class="messages-pagination__summary">
	          Strana {{ messagesMeta.current_page }} z {{ messagesMeta.last_page }} ({{ messagesMeta.total }} správ)
	        </div>
	        <div class="messages-pagination__controls">
	          <button
	            class="messages-pagination__btn"
	            :disabled="messagesMeta.current_page <= 1"
	            @click="loadMessages(messagesMeta.current_page - 1)"
	          >
	            Predchádzajúca
	          </button>
	          <button
	            class="messages-pagination__btn"
	            :disabled="messagesMeta.current_page >= messagesMeta.last_page"
	            @click="loadMessages(messagesMeta.current_page + 1)"
	          >
	            Ďalšia
	          </button>
	        </div>
	      </div>
	    </div>
    
    <!-- Message Detail Modal -->
    <div class="modal-overlay" v-if="selectedMessage" @click="selectedMessage = null">
      <div class="modal-content" @click.stop>
        <button class="modal-close" @click="selectedMessage = null">✕</button>
        <div class="modal-header">
          <h3>{{ selectedMessage.first_name }} {{ selectedMessage.last_name }}</h3>
          <p>{{ selectedMessage.email }} | {{ selectedMessage.phone || 'Bez telefónu' }}</p>
          <p class="modal-date">{{ formatDate(selectedMessage.created_at) }}</p>
        </div>
        <div class="modal-body">
          <p>{{ selectedMessage.message }}</p>
        </div>
        <div class="modal-footer">
          <span class="ip-info">IP: {{ selectedMessage.ip_address }}</span>
        </div>
      </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" v-if="messageToDelete" @click="messageToDelete = null">
      <div class="modal-content delete-modal" @click.stop>
        <div class="modal-header">
          <h3>Zmazať správu?</h3>
        </div>
        <div class="modal-body">
          <p>Naozaj chcete zmazať správu od <strong>{{ messageToDelete.first_name }} {{ messageToDelete.last_name }}</strong>?</p>
          <p class="warning-text">Táto akcia je nevratná.</p>
        </div>
        <div class="modal-actions">
          <button class="btn-cancel" @click="messageToDelete = null">Zrušiť</button>
          <button class="btn-confirm-delete" @click="deleteMessage">Zmazať</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin'
})

	const config = useRuntimeConfig()
	const { ensureCsrfCookie, csrfHeaders } = useAuth()

	const stats = ref<any>(null)
	const messages = ref<any[]>([])
	const messagesMeta = ref({
	  current_page: 1,
	  last_page: 1,
	  per_page: 20,
	  total: 0,
	})
	const filter = ref('')
	const selectedMessage = ref<any>(null)
	const messageToDelete = ref<any>(null)
	const chartCanvas = ref<HTMLCanvasElement | null>(null)
	let chartInstance: any = null

const fetchWithAuth = (url: string, options: any = {}) => {
  const method = (options?.method || 'GET').toUpperCase()
  return $fetch(url, {
    ...options,
    credentials: 'include',
    headers: {
      Accept: 'application/json',
      ...(options.headers || {}),
      ...(method !== 'GET' ? csrfHeaders() : {}),
    },
  })
}

const loadStats = async () => {
  try {
    stats.value = await fetchWithAuth(`${config.public.apiUrl}/admin/contact-stats`)
    await nextTick()
    renderChart()
  } catch (e: any) {
    console.error('Failed to load stats:', e)
  }
}

	const loadMessages = async (page: number = messagesMeta.value.current_page) => {
	  try {
	    messagesMeta.value.current_page = Math.max(1, page)
	    const response: any = await fetchWithAuth(`${config.public.apiUrl}/admin/contact-messages`, {
	      params: {
	        filter: filter.value || undefined,
	        page: messagesMeta.value.current_page,
	      },
	    })
	    messages.value = response.data || []
	    messagesMeta.value = response.meta || messagesMeta.value
	  } catch (e: any) {
	    console.error('Failed to load messages:', e)
	  }
	}

const markRead = async (id: number) => {
  try {
    await ensureCsrfCookie()
    await fetchWithAuth(`${config.public.apiUrl}/admin/contact-messages/${id}/read`, { method: 'POST' })
  } catch (e) {
    console.error('Failed to mark as read:', e)
  }
}

const confirmDelete = (msg: any) => {
  messageToDelete.value = msg
}

	const deleteMessage = async () => {
	  if (!messageToDelete.value) return
	  try {
	    await ensureCsrfCookie()
	    await fetchWithAuth(`${config.public.apiUrl}/admin/contact-messages/${messageToDelete.value.id}`, { method: 'DELETE' })
	    messageToDelete.value = null
	    await loadMessages()
	    if (messagesMeta.value.current_page > messagesMeta.value.last_page) {
	      messagesMeta.value.current_page = messagesMeta.value.last_page
	      await loadMessages()
	    }
	    loadStats()
	  } catch (e) {
	    console.error('Failed to delete:', e)
	  }
	}

const openMessage = (msg: any) => {
  selectedMessage.value = msg
  if (!msg.is_read) {
    markRead(msg.id)
    msg.is_read = true
  }
}

const formatDate = (dateStr: string) => {
  return new Date(dateStr).toLocaleDateString('sk-SK', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const renderChart = async () => {
  if (!chartCanvas.value || !stats.value?.chart) return
  
  const { Chart, registerables } = await import('chart.js')
  Chart.register(...registerables)
  
  if (chartInstance) {
    chartInstance.destroy()
  }
  
  const ctx = chartCanvas.value.getContext('2d')
  if (!ctx) return
  
  chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: stats.value.chart.map((d: any) => d.label),
      datasets: [{
        label: 'Počet správ',
        data: stats.value.chart.map((d: any) => d.count),
        backgroundColor: 'rgba(203, 155, 81, 0.7)',
        borderColor: 'rgba(203, 155, 81, 1)',
        borderWidth: 1,
        borderRadius: 4,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  })
}

	onMounted(() => {
	  loadStats()
	  loadMessages()
	})

onUnmounted(() => {
  if (chartInstance) {
    chartInstance.destroy()
  }
})
</script>
