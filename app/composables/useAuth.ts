export const useAuth = () => {
    type AuthUser = {
        id: number
        name: string
        email: string
        role: string
        is_blocked?: boolean
        is_approved?: boolean
        last_login_at?: string | null
        created_at?: string
    }

    type CsrfHeaders = Record<string, string>
    type RegisterPayload = {
        name: string
        email: string
        password: string
        password_confirmation: string
    }
    type RegisterResponse = {
        message: string
        user: {
            id: number
            name: string
            email: string
        }
    }

    const user = useState<AuthUser | null>('auth-user', () => null)
    const config = useRuntimeConfig()

    const isAuthenticated = computed(() => !!user.value)
    const isAdmin = computed(() => user.value?.role === 'admin')

    const getBaseUrl = () => config.public.apiUrl.replace(/\/api\/?$/, '')

    const getCookieValue = (name: string): string | null => {
        if (import.meta.server) return null
        if (typeof document === 'undefined') return null

        const parts = document.cookie.split('; ')
        for (const part of parts) {
            if (!part.startsWith(`${name}=`)) continue
            return part.substring(name.length + 1)
        }
        return null
    }

    const getCsrfHeaders = (): CsrfHeaders => {
        const raw = getCookieValue('XSRF-TOKEN')
        const xsrf = raw ? decodeURIComponent(raw) : ''
        if (!xsrf) return {}

        return {
            'X-Requested-With': 'XMLHttpRequest',
            'X-XSRF-TOKEN': xsrf,
        }
    }

    const ensureCsrfCookie = async () => {
        if (import.meta.server) return
        if (getCookieValue('XSRF-TOKEN')) return

        await $fetch(`${getBaseUrl()}/sanctum/csrf-cookie`, {
            credentials: 'include',
        })
    }

    const login = async (credentials: any) => {
        try {
            await ensureCsrfCookie()

            // Login via Sanctum SPA session cookie
            await $fetch(`${config.public.apiUrl}/auth/login`, {
                method: 'POST',
                body: credentials,
                credentials: 'include',
                headers: {
                    Accept: 'application/json',
                    ...getCsrfHeaders(),
                },
            })

            await fetchUser()

            return true
        } catch (error: any) {
            if (error.response?.status === 403 && error.response?._data?.error === 'account_blocked') {
                throw new Error('Your account has been blocked.')
            }
            throw error
        }
    }

    const register = async (userData: RegisterPayload): Promise<RegisterResponse> => {
        await ensureCsrfCookie()

        return await $fetch<RegisterResponse>(`${config.public.apiUrl}/auth/register`, {
            method: 'POST',
            body: userData,
            credentials: 'include',
            headers: {
                Accept: 'application/json',
                ...getCsrfHeaders(),
            },
        })
    }

    const logout = async () => {
        try {
            await ensureCsrfCookie()
            await $fetch(`${config.public.apiUrl}/auth/logout`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    Accept: 'application/json',
                    ...getCsrfHeaders(),
                },
            })
        } catch (e) {
            // Ignore logout errors
        } finally {
            user.value = null
            navigateTo('/login')
        }
    }

    const fetchUser = async () => {
        try {
            const userData = await $fetch<AuthUser>(`${config.public.apiUrl}/auth/me`, {
                headers: {
                    Accept: 'application/json',
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
        isAuthenticated,
        isAdmin,
        login,
        register,
        logout,
        fetchUser,
        ensureCsrfCookie,
        csrfHeaders: getCsrfHeaders,
        refreshUser
    }
}
