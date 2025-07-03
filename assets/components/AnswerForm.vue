<template>
    <pre>{{ values }}</pre>

    <Form v-slot="{ handleSubmit }" :validation-schema="schema">
        <form @submit.prevent="handleSubmit(onSubmit)">

            <div v-if="status.message" :class="['alert', status.success ? 'alert-success' : 'alert-danger']">
                {{ status.message }}
            </div>

            <div class="mb-3">
                <label class="form-label">Jméno *</label>
                <Field name="name" type="text" class="form-control" />
                <ErrorMessage name="name" class="text-danger" />
            </div>

            <div class="mb-3">
                <label class="form-label">E-mail *</label>
                <Field name="email" type="email" class="form-control" />
                <ErrorMessage name="email" class="text-danger" />
            </div>

            <div class="mb-3">
                <label class="form-label">Telefon *</label>
                <Field name="phone" type="text" class="form-control" />
                <ErrorMessage name="phone" class="text-danger" />
            </div>

            <div class="mb-3">
                <label class="form-label">LinkedIn profil</label>
                <Field name="linkedin" type="url" class="form-control" />
                <ErrorMessage name="linkedin" class="text-danger" />
            </div>

            <div class="mb-3">
                <label class="form-label">Motivační dopis *</label>
                <Field name="coverLetter" as="textarea" class="form-control" rows="4" />
                <ErrorMessage name="coverLetter" class="text-danger" />
            </div>

            <div class="mb-3">
                <label class="form-label">Očekávaná měsíční mzda (HPP, Kč)</label>
                <Field name="salary" type="number" class="form-control" />
                <ErrorMessage name="salary" class="text-danger" />
            </div>

            <button type="submit" class="btn btn-primary" :disabled="submitting">
                <span v-if="submitting" class="spinner-border spinner-border-sm me-2"></span>
                {{ submitting ? 'Odesílám...' : 'Odeslat odpověď' }}
            </button>
        </form>
    </Form>
</template>

<script setup>
import { Field, ErrorMessage, Form } from 'vee-validate'
import * as yup from 'yup'
import { useRoute } from 'vue-router'
import { ref } from 'vue'

const route = useRoute()
const jobId = route.params.id

const schema = yup.object({
    name: yup.string().required('Jméno je povinné'),
    email: yup.string().email('Neplatný e-mail').required('E-mail je povinný'),
    phone: yup.string().required('Telefon je povinný'),
    linkedin: yup.string().url('Neplatná URL').nullable(),
    coverLetter: yup.string().required('Motivační dopis je povinný'),
    salary: yup.number().typeError('Zadejte číslo').nullable(),
})

const submitting = ref(false)
const status = ref({ message: '', success: false })

const onSubmit = async (values) => {
    submitting.value = true
    status.value = { message: '', success: false }

    const payload = {
        ...values,
        job_id: jobId,
    }

    try {
        const response = await fetch('/api/answers', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        })

        if (response.ok) {
            status.value = {
                message: 'Odpověď byla úspěšně odeslána!',
                success: true,
            }
        } else {
            const error = await response.json()
            status.value = {
                message: 'Chyba: ' + (error.error || 'Neznámá chyba'),
                success: false,
            }
        }
    } catch (e) {
        console.error(e)
        status.value = {
            message: 'Nepodařilo se odeslat odpověď.',
            success: false,
        }
    } finally {
        submitting.value = false
    }
}
</script>
