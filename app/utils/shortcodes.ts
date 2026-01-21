export function parseDmMapShortcode(input: string): string | null {
    if (!input) return null

    const match = input.match(
        /\[dm_map\b[^\]]*?\bmap_key\s*=\s*(?:"([^"]+)"|'([^']+)'|([^\s\]]+))/i
    )

    const mapKey = (match?.[1] || match?.[2] || match?.[3] || '').trim()
    return mapKey || null
}

