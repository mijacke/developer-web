import {
    validateField as validateFieldUtil,
    validateForm as validateFormUtil,
    createEmptyFormData,
    type ContactFormData,
    type ValidationErrors
} from '~/utils/contactValidation'

export const useContactForm = () => {
    const config = useRuntimeConfig()
    const API_URL = config.public.apiUrl

    // Form data
    const form = ref<ContactFormData>(createEmptyFormData())

    // Validation errors
    const errors = ref<ValidationErrors>({})

    // Form state
    const isSubmitting = ref(false)
    const formMessage = ref('')
    const formMessageType = ref<'success' | 'error'>('success')

    // Validate single field
    const validateField = (field: string): boolean => {
        return validateFieldUtil(field, form.value, errors.value)
    }

    // Validate all fields
    const validateAllFields = (): boolean => {
        return validateFormUtil(form.value, errors.value)
    }

    // Submit form
    const handleSubmit = async () => {
        // Clear previous messages
        formMessage.value = ''

        // Client-side validation
        if (!validateAllFields()) {
            formMessage.value = 'Prosím opravte chyby vo formulári'
            formMessageType.value = 'error'
            return
        }

        isSubmitting.value = true

        try {
            // Send to Laravel API for server-side validation
            const response = await fetch(`${API_URL}/contact`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    ...form.value,
                    phone: form.value.phone.replace(/\s/g, '') // Remove spaces from phone
                })
            })

            const data = await response.json()

            if (!response.ok) {
                // Server validation errors
                if (data.errors) {
                    // Map server errors to form fields
                    Object.keys(data.errors).forEach(key => {
                        errors.value[key] = data.errors[key][0]
                    })
                }
                formMessage.value = 'Chyba pri odosielaní formulára'
                formMessageType.value = 'error'
                return
            }

            // Success
            formMessage.value = data.message
            formMessageType.value = 'success'

            // Reset form
            form.value = createEmptyFormData()
            errors.value = {}

        } catch (error) {
            formMessage.value = 'Chyba pripojenia k serveru'
            formMessageType.value = 'error'
        } finally {
            isSubmitting.value = false
        }
    }

    return {
        form,
        errors,
        isSubmitting,
        formMessage,
        formMessageType,
        validateField,
        handleSubmit
    }
}
