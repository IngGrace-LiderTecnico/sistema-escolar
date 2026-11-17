<script setup>defineProps({ type: { type: String, default: 'button' } });</script>
<template><button :type="type" class="rounded-lg bg-brand px-4 py-2 font-semibold text-surface disabled:opacity-50"><slot /></button></template>
