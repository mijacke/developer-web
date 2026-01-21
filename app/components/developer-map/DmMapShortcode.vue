<template>
    <DeveloperMapDmMap v-if="mapKey" :map-key="mapKey" />
    <span v-else>{{ content }}</span>
</template>

<script setup lang="ts">
import { parseDmMapShortcode } from '~/utils/shortcodes'

const props = defineProps<{
    content: string
}>()

const mapKey = computed(() => parseDmMapShortcode(props.content))
</script>
