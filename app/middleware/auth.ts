export default defineNuxtRouteMiddleware(async (to, from) => {
    const { isAuthenticated, isAdmin, fetchUser } = useAuth()

    // Try to fetch user if we have a token but no user data (e.g. page refresh)
    if (isAuthenticated.value && !useAuth().user.value) {
        await fetchUser()
    }

    if (!isAuthenticated.value) {
        return navigateTo({
            path: '/login',
            query: { redirect: to.fullPath }
        })
    }


})
