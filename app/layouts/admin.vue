<template>
  <div class="admin-layout" :class="{ 'layout-right': sidebarPos === 'right', 'is-dragging': isDragging, 'sidebar-open': isSidebarOpen }">
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" @click="toggleSidebar" aria-label="Toggle Menu">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
    </button>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" @click="closeSidebar"></div>

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
        <div class="header-top">
          <h2>Admin</h2>
          <button class="close-sidebar-btn" @click="closeSidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          </button>
        </div>
        <p class="user-email">{{ user?.email }}</p>
      </div>
      
      <ul class="nav-links">
        <li>
          <NuxtLink to="/admin" exact-active-class="active" @click="closeSidebar">
            <span class="icon">🗺️</span>
            Map Editor
          </NuxtLink>
        </li>
        <li v-if="isAdmin">
          <NuxtLink to="/admin/dashboard" active-class="active" @click="closeSidebar">
            <span class="icon">📊</span>
            Dashboard
          </NuxtLink>
        </li>
        <li v-if="isAdmin">
          <NuxtLink to="/admin/messages" active-class="active" @click="closeSidebar">
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

// Responsive Sidebar Logic
const isSidebarOpen = ref(false)

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}

const closeSidebar = () => {
  if (window.innerWidth <= 750) {
    isSidebarOpen.value = false
  }
}

// Dragging Logic
const isDragging = ref(false)
const hoverSide = ref<string | null>(null)

const startDrag = (e: MouseEvent | TouchEvent) => {
  // Check if target is an interactive element (link or button)
  const target = e.target as HTMLElement
  if (target.closest('a') || target.closest('button')) {
    return // Allow normal interaction
  }

  // Disable dragging on mobile (small screens)
  if (window.innerWidth <= 750) {
    return
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

/* Mobile Toggle */
.mobile-toggle {
  display: none;
  position: fixed;
  top: 1rem;
  left: 1rem;
  z-index: 2147483640; /* High but below sidebar */
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  padding: 0.5rem;
  color: #1e293b;
  cursor: pointer;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.mobile-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 2147483645; /* Below sidebar, above everything else */
  opacity: 0;
  transition: opacity 0.3s;
  pointer-events: none;
}

.sidebar-open .mobile-overlay {
  opacity: 1;
  pointer-events: auto;
}

/* Header Close Button (Mobile Only) */
.header-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.close-sidebar-btn {
  display: none;
  background: transparent;
  border: none;
  color: #94a3b8;
  cursor: pointer;
  padding: 0.25rem;
}

/* Drop Zones Overlay */
.drop-zones {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 2147483646;
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
  transition: transform 0.3s ease, width 0.3s ease;
  z-index: 2147483647; /* Max 32-bit signed integer - MUST be on top */
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
  margin-top: 0;
}

.user-email {
  font-size: 0.85rem;
  color: #94a3b8;
  word-break: break-all;
  margin: 0;
}

.nav-links {
  list-style: none;
  padding: 1.5rem 1rem;
  flex: 1;
  margin: 0;
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
  position: relative;
}

/* RESPONSIVE STYLES */
@media (max-width: 750px) {
  .mobile-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .mobile-overlay {
    display: block;
  }
  
  .close-sidebar-btn {
    display: block;
  }
  
  .admin-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    transform: translateX(-100%);
    box-shadow: 4px 0 24px rgba(0,0,0,0.3);
    z-index: 100;
    /* Disable drag on mobile for simplicity */
    width: 280px; 
    cursor: default;
  }

  .admin-layout.layout-right .admin-sidebar {
      /* Reset layout-right behavior on mobile to standard left drawer */
      /* Or we could respect it, but typically mobile drawers are left-sided or strictly defined */
      /* Let's force left side for consistency on mobile unless specifically requested otherwise */
      left: 0;
      right: auto;
  }

  .sidebar-open .admin-sidebar {
    transform: translateX(0);
  }
  
  .admin-layout {
    /* If layout was reversed, mobile drawer should probably just be an overlay */
  }
  
  /* Ensure content doesn't break */
  .admin-content {
    width: 100%;
    padding-top: 3.5rem; /* Space for toggle button */
  }
}
</style>
