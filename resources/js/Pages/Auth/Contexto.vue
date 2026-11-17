<script setup>
import { computed, ref } from 'vue'; import { useForm } from '@inertiajs/vue3';
import AppLayout from '../../Shared/AppLayout.vue'; import BaseCard from '../../Shared/BaseCard.vue'; import BaseButton from '../../Shared/BaseButton.vue'; import BaseForm from '../../Shared/BaseForm.vue';
const props = defineProps({ perfiles: Array });
const periods = computed(() => [...new Map(props.perfiles.map(p => [p.periodo_academico_id, { id: p.periodo_academico_id, nombre: p.periodo, actual: p.en_curso }])).values()]);
const selectedPeriod = ref(periods.value.find(p=>p.actual)?.id ?? periods.value[0]?.id ?? '');
const available = computed(()=>props.perfiles.filter(p=>p.periodo_academico_id===Number(selectedPeriod.value)));
const form = useForm({ perfil_id: '' });
</script>
<template><AppLayout title="Elige tu periodo y perfil"><BaseCard><p class="mb-6 text-muted">Cada sesión utiliza un solo perfil. Los permisos de tus otros perfiles no se suman.</p><BaseForm v-if="perfiles.length" @submit="form.post('/contexto')"><div><label for="periodo">Periodo académico</label><select id="periodo" v-model="selectedPeriod" @change="form.perfil_id = ''"><option v-for="p in periods" :key="p.id" :value="p.id">{{ p.nombre }}{{ p.actual ? ' · En curso' : '' }}</option></select></div><div><label for="perfil">Perfil</label><select id="perfil" v-model="form.perfil_id" required><option value="" disabled>Selecciona un perfil</option><option v-for="p in available" :key="p.id" :value="p.id">{{ p.rol }}</option></select></div><p v-for="(error,key) in form.errors" :key="key" class="text-danger">{{ error }}</p><BaseButton type="submit" :disabled="form.processing || !form.perfil_id">Continuar</BaseButton></BaseForm><p v-else role="alert">No tienes perfiles activos. Solicita una asignación al administrador.</p></BaseCard></AppLayout></template>
