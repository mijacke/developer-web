export default defineNuxtRouteMiddleware((to, from) => {
    const { isAdmin } = useAuth()

    if (!isAdmin.value) {
        return navigateTo('/admin')
    }
})
