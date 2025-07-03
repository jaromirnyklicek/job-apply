<template>
    <div v-if="loading">Načítám detail…</div>
    <div v-else-if="job">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">{{ job.title }}</h1>
            <router-link to="/" class="btn btn-secondary">Zpět</router-link>
        </div>
        <p v-html="job.description" />
        <hr>
        <AnswerForm />
    </div>
    <div v-else>
        <p class="text-danger">Inzerát nebyl nalezen.</p>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import AnswerForm from './AnswerForm.vue'

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
