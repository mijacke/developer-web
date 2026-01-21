export const useAuth = () => {
    const user = useState('auth-user', () => null as any)
    const token = useCookie<string | null>('auth-token', {
        sameSite: 'lax',
        secure: process.env.NODE_ENV === 'production',
    })
    const config = useRuntimeConfig()

    const isAuthenticated = computed(() => !!user.value)
    const isAdmin = computed(() => user.value?.role === 'admin')

    const getBaseUrl = () => config.public.apiUrl.replace(/\/api\/?$/, '')

    const getCookieValue = (name: string): string | null => {
        if (process.server) return null
        if (typeof document === 'undefined') return null

        const parts = document.cookie.split('; ')
        for (const part of parts) {
            if (!part.startsWith(`${name}=`)) continue
            return part.substring(name.length + 1)
        }
        return null
    }

    const getCsrfHeaders = () => {
        const raw = getCookieValue('XSRF-TOKEN')
        const xsrf = raw ? decodeURIComponent(raw) : ''
        return xsrf
            ? {
                'X-Requested-With': 'XMLHttpRequest',
                'X-XSRF-TOKEN': xsrf,
            }
            : {}
    }

    const getAuthHeaders = () => {
        return token.value ? { Authorization: `Bearer ${token.value}` } : {}
    }

    const login = async (credentials: any) => {
        try {
            // 1. Get CSRF cookie
            // We need to strip '/api' from the end of the URL to get the root URL where sanctum lives
            await $fetch(`${getBaseUrl()}/sanctum/csrf-cookie`, {
                credentials: 'include'
            })

            // 2. Login (browser saves HttpOnly cookie automatically)
            const loginResponse = await $fetch<{ user: any; token: string }>(`${config.public.apiUrl}/auth/login`, {
                method: 'POST',
                body: credentials,
                credentials: 'include',
                headers: {
                    Accept: 'application/json',
                    ...getCsrfHeaders(),
                },
            })

            // 3. Fetch user to update state
            token.value = loginResponse?.token || null
            await fetchUser()

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
            await $fetch(`${getBaseUrl()}/sanctum/csrf-cookie`, {
                credentials: 'include'
            })

            await $fetch(`${config.public.apiUrl}/auth/register`, {
                method: 'POST',
                body: userData,
                credentials: 'include',
                headers: {
                    Accept: 'application/json',
                    ...getCsrfHeaders(),
                },
            })

            await fetchUser()

            return true
        } catch (error) {
            throw error
        }
    }

    const logout = async () => {
        try {
            await $fetch(`${config.public.apiUrl}/auth/logout`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    Accept: 'application/json',
                    ...getAuthHeaders(),
                    ...getCsrfHeaders(),
                },
            })
        } catch (e) {
            // Ignore errors
        } finally {
            user.value = null
            token.value = null
            navigateTo('/login')
        }
    }

    const fetchUser = async () => {
        try {
            const userData = await $fetch(`${config.public.apiUrl}/auth/me`, {
                headers: {
                    Accept: 'application/json',
                    ...getAuthHeaders(),
                },
                credentials: 'include'
            })
            user.value = userData
        } catch (error) {
            user.value = null
        }
    }

    const refreshUser = async () => {
        if (!user.value) {
            await fetchUser()
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
        fetchUser,
        refreshUser
    }
}
