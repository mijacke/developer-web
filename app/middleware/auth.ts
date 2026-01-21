export default defineNuxtRouteMiddleware(async (to, from) => {
    const { isAuthenticated, isAdmin, fetchUser } = useAuth()

    // If user is not present, define logic to try to fetch it
    if (!useAuth().user.value) {
        await fetchUser()
    }

    if (!isAuthenticated.value) {
        return navigateTo({
            path: '/login',
            query: { redirect: to.fullPath }
        })
    }


})
