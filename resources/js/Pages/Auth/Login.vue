<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import BaseButton from '../../Shared/BaseButton.vue'; import BaseForm from '../../Shared/BaseForm.vue';
const form = useForm({ dni: '', password: '' });
function submit() { form.post('/login', { onFinish: () => form.reset('password') }); }
</script>
<template><Head title="Ingresar" /><main class="mx-auto mt-20 max-w-md rounded-xl border border-line bg-surface p-8"><p class="mb-2 text-sm font-semibold text-brand">SISTEMA ESCOLAR</p><h1 class="mb-6 text-2xl font-bold">Acceso institucional</h1><BaseForm @submit="submit"><div><label for="dni">DNI</label><input id="dni" v-model="form.dni" autocomplete="username" inputmode="numeric" maxlength="8" required /></div><div><label for="password">Contraseña</label><input id="password" v-model="form.password" type="password" autocomplete="current-password" required /></div><p v-for="(error,key) in form.errors" :key="key" role="alert" class="text-danger">{{ error }}</p><BaseButton type="submit" :disabled="form.processing">Ingresar</BaseButton></BaseForm></main></template>
