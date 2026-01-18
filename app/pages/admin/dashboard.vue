<template>
  <div class="dashboard-container">
    <header class="dashboard-header">
      <h1>Prehľad</h1>
    </header>

    <div v-if="pending" class="loading">Načítavam dáta...</div>
    <div v-else-if="error" class="error">Nastala chyba pri načítaní dát.</div>
    
    <div v-else class="dashboard-content">
      <!-- Stats Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <h3>Používatelia</h3>
          <div class="value">{{ stats.users.total }}</div>
          <div class="meta">
            {{ stats.users.admins }} adminov | {{ stats.users.blocked }} blokovaných
          </div>
        </div>
        <div class="stat-card highlight" v-if="stats.users.pending > 0">
          <h3>Čakajú na schválenie</h3>
          <div class="value">{{ stats.users.pending }}</div>
          <div class="meta">nových registrácií</div>
        </div>
        <div class="stat-card">
          <h3>Aktívni dnes</h3>
          <div class="value">{{ stats.users.active_today }}</div>
          <div class="meta">prihlásení za posledných 24h</div>
        </div>
        <div class="stat-card">
          <h3>Projekty</h3>
          <div class="value">{{ stats.content.projects }}</div>
          <div class="meta">{{ stats.content.localities }} lokalít</div>
        </div>
      </div>

      <!-- Filter Tabs -->
      <div class="filter-tabs">
        <button 
          :class="{ active: currentFilter === 'all' }" 
          @click="setFilter('all')"
        >Všetci</button>
        <button 
          :class="{ active: currentFilter === 'pending', highlight: stats.users.pending > 0 }" 
          @click="setFilter('pending')"
        >Čakajúci ({{ stats.users.pending }})</button>
        <button 
          :class="{ active: currentFilter === 'admins' }" 
          @click="setFilter('admins')"
        >Admini</button>
        <button 
          :class="{ active: currentFilter === 'blocked' }" 
          @click="setFilter('blocked')"
        >Blokovaní</button>
      </div>

      <!-- Users Management -->
      <div class="section-card">
        <div class="section-header">
          <h2>Správa používateľov</h2>
        </div>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Meno</th>
                <th>Email</th>
                <th>Rola</th>
                <th>Status</th>
                <th>Posledné prihlásenie</th>
                <th>Akcie</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="u in usersList.data" :key="u.id" :class="{ 'pending-row': !u.is_approved }">
                <td>{{ u.name }}</td>
                <td>{{ u.email }}</td>
                <td>
                  <span :class="['role-badge', u.role]">{{ u.role }}</span>
                </td>
                <td>
                  <span v-if="!u.is_approved" class="status-badge pending">Čaká</span>
                  <span v-else-if="u.is_blocked" class="status-badge blocked">Blokovaný</span>
                  <span v-else class="status-badge active">Aktívny</span>
                </td>
                <td>{{ u.last_login_at ? formatDate(u.last_login_at) : '-' }}</td>
                <td class="actions">
                  <div v-if="u.id !== user.id" class="action-buttons">
                    <!-- Approval actions -->
                    <template v-if="!u.is_approved">
                      <button @click="approveUser(u)" class="btn-success btn-sm">Schváliť</button>
                      <button @click="rejectUser(u)" class="btn-danger btn-sm">Odmietnuť</button>
                    </template>
                    
                    <!-- Approved user actions -->
                    <template v-else>
                      <!-- Block/Unblock -->
                      <button v-if="!u.is_blocked" @click="blockUser(u)" class="btn-warning btn-sm">Blokovať</button>
                      <button v-else @click="unblockUser(u)" class="btn-success btn-sm">Odblokovať</button>
                      
                      <!-- Promote/Demote -->
                      <button v-if="u.role !== 'admin'" @click="promoteUser(u)" class="btn-primary btn-sm">→ Admin</button>
                      <button v-else @click="demoteUser(u)" class="btn-secondary btn-sm">→ User</button>
                      
                      <!-- Delete -->
                      <button @click="deleteUser(u)" class="btn-danger btn-sm">Zmazať</button>
                    </template>
                  </div>
                  <span v-else class="text-muted">(Vy)</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Audit Logs -->
      <div class="section-card">
        <div class="section-header">
          <h2>Posledná aktivita</h2>
        </div>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Čas</th>
                <th>Používateľ</th>
                <th>Akcia</th>
                <th>Objekt</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="log in stats.recent_activity" :key="log.id">
                <td>{{ formatDate(log.created_at) }}</td>
                <td>{{ log.user }}</td>
                <td>{{ log.action }}</td>
                <td>{{ log.model_type }} {{ log.model_id ? '#' + log.model_id : '' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: ['auth', 'admin-only']
})

