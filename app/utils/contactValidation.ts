// Contact form validation utilities

export interface ContactFormData {
    firstName: string
    lastName: string
    email: string
    phone: string
    message: string
    gdpr: boolean
}

export type ValidationErrors = Record<string, string>

// Email regex pattern
export const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

// Slovak phone pattern: +421XXXXXXXXX or 09XXXXXXXX
export const phonePattern = /^(\+421|0)[0-9]{9}$/

/**
 * Validate a single field of the contact form
 * @param field - Field name to validate
 * @param formData - Current form data
 * @param errors - Current errors object (will be mutated)
 * @returns boolean - Whether the field is valid
 */
export const validateField = (
    field: string,
    formData: ContactFormData,
    errors: ValidationErrors
): boolean => {
    let isValid = true

    switch (field) {
        case 'firstName':
            if (!formData.firstName.trim()) {
                errors.firstName = 'Meno je povinné'
                isValid = false
            } else if (formData.firstName.length < 2) {
                errors.firstName = 'Meno musí mať aspoň 2 znaky'
                isValid = false
            } else {
                delete errors.firstName
            }
            break

        case 'lastName':
            if (!formData.lastName.trim()) {
                errors.lastName = 'Priezvisko je povinné'
                isValid = false
            } else if (formData.lastName.length < 2) {
                errors.lastName = 'Priezvisko musí mať aspoň 2 znaky'
                isValid = false
            } else {
                delete errors.lastName
            }
            break

        case 'email':
            if (!formData.email.trim()) {
                errors.email = 'E-mail je povinný'
                isValid = false
            } else if (!emailPattern.test(formData.email)) {
                errors.email = 'Zadajte platný e-mail'
                isValid = false
            } else {
                delete errors.email
            }
            break

        case 'phone':
            // Phone is optional, but if provided must be valid
            if (formData.phone.trim() && !phonePattern.test(formData.phone.replace(/\s/g, ''))) {
                errors.phone = 'Telefón musí byť v formáte +421XXXXXXXXX'
                isValid = false
            } else {
                delete errors.phone
            }
            break

        case 'message':
            if (!formData.message.trim()) {
                errors.message = 'Správa je povinná'
                isValid = false
            } else if (formData.message.length < 10) {
                errors.message = 'Správa musí mať aspoň 10 znakov'
                isValid = false
            } else {
                delete errors.message
            }
            break

        case 'gdpr':
            if (!formData.gdpr) {
                errors.gdpr = 'Musíte súhlasiť so spracovaním údajov'
                isValid = false
            } else {
                delete errors.gdpr
            }
            break
    }

    return isValid
}

/**
 * Validate all fields of the contact form
 * @param formData - Form data to validate
 * @param errors - Errors object (will be mutated)
 * @returns boolean - Whether the entire form is valid
 */
export const validateForm = (
    formData: ContactFormData,
    errors: ValidationErrors
): boolean => {
    const fields = ['firstName', 'lastName', 'email', 'phone', 'message', 'gdpr']
    let isValid = true

    fields.forEach(field => {
        if (!validateField(field, formData, errors)) {
            isValid = false
        }
    })

    return isValid
}

/**
 * Create a fresh, empty contact form data object
 */
export const createEmptyFormData = (): ContactFormData => ({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    message: '',
    gdpr: false
})
