/**
 * Global Route Guard Plugin
 * Protects all /admin routes - redirects to login if not authenticated
 */
export default defineNuxtPlugin(() => {
    const router = useRouter()

    router.beforeEach(async (to, from) => {
        // Only protect /admin routes
        if (!to.path.startsWith('/admin')) {
            return true
        }

        if (import.meta.server) {
            return true
        }

        const { user, fetchUser } = useAuth()

        // Always fetch fresh user data to verify session
        await fetchUser()

        if (!user.value) {
            return {
                path: '/login',
                query: { redirect: to.fullPath }
            }
        }

        const adminOnlyPaths = ['/admin/dashboard', '/admin/messages']
        if (adminOnlyPaths.some(p => to.path.startsWith(p))) {
            if (user.value.role !== 'admin') {
                return { path: '/admin' }
            }
        }

        return true
    })
})
