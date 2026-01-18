<template>
  <div class="admin-layout" :class="{ 'layout-right': sidebarPos === 'right', 'is-dragging': isDragging }">
    <!-- Drop Zones -->
    <div v-if="isDragging" class="drop-zones">
      <div 
        class="drop-zone left" 
        :class="{ active: hoverSide === 'left', current: sidebarPos === 'left' }"
        data-side="left"
      >
        <span>Vľavo</span>
      </div>
      <div 
        class="drop-zone right" 
        :class="{ active: hoverSide === 'right', current: sidebarPos === 'right' }"
        data-side="right"
      >
        <span>Vpravo</span>
      </div>
    </div>

    <nav 
      class="admin-sidebar"
      @mousedown="startDrag" 
      @touchstart="startDrag"
    >
      <div class="sidebar-header">
        <h2>Admin</h2>
        <p class="user-email">{{ user?.email }}</p>
      </div>
      
      <ul class="nav-links">
        <li>
          <NuxtLink to="/admin" exact-active-class="active">
            <span class="icon">🗺️</span>
            Map Editor
          </NuxtLink>
        </li>
        <li v-if="isAdmin">
          <NuxtLink to="/admin/dashboard" active-class="active">
            <span class="icon">📊</span>
            Dashboard
          </NuxtLink>
        </li>
        <li v-if="isAdmin">
          <NuxtLink to="/admin/messages" active-class="active">
            <span class="icon">✉️</span>
            Správy
          </NuxtLink>
        </li>
      </ul>
      
      <div class="sidebar-footer">
        <button @click="handleLogout" class="logout-btn">
          Odhlásiť sa
        </button>
      </div>
    </nav>
    
    <main class="admin-content">
      <slot />
    </main>
  </div>
</template>

<script setup lang="ts">
const { user, logout, isAdmin } = useAuth()

// Persist sidebar position preference
const sidebarPos = useCookie('sidebar-pos', { default: () => 'left' })

// Dragging Logic
const isDragging = ref(false)
const hoverSide = ref<string | null>(null)

const startDrag = (e: MouseEvent | TouchEvent) => {
  // Check if target is an interactive element (link or button)
  const target = e.target as HTMLElement
  if (target.closest('a') || target.closest('button')) {
    return // Allow normal interaction
  }

  // If touch event, ensure we don't block scrolling unless it's a drag intention? 
  // Actually, for sidebar move, usually we want to block scroll if sidebar is fixed.
  // But sidebar might have scroll?
  // Let's prevent default only if we start dragging.
  
  isDragging.value = true
  document.body.style.userSelect = 'none'
  
  if (e.type === 'mousedown') {
    window.addEventListener('mousemove', onDrag)
    window.addEventListener('mouseup', endDrag)
  } else {
    // Prevent default to stop scrolling while dragging sidebar
    // e.preventDefault() // Need to be careful with passive listeners
    window.addEventListener('touchmove', onDrag)
    window.addEventListener('touchend', endDrag)
  }
}

const onDrag = (e: Event) => {
  let clientX = 0
  const winWidth = window.innerWidth
  
  if (e instanceof MouseEvent) {
    clientX = e.clientX
  } else if (e instanceof TouchEvent && e.touches[0]) {
    clientX = e.touches[0].clientX
  }
  
  if (clientX < winWidth / 2) {
    hoverSide.value = 'left'
  } else {
    hoverSide.value = 'right'
  }
}

const endDrag = () => {
  isDragging.value = false
  document.body.style.userSelect = ''
  
  if (hoverSide.value) {
    sidebarPos.value = hoverSide.value
  }
  
  hoverSide.value = null
  
  window.removeEventListener('mousemove', onDrag)
  window.removeEventListener('mouseup', endDrag)
  window.removeEventListener('touchmove', onDrag)
  window.removeEventListener('touchend', endDrag)
}

const handleLogout = async () => {
  await logout()
}
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
  background-color: #f8fafc;
  position: relative;
}

.admin-layout.layout-right {
  flex-direction: row-reverse;
}

/* Drop Zones Overlay */
.drop-zones {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  display: flex;
  pointer-events: none;
}

.drop-zone {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(30, 41, 59, 0.1);
  border: 4px dashed transparent;
  transition: all 0.2s;
  color: white;
  font-weight: bold;
  font-size: 2rem;
  backdrop-filter: blur(2px);
}

.drop-zone span {
  background: rgba(0,0,0,0.5);
  padding: 1rem 2rem;
  border-radius: 1rem;
  transform: scale(0.9);
  transition: transform 0.2s;
}

.drop-zone.current {
  background-color: rgba(203, 155, 81, 0.1);
}

.drop-zone.active {
  background-color: rgba(203, 155, 81, 0.4);
  border-color: #cb9b51;
}

.drop-zone.active span {
  transform: scale(1.1);
  background: #cb9b51;
}

/* Sidebar Styles */
.admin-sidebar {
  width: 260px;
  background-color: #1e293b;
  color: white;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  transition: all 0.3s ease;
  z-index: 100;
  cursor: grab; /* Indicate draggable */
  user-select: none;
}

.admin-sidebar:active {
  cursor: grabbing;
}

/* Create a new class for interactive elements to restore cursor */
.admin-sidebar a, .admin-sidebar button {
  cursor: pointer;
}

.admin-layout.is-dragging .admin-sidebar {
  opacity: 0.9;
  transform: scale(0.98);
}


.sidebar-header {
  padding: 2rem 1.5rem;
  border-bottom: 1px solid #334155;
}

.sidebar-header h2 {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
  color: #fff;
}

.user-email {
  font-size: 0.85rem;
  color: #94a3b8;
  word-break: break-all;
}

.nav-links {
  list-style: none;
  padding: 1.5rem 1rem;
  flex: 1;
}

.nav-links li {
  margin-bottom: 0.5rem;
}

.nav-links a {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: #cbd5e1;
  text-decoration: none;
  border-radius: 0.5rem;
  transition: all 0.2s;
  font-weight: 500;
}

.nav-links a:hover {
  background-color: #334155;
  color: white;
}

.nav-links a.active {
  background-color: #cb9b51;
  color: white;
}

.nav-links .icon {
  margin-right: 0.75rem;
}

.sidebar-footer {
  padding: 1.5rem;
  border-top: 1px solid #334155;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.utility-btn {
  width: 100%;
  padding: 0.75rem;
  background-color: #334155;
  border: none;
  color: #cbd5e1;
  border-radius: 0.5rem;
  cursor: grab;
  font-size: 0.9rem;
  transition: all 0.2s;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.utility-btn:active {
  cursor: grabbing;
}

.utility-btn:hover {
  background-color: #475569;
  color: white;
}

.move-btn .icon {
  font-size: 1.1rem;
}

.logout-btn {
  width: 100%;
  padding: 0.75rem;
  background-color: transparent;
  border: 1px solid #475569;
  color: #cbd5e1;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.2s;
}

.logout-btn:hover {
  background-color: #ef4444;
  border-color: #ef4444;
  color: white;
}

.admin-content {
  flex: 1;
  overflow-x: hidden;
  height: 100vh;
  overflow-y: auto;
}
</style>
