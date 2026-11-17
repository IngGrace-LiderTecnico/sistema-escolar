<script setup>
import { Link } from '@inertiajs/vue3'; import AppLayout from '../Shared/AppLayout.vue'; import BaseCard from '../Shared/BaseCard.vue';
defineProps({ modulos: Array });
</script>
<template><AppLayout title="Tu espacio de trabajo"><div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3"><BaseCard v-for="m in modulos" :key="m.slug"><p class="mb-3 text-sm text-muted">Equipo {{ m.equipo }}</p><h2 class="mb-6 text-xl font-semibold">{{ m.nombre }}</h2><Link :href="'/' + m.slug" class="font-semibold text-brand">Abrir módulo →</Link></BaseCard></div><p v-if="!modulos.length">Este perfil aún no tiene módulos asignados.</p></AppLayout></template>
