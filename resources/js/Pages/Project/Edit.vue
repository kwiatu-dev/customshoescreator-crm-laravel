<template>
  <form class="container mx-auto p-4" autocomplete="off" @submit.prevent="edit">
    <h1 class="title">Edytuj projekt</h1>
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
          ref="inspirations"
          v-model:images="form.inspiration_images" 
          v-model:errors="imageErrors"
          @init="setImages"
        />
        <FormError :error="form.errors.images" />
      </div>

      <div class="col-span-6">
        <label for="remarks" class="label">Uwagi do projektu</label>
        <textarea id="remarks" v-model="form.remarks" class="input" rows="5" />
        <FormError :error="form.errors.remarks" />
      </div>

      <HiddenSection title="Zaawansowane opcje" class="col-span-6" :danger="hasHiddenSectionErrors">
        <div v-if="currentUser?.is_admin" class="col-span-6">
          <label for="status" class="label">Status</label>
          <DropdownList :id="(item) => item.id" v-model="form.status_id" :name="(item) => item.name" :source="statuses" :class="{'input-disabled': !isAdmin}" :disabled="!isAdmin" />
          <FormError :error="form.errors.status_id" />
        </div>

        <div class="col-span-3">
          <label for="costs" class="label">Koszty stałe</label>
          <input id="costs" v-model="form.costs" type="number" class="input" :class="{'input-disabled': !isAdmin}" :disabled="!isAdmin" />
          <FormError :error="form.errors.costs" />
        </div>
  
        <div class="col-span-3">
          <label for="commission" class="label">Prowizja</label>
          <input id="commission" v-model="form.commission" type="number" class="input" :class="{'input-disabled': !isAdmin}" :disabled="!isAdmin" />
          <FormError :error="form.errors.commission" />
        </div>

        <div v-if="isAdmin" class="col-span-6">
          <label for="distribution" class="label">Podział</label>
          <AdminDistribution v-model="form.distribution" :distribution="form.distribution" :users="users" />
          <FormError :error="form.errors.distribution" />
        </div>
      </HiddenSection>

      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Edytuj projekt</button>
    </section>
  </form>
</template>
  
<script setup>
import UploadImages from '@/Components/UI/Form/UploadImages.vue'
import FormError from '@/Components/UI/Form/FormError.vue'
import Autocomplete from '@/Components/UI/Form/Autocomplete.vue'
import FormClientCreate from '@/Pages/Client/Create.vue'
import DropdownList from '@/Components/UI/Form/DropdownList.vue'
import { onMounted, ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import datepicker from '@/Helpers/datepicker.js'
import FormPopup from '@/Components/UI/Popup/FormPopup.vue'
import AdminDistribution from '@/Components/UI/Form/AdminDistribution.vue'
import HiddenSection from '@/Components/UI/Form/HiddenSection.vue'

const props = defineProps({
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
  project: {
    required: true,
    type: Object,
  },
  statuses: {
    required: true,
    type: Array,
  },
})
  
const form = useForm({
  created_by_user_id: props.project.created_by_user_id.toString(),
  visualization: props.project.visualization,
  client_id: props.project.client_id.toString(),
  title: props.project.title,
  type_id: props.project.type_id.toString(),
  price: props.project.price,
  start: props.project.start,
  deadline: props.project.deadline,
  remarks: props.project.remarks,
  inspiration_images: props.project.images
    .filter(i => i.type_id === 1)
    .map(i => i.file),
  status_id: props.project.status.id.toString(),
  costs: props.project.costs,
  commission: props.project.commission,
  distribution: JSON.parse(props.project.distribution),
})
  
const start = ref(null)
const deadline = ref(null)
const page = usePage()
const imageErrors = {}
const clientSearchQuery = ref('')
const userSearchQuery = ref('')
const inspirations = ref(null)

const currentUser = computed(
  () => page.props.currentUser,
)

const isAdmin = computed(() => currentUser.value?.is_admin)

const hasHiddenSectionErrors = computed(() => 
  form.errors.distribution !== undefined || form.errors.commission !== undefined || form.errors.costs !== undefined || form.errors.status_id !== undefined)
  
onMounted(() => {
  datepicker.create(start, null, (event) => form.start = event.target.value)
  datepicker.create(deadline, null, (event) => form.deadline = event.target.value)
})

const onNewClientCreated = (client) => {
  form.client_id = client.id.toString()
  clientSearchQuery.value = `${client.first_name} ${client.last_name}`
}

const setImages = () => {
  inspirations.value.addImages(
    props.project.images
      .filter(image => image.type_id === 1)
      .map(image => `projects/${image.file}`), { type: 'local' })
}

const edit = () => form.put(route('projects.update', { project: props.project.id }))

//todo - usuwanie zdjęć z popup zmiana statusu
//todo - ustawienie popup na srodku ekranu
</script>