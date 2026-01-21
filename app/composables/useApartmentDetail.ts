import { apartmentsByCode } from '~/data/apartments'

type BreakdownRow = {
    label: string
    value: string | number
    emphasis?: boolean
}

export const useApartmentDetail = () => {
    const route = useRoute()
    const code = String(route.params.code ?? '').trim()
    const apartment = apartmentsByCode[code] || null

    if (!apartment) {
        throw createError({ statusCode: 404, statusMessage: 'Jednotka neexistuje' })
    }

    // SEO
    useSeoMeta({
        title: `Jednotka ${apartment.code} | Rezidencia Žilina`,
        description: `Detail jednotky ${apartment.code} – výmera ${apartment.areaM2} m².`,
    })

    // Formatters
    const skNumber = new Intl.NumberFormat('sk-SK', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
    const skCurrency = new Intl.NumberFormat('sk-SK', { style: 'currency', currency: 'EUR' })

    // Display values
    const areaDisplay = computed(() => skNumber.format(apartment.areaM2))
    const priceWithVatDisplay = computed(() => skCurrency.format(apartment.priceWithVatEur))
    const priceWithoutVatDisplay = computed(() => skCurrency.format(apartment.priceWithVatEur / 1.23))

    // Breakdown Logic
    const baseBreakdown = [
        { label: 'Denná miestnosť', value: 37.32 },
        { label: 'Predsieň', value: 2.85 },
        { label: 'Hygienické zariadenie', value: 3.13 },
        { label: 'Izba', value: 14.33 },
        { label: 'Mezonet', value: 24.22 },
        { label: 'Pivnica', value: 2.07 },
    ] as const

    const breakdownRows = computed<BreakdownRow[]>(() => {
        const totalBase = baseBreakdown.reduce((sum, item) => sum + item.value, 0)
        const total = apartment.areaM2

        const raw = baseBreakdown.map((item) => ({
            label: item.label,
            value: item.value,
            ratio: totalBase > 0 ? item.value / totalBase : 0,
        }))

        const scaled = raw.map((item) => ({
            label: item.label,
            value: Math.round(total * item.ratio * 100) / 100,
        }))

        const sumScaled = scaled.reduce((sum, item) => sum + item.value, 0)
        const diff = Math.round((total - sumScaled) * 100) / 100
        if (scaled.length > 0) {
            const lastItem = scaled[scaled.length - 1]
            if (lastItem) {
                lastItem.value = Math.round((lastItem.value + diff) * 100) / 100
            }
        }

        return [
            ...scaled.map((item) => ({
                label: item.label,
                value: skNumber.format(item.value),
            })),
            {
                label: 'Celková výmera',
                value: skNumber.format(apartment.areaM2),
                emphasis: true,
            },
        ]
    })

    // Standards Slides
    const standardsSlides = [
        { caption: 'Spálňa A', src: '/images/apartments/standards/SPALNA-A.jpg' },
        { caption: 'Spálňa B', src: '/images/apartments/standards/SPALNA-B.jpg' },
        { caption: 'Vstupná hala', src: '/images/apartments/standards/VSTUPNA-HALA-A.jpg' },
        { caption: 'Vstupná hala 2', src: '/images/apartments/standards/VSTUPNA-HALA-B.jpg' },
        { caption: 'Kúpeľňa A', src: '/images/apartments/standards/KUPELNA-A.jpg' },
        { caption: 'Kúpeľňa B', src: '/images/apartments/standards/KUPELNA-B.jpg' },
        { caption: 'Kúpeľňa C', src: '/images/apartments/standards/KUPELNA-C.jpg' },
        { caption: 'Kuchyňa', src: '/images/apartments/standards/KUCHYNA.jpg' },
        { caption: 'Obývačka A', src: '/images/apartments/standards/OBYVACKA-A.jpg' },
        { caption: 'Obývačka B', src: '/images/apartments/standards/OBYVACKA-B.jpg' },
        { caption: 'Izba A', src: '/images/apartments/standards/IZBA-A.jpg' },
        { caption: 'Izba B', src: '/images/apartments/standards/IZBA-B.jpg' },
    ].map((item) => ({
        ...item,
        src: encodeURI(item.src),
        alt: `Štandardy - ${item.caption}`,
    }))

    return {
        apartment,
        areaDisplay,
        priceWithVatDisplay,
        priceWithoutVatDisplay,
        breakdownRows,
        standardsSlides,
    }
}
