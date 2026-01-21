<template>
    <div class="dm-map-embed">
        <div
            :key="mapKey"
            ref="container"
            class="dm-map-embed__container"
            :data-dm-map-key="mapKey"
        />
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    mapKey: string
}>()

const container = ref<HTMLElement | null>(null)
const { ensureLoaded, hydrate } = useDeveloperMapViewer()

const mapKey = computed(() => props.mapKey?.trim())

onMounted(async () => {
    await ensureLoaded()
    await nextTick()
    hydrate()
})

watch(mapKey, async () => {
    if (!container.value) return
    delete (container.value as any).dataset?.dmMapHydrated
    await ensureLoaded()
    await nextTick()
    hydrate()
})
</script>

<style scoped>
.dm-map-embed {
    width: 100%;
}
</style>

