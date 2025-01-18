<template>
  <section class="mt-8 bg-gray-800 p-2 rounded-sm grid grid-cols-1 gap-2">
    <div>
      <div>Firma: </div>
      <ol class="!list-disc ml-4" style="list-style-type: disc;">
        <li>
          <span>Przychód: </span> <span class="font-medium">{{ form.price ? `${(form.price).toFixed(2)} zł` : 'BRAK' }}</span>
        </li>
        <li>
          <span>Dochód: </span> <span class="font-medium">{{ form.price ? `${(form.price * form.costs / 100).toFixed(2) } zł` : 'BRAK' }}</span>
        </li>
      </ol>
    </div>
    <div v-if="form.costs < 100 && typeof form.costs === 'number' && typeof form.price === 'number' && form.distribution">
      <div>Podział: </div>
      <ol class="!list-disc ml-4" style="list-style-type: disc;">
        <li v-for="([key, value], index) in Object.entries(form.distribution)" :key="key">
          <span>{{ users.find(admin => admin.id == key).first_name }}:</span> <span class="font-medium">{{ ((form.price - (form.price * form.costs / 100) || 0) * value / 100).toFixed(2) }} zł</span>
        </li>
      </ol>
    </div>
    <div v-else>
      <span>Podział: </span> <span class="font-medium">BRAK</span>
    </div>
  </section>
</template>

<script setup>
defineProps({
  form: {
    type: Object,
    required: true,
  },
  users: {
    type: Array, 
    required: true,
  },
})
</script>