export type ApartmentData = {
  code: string
  floorLabel: string
  roomsLabel: string
  areaM2: number
  priceWithVatEur: number
  floorplanPrimarySrc: string
  floorplanSecondarySrc: string
}

export const apartmentsByCode: Record<string, ApartmentData> = {
  '101': {
    code: '101',
    floorLabel: '1. poschodie',
    roomsLabel: '3 izby',
    areaM2: 79.84,
    priceWithVatEur: 259900,
    floorplanPrimarySrc: '/images/apartments/floorplans/byt-101-1.png',
    floorplanSecondarySrc: '/images/apartments/floorplans/byt-101-2.png',
  },
  '102': {
    code: '102',
    floorLabel: '1. poschodie',
    roomsLabel: '3 izby',
    areaM2: 81.22,
    priceWithVatEur: 264500,
    floorplanPrimarySrc: '/images/apartments/floorplans/byt-102-1.png',
    floorplanSecondarySrc: '/images/apartments/floorplans/byt-102-2.png',
  },
  '103': {
    code: '103',
    floorLabel: '1. poschodie',
    roomsLabel: '2 izby',
    areaM2: 82.55,
    priceWithVatEur: 268900,
    floorplanPrimarySrc: '/images/apartments/floorplans/byt-103-1.png',
    floorplanSecondarySrc: '/images/apartments/floorplans/byt-103-2.png',
  },
  '201': {
    code: '201',
    floorLabel: '2. poschodie',
    roomsLabel: '3 izby',
    areaM2: 83.1,
    priceWithVatEur: 270500,
    floorplanPrimarySrc: '/images/apartments/floorplans/byt-201-1.png',
    floorplanSecondarySrc: '/images/apartments/floorplans/byt-201-2.png',
  },
  '202': {
    code: '202',
    floorLabel: '2. poschodie',
    roomsLabel: '2 izby',
    areaM2: 83.92,
    priceWithVatEur: 271500,
    floorplanPrimarySrc: '/images/apartments/floorplans/byt-202-1.png',
    floorplanSecondarySrc: '/images/apartments/floorplans/byt-202-2.png',
  },
  '203': {
    code: '203',
    floorLabel: '2. poschodie',
    roomsLabel: '2 izby',
    areaM2: 85.07,
    priceWithVatEur: 276900,
    floorplanPrimarySrc: '/images/apartments/floorplans/byt-203-1.png',
    floorplanSecondarySrc: '/images/apartments/floorplans/byt-203-2.png',
  },
}
