<template>
  <form class="container mx-auto p-4" @submit.prevent="create">
    <h1 class="title">Dodaj wydarzenie</h1>
    <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
      <div v-if="currentUser?.is_admin" class="col-span-6">
        <label for="created_by_user_id" class="label">Osoba</label>
        <Autocomplete 
          :id="(item) => item.id" 
          v-model:objectId="form.user_id"
          v-model:searchQuery="userSearchQuery"
          :source="users"
          :fields="['first_name', 'last_name', 'email']"
          :list="(item) => `${item.first_name} ${item.last_name}: ${item.email}`"
          :name="(item) => `${item.first_name} ${item.last_name}`"
        />
        <FormError :error="form.errors.user_id" />
      </div>

      <div class="col-span-6">
        <label for="type" class="label">Typ wydarzenia</label>
        <DropdownList 
          :id="(item) => item.id" 
          v-model="form.type_id"
          :name="(item) => item.name"
          :source="types"
        />
        <FormError :error="form.errors.type_id" />
      </div>

      <div class="col-span-6">
        <label for="title" class="label">Tytuł</label>
        <input id="title" v-model="form.title" type="text" class="input" />
        <FormError :error="form.errors.title" />
      </div>
    
      <div class="col-span-3">
        <label for="start" class="label">Data rozpoczęcia</label>
        <input id="start" ref="start" v-model="form.start" type="text" class="input" />
        <FormError :error="form.errors.start" />
      </div>

      <div class="col-span-3">
        <label for="end" class="label">Data zakończenia</label>
        <input id="end" ref="end" v-model="form.end" type="text" class="input" />
        <FormError :error="form.errors.end" />
      </div>
  
      <div class="col-span-6">
        <label for="remarks" class="label">Uwagi</label>
        <textarea id="remarks" v-model="form.remarks" class="input" rows="5" />
        <FormError :error="form.errors.remarks" />
      </div>
    
      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Dodaj wydarzenie</button>
    </section>
  </form>
</template>
    
<script setup>
import { onMounted, ref, defineAsyncComponent, inject } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useAuthUser } from '@/Composables/useAuthUser'

const FormError = defineAsyncComponent(() => import('@/Components/UI/Form/FormError.vue'))
const Autocomplete = defineAsyncComponent(() => import('@/Components/UI/Form/Autocomplete.vue'))
const DropdownList = defineAsyncComponent(() => import('@/Components/UI/Form/DropdownList.vue'))
  
const form = useForm({
  title: null,
  start: null,
  end: null,
  remarks: null,
  user_id: null,
  type_id: null,
})

const start = ref(null)
const end = ref(null)
const currentUser = useAuthUser()
const userSearchQuery = ref('')
    
onMounted(async () => {
  const { default: datepicker } = await import('@/Helpers/datepicker')
  datepicker.create(start, null, (event) => form.start = event.target.value)
  datepicker.create(end, null, (event) => form.end = event.target.value)
})
  
const emit = defineEmits(['created'])

const clear = () => {
  form.reset()
  userSearchQuery.value = ''
  start.value.value = ''
  end.value.value = ''
}

const create = () => form.post(route('user-events.store'), {
  preserveScroll: true,
  onSuccess: () => {
    clear()
    emit('created')
  },
})

const users = inject('users', [])
const types = inject('types', [])
</script>