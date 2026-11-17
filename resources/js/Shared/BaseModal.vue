<script setup>
import { ref, watch, nextTick } from 'vue';
import BaseButton from './BaseButton.vue';
const props = defineProps({ open: Boolean, title: { type: String, required: true } });
const emit = defineEmits(['close']); const dialog = ref();
watch(() => props.open, async value => { await nextTick(); if (value && !dialog.value.open) dialog.value.showModal(); else if (!value && dialog.value.open) dialog.value.close(); }, { immediate: true });
</script>
<template><dialog ref="dialog" :aria-label="title" class="m-auto w-full max-w-lg rounded-xl border border-line bg-surface p-6 backdrop:bg-ink/50" @cancel.prevent="emit('close')"><h2 class="mb-4 text-xl font-semibold">{{ title }}</h2><slot /><div class="mt-6"><BaseButton @click="emit('close')">Cerrar</BaseButton></div></dialog></template>