const { user, token } = useAuth()
const config = useRuntimeConfig()

// State
const stats = ref<any>(null)
const usersList = ref<any>(null)
const currentFilter = ref('all')

// Helper to fetch with auth
const authedFetch = (opt: any = {}) => ({
  ...opt,
  headers: {
    Authorization: `Bearer ${token.value}`,
    Accept: 'application/json'
  }
})

// Use asyncData to fetch initial data
const { data, pending, error, refresh } = await useAsyncData('dashboard-data', async () => {
  const [statsData, usersData] = await Promise.all([
    $fetch(`${config.public.apiUrl}/admin/stats`, authedFetch()),
    $fetch(`${config.public.apiUrl}/admin/users`, authedFetch())
  ])
  return { stats: statsData, users: usersData }
})

// Update refs when data changes
watchEffect(() => {
  if (data.value) {
    stats.value = data.value.stats
    usersList.value = data.value.users
  }
})

const setFilter = async (filter: string) => {
  currentFilter.value = filter
  const usersData = await $fetch(`${config.public.apiUrl}/admin/users`, authedFetch({
    params: { filter: filter === 'all' ? undefined : filter }
  }))
  usersList.value = usersData
}

const formatDate = (dateStr: string) => {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleString('sk-SK')
}

// User Actions
const approveUser = async (targetUser: any) => {
  if (!confirm(`Naozaj chcete schváliť používateľa ${targetUser.name}?`)) return
  try {
    await $fetch(`${config.public.apiUrl}/admin/users/${targetUser.id}/approve`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` }
    })
    refresh()
  } catch (e) {
    alert('Chyba pri schvaľovaní používateľa')
  }
}

const rejectUser = async (targetUser: any) => {
  if (!confirm(`Naozaj chcete odmietnuť a zmazať registráciu ${targetUser.name}?`)) return
  try {
    await $fetch(`${config.public.apiUrl}/admin/users/${targetUser.id}/reject`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` }
    })
    refresh()
  } catch (e) {
    alert('Chyba pri odmietaní používateľa')
  }
}

const promoteUser = async (targetUser: any) => {
  if (!confirm(`Naozaj chcete povýšiť ${targetUser.name} na admina?`)) return
  try {
    await $fetch(`${config.public.apiUrl}/admin/users/${targetUser.id}/promote`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` }
    })
    refresh()
  } catch (e) {
    alert('Chyba pri povyšovaní používateľa')
  }
}

const demoteUser = async (targetUser: any) => {
  if (!confirm(`Naozaj chcete degradovať ${targetUser.name} na bežného používateľa?`)) return
  try {
    await $fetch(`${config.public.apiUrl}/admin/users/${targetUser.id}/demote`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` }
    })
    refresh()
  } catch (e) {
    alert('Chyba pri degradovaní používateľa')
  }
}

const blockUser = async (targetUser: any) => {
  if (!confirm(`Naozaj chcete zablokovať používateľa ${targetUser.name}?`)) return
  try {
    await $fetch(`${config.public.apiUrl}/admin/users/${targetUser.id}/block`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` }
    })
    refresh()
  } catch (e) {
    alert('Chyba pri blokovaní používateľa')
  }
}

const unblockUser = async (targetUser: any) => {
  if (!confirm(`Naozaj chcete odblokovať používateľa ${targetUser.name}?`)) return
  try {
    await $fetch(`${config.public.apiUrl}/admin/users/${targetUser.id}/unblock`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` }
    })
    refresh()
  } catch (e) {
    alert('Chyba pri odblokovaní používateľa')
  }
}

const deleteUser = async (targetUser: any) => {
  if (!confirm(`POZOR: Naozaj chcete natrvalo zmazať používateľa ${targetUser.name}? Táto akcia je nevratná!`)) return
  try {
    await $fetch(`${config.public.apiUrl}/admin/users/${targetUser.id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${token.value}` }
    })
    refresh()
  } catch (e) {
    alert('Chyba pri mazaní používateľa')
  }
}
</script>

