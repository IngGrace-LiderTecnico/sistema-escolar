<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
defineProps({ title: { type: String, required: true } }); const page = usePage();
</script>
<template><Head :title="title" /><div class="min-h-screen"><header class="border-b border-line bg-surface"><div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-5"><Link href="/inicio" class="text-lg font-bold">Sistema Escolar</Link><nav class="flex flex-wrap items-center gap-5 text-sm"><span v-if="page.props.contexto" class="text-muted">{{ page.props.contexto.perfil.rol }}</span><Link href="/contexto" class="font-semibold text-brand">Periodo y perfil</Link><Link href="/logout" method="post" as="button">Salir</Link></nav></div></header><main class="mx-auto max-w-6xl px-6 py-10"><p class="mb-2 text-sm font-semibold uppercase tracking-widest text-muted">Gestión institucional</p><h1 class="mb-8 text-3xl font-bold">{{ title }}</h1><slot /></main></div></template>
