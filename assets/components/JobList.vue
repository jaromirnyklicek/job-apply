<template>
    <div class="container my-5">
        <h1 class="mb-4">Inzeráty</h1>

        <div v-if="loading">Načítám…</div>

        <div v-else class="row g-3">
            <div v-for="job in jobs" :key="job.id" class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ job.title }}</h5>
                        <p class="card-text text-muted" v-html="job.description"></p>
                        <router-link :to="`/job/${job.id}`" class="btn btn-primary">
                            Zobrazit detail
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const jobs = ref([])
const loading = ref(true)

onMounted(async () => {
    try {
        const res = await fetch('/api/jobs')
        jobs.value = await res.json()
    } catch (e) {
        console.error('Error while fetching jobs:', e)
    } finally {
        loading.value = false
    }
})
</script>
