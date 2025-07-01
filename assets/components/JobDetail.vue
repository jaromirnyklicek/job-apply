<template>
    <div v-if="loading">Načítám detail…</div>
    <div v-else-if="job">
        <h2>{{ job.title }}</h2>
        <p v-html="job.description" />
        <router-link to="/" class="btn btn-secondary">Zpět</router-link>
    </div>
    <div v-else>
        <p class="text-danger">Inzerát nebyl nalezen.</p>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const job = ref(null)
const loading = ref(true)

onMounted(async () => {
    try {
        const res = await fetch(`/api/jobs/${route.params.id}`)
        job.value = await res.json()
    } catch (e) {
        console.error('Error loading job detail:', e)
    } finally {
        loading.value = false
    }
})
</script>
