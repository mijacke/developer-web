export const useAuth = () => {
    const user = useState('auth-user', () => null as any)
    const token = useCookie('auth-token')
    const config = useRuntimeConfig()

    const isAuthenticated = computed(() => !!token.value)
    const isAdmin = computed(() => user.value?.role === 'admin')

    const login = async (credentials: any) => {
        try {
            const response: any = await $fetch(`${config.public.apiUrl}/auth/login`, {
                method: 'POST',
                body: credentials
            })

            token.value = response.token
            user.value = response.user

            return true
        } catch (error: any) {
            if (error.response?.status === 403 && error.response?._data?.error === 'account_blocked') {
                throw new Error('Your account has been blocked.')
            }
            throw error
        }
    }

    const register = async (userData: any) => {
        try {
            const response: any = await $fetch(`${config.public.apiUrl}/auth/register`, {
                method: 'POST',
                body: userData
            })

            token.value = response.token
            user.value = response.user

            return true
        } catch (error) {
            throw error
        }
    }

    const logout = async () => {
        try {
            if (token.value) {
                await $fetch(`${config.public.apiUrl}/auth/logout`, {
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${token.value}`
                    }
                })
            }
        } catch (e) {
            // Ignore errors during logout
        } finally {
            token.value = null
            user.value = null
            navigateTo('/login')
        }
    }

    const fetchUser = async () => {
        if (!token.value) return

        try {
            const userData = await $fetch(`${config.public.apiUrl}/auth/me`, {
                headers: {
                    Authorization: `Bearer ${token.value}`
                }
            })
            user.value = userData
        } catch (error) {
            // Token invalid or expired
            token.value = null
            user.value = null
        }
    }

    return {
        user,
        token,
        isAuthenticated,
        isAdmin,
        login,
        register,
        logout,
        fetchUser
    }
}
