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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Chyba</h1>
            <router-link to="/" class="btn btn-secondary">Zpět</router-link>
        </div>
        <div v-if="error" class="alert alert-danger">
            <b>Chyba inzerátu:</b> {{ error }}
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import AnswerForm from './AnswerForm.vue'

const route = useRoute()
const job = ref(null)
const error = ref(null)
const loading = ref(true)

onMounted(async () => {
    try {
        const res = await fetch(`/api/jobs/${route.params.id}`)
        const data = await res.json()
        if(!res.ok) {
            error.value = data.error || 'Chyba pri nacitani detailu inzeratu'
        } else {
            job.value = data.job
        }
    } catch (e) {
        console.error('Error loading job detail:', e)
    } finally {
        loading.value = false
    }
})
</script>
