<template>
  <form class="container mx-auto p-4" autocomplete="off" @submit.prevent="create">
    <h1 class="title">Dodaj projekt</h1>
    <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
      <div v-if="currentUser?.is_admin" class="col-span-6">
        <label for="created_by_user_id" class="label">Wykonawca</label>
        <Autocomplete 
          :id="(item) => item.id" 
          v-model:objectId="form.created_by_user_id"
          v-model:searchQuery="userSearchQuery"
          :source="users"
          :fields="['first_name', 'last_name', 'email']"
          :list="(item) => `${item.first_name} ${item.last_name}: ${item.email}`"
          :name="(item) => `${item.first_name} ${item.last_name}`"
        />
        <FormError :error="form.errors.created_by_user_id" />
      </div>

      <div class="col-span-6">
        <label for="client_id" class="label">Klient</label>
        <Autocomplete 
          :id="(item) => item.id" 
          v-model:objectId="form.client_id" 
          v-model:searchQuery="clientSearchQuery"
          :source="clients"
          :fields="['first_name', 'last_name', 'email']"
          :list="(item) => `${item.first_name} ${item.last_name}: ${item.email}`"
          :name="(item) => `${item.first_name} ${item.last_name}`"
        />
        <FormPopup :form="FormClientCreate" label="Dodaj nowego klienta" @form-action-created="onNewClientCreated" />
        <FormError :error="form.errors.client_id" />
      </div>

      <div class="col-span-3">
        <label for="title" class="label">Tytuł</label>
        <input id="title" v-model="form.title" type="text" class="input" />
        <FormError :error="form.errors.title" />
      </div>

      <div class="col-span-3">
        <label for="type" class="label">Rodzaj projektu</label>
        <DropdownList 
          :id="(item) => item.id" 
          v-model="form.type_id"
          :name="(item) => item.name"
          :source="types"
        />
        <FormError :error="form.errors.type_id" />
      </div>

      <div class="col-span-3">
        <label for="price" class="label">Koszt malowania</label>
        <input id="price" v-model="form.price" type="number" class="input" step="any" min="0" />
        <FormError :error="form.errors.price" />
      </div>

      <div class="col-span-3">
        <label for="visualization" class="label">Koszt wizualizacji</label>
        <input id="visualization" v-model="form.visualization" type="number" class="input" step="any" min="0" />
        <FormError :error="form.errors.visualization" />
      </div>
  
      <div class="col-span-3">
        <label for="start" class="label">Data rozpoczęcia</label>
        <input id="start" ref="start" v-model="form.start" type="text" class="input" />
        <FormError :error="form.errors.start" />
      </div>

      <div class="col-span-3">
        <label for="deadline" class="label">Data zakończenia</label>
        <input id="deadline" ref="deadline" v-model="form.deadline" type="text" class="input" />
        <FormError :error="form.errors.deadline" />
      </div>

      <div class="col-span-6">
        <label class="label">Inspiracje (zdjęcia)</label>
        <UploadImages 
          v-model:images="form.inspiration_images" 
          v-model:errors="imageErrors"
        />
        <FormError :error="form.errors.images" />
      </div>

      <div class="col-span-6">
        <label for="remarks" class="label">Uwagi do projektu</label>
        <textarea id="remarks" v-model="form.remarks" class="input" rows="5" />
        <FormError :error="form.errors.remarks" />
      </div>

      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Dodaj projekt</button>
    </section>
  </form>
</template>
  
<script setup>
import { defineAsyncComponent, onMounted, ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const UploadImages = defineAsyncComponent(() => import('@/Components/UI/Form/UploadImages.vue'))
const FormPopup = defineAsyncComponent(() => import('@/Components/UI/Popup/FormPopup.vue'))
const FormClientCreate = defineAsyncComponent(() => import('@/Pages/Client/Create.vue'))
const DropdownList = defineAsyncComponent(() => import('@/Components/UI/Form/DropdownList.vue'))
const FormError = defineAsyncComponent(() => import('@/Components/UI/Form/FormError.vue'))
const Autocomplete = defineAsyncComponent(() => import('@/Components/UI/Form/Autocomplete.vue'))


defineProps({
  users: {
    type: Array,
    required: true,
  },
  clients: {
    type: Array,
    required: true,
  },
  types: {
    type: Array,
    required: true,
  },
})
  
const form = useForm({
  created_by_user_id: null,
  visualization: null,
  client_id: null,
  title: null,
  type_id: null,
  price: null,
  start: null,
  deadline: null,
  remarks: null,
  inspiration_images: [],
})
  
const start = ref(null)
const deadline = ref(null)
const page = usePage()
const imageErrors = {}
const clientSearchQuery = ref('')
const userSearchQuery = ref('')
  
onMounted(async () => {
  const { default: datepicker } = await import('@/Helpers/datepicker.js')
  datepicker.create(start, null, (event) => form.start = event.target.value)
  datepicker.create(deadline, null, (event) => form.deadline = event.target.value)
})

const onNewClientCreated = (client) => {
  form.client_id = client.id.toString()
  clientSearchQuery.value = `${client.first_name} ${client.last_name}`
}

const currentUser = computed(
  () => page.props.currentUser,
)

const create = () => form.post(route('projects.store'))
</script>