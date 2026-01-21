<template>
  <div class="admin-layout" :class="{ 'layout-right': sidebarPos === 'right', 'is-dragging': isDragging, 'sidebar-open': isSidebarOpen }">
    
    <!-- Teleport mobile elements to body to escape stacking contexts -->
    <Teleport to="body" :disabled="!isMobile">
      <div v-if="isMobile">
          <!-- Mobile Toggle Button -->
          <button class="mobile-toggle" @click="toggleSidebar" aria-label="Toggle Menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
          </button>

          <!-- Mobile Overlay -->
          <div class="mobile-overlay" :class="{ open: isSidebarOpen }" @click="closeSidebar"></div>
      </div>
      
      <!-- Drop Zones -->
      <div v-if="isDragging && !isMobile" class="drop-zones">
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
        :class="{ 'sidebar-open-mobile': isMobile && isSidebarOpen, 'is-mobile': isMobile }"
        @mousedown="startDrag" 
        @touchstart="startDrag"
      >
        <div class="sidebar-header">
          <div class="header-top">
            <h2>{{ user?.name || 'Admin' }}</h2>
            <button class="close-sidebar-btn" @click="closeSidebar">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
          </div>
          <p class="user-email">{{ user?.email }}</p>
        </div>
        
        <ul class="nav-links">
          <li>
            <NuxtLink to="/admin" exact-active-class="active" @click="closeSidebar">
              Map Editor
            </NuxtLink>
          </li>
          <li v-if="isAdmin">
            <NuxtLink to="/admin/dashboard" active-class="active" @click="closeSidebar">
              Dashboard
            </NuxtLink>
          </li>
          <li v-if="isAdmin">
            <NuxtLink to="/admin/messages" active-class="active" @click="closeSidebar">
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
    </Teleport>
    
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
const isMobile = ref(false)

const checkScreenSize = () => {
  isMobile.value = window.innerWidth <= 750
  if (!isMobile.value) {
    isSidebarOpen.value = false
  }
}

onMounted(() => {
  checkScreenSize()
  window.addEventListener('resize', checkScreenSize)
})

onUnmounted(() => {
  window.removeEventListener('resize', checkScreenSize)
})

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}

const closeSidebar = () => {
  if (isMobile.value) {
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
  if (isMobile.value) {
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
/* NOTE: Some styles (like .admin-sidebar when teleported) might need to be global or ::v-deep if scoped styles typically don't apply to teleported elements correctly in some setups.
   However, Vue's Teleport keeps the component instance's context, so scoped styles usually still work IF the data attributes are preserved.
   But we'll verify. To be safe, we might drop 'scoped' for specific classes or ensure they work.
*/

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
  display: flex; /* Visible when rendered via v-if isMobile */
  position: fixed;
  top: 1rem;
  left: 1rem;
  z-index: 100;
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
  z-index: 100;
  opacity: 0;
  transition: opacity 0.3s;
  pointer-events: none;
}

.mobile-overlay.open {
  display: block; /* Ensure it's block when open */
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
  z-index: 100;
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
  z-index: 100; /* Desktop z-index */
  cursor: grab;
  user-select: none;
  height: 100vh; /* Ensure full height */
}

/* When teleported to body (Mobile) */
.admin-sidebar.is-mobile {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 100; /* Max Z-Index for mobile */
    transform: translateX(-100%);
    box-shadow: 4px 0 24px rgba(0,0,0,0.3);
    cursor: default;
}

.admin-sidebar.sidebar-open-mobile {
    transform: translateX(0);
}

/* If layout-right is active but we are on mobile, we still want standard left drawer behavior */
/* Since it's teleported, it ignores parent layout flex-direction */

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

/* RESPONSIVE STYLES (Adjustments for layout when sidebar is removed flow) */
@media (max-width: 750px) {
  .close-sidebar-btn {
    display: block;
  }
  
  /* Sidebar is teleported so .admin-sidebar selector here implies global context if not careful, 
     but scoped works assuming data-v attributes are on teleported elements. 
     We handle most styling with .is-mobile class now. */

  .admin-content {
    width: 100%;
    padding-top: 3.5rem; /* Space for toggle button */
  }
}

/* Loading State */
.admin-loading {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: #1e293b;
}

.admin-loading-spinner {
  width: 48px;
  height: 48px;
  border: 4px solid rgba(203, 155, 81, 0.3);
  border-top-color: #cb9b51;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
