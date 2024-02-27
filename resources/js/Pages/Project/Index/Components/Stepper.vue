<template>
  <ol
    class="flex items-center w-full" 
  >
    <li 
      v-for="i in steps" 
      :key="i"
      class="relative step"
      :class="{ 
        'step--active': i <= active,
        'step--last': i === steps
      }"
    >
      <span 
        class="step_circle"
        :class="{ 
          'step_circle--active': i <= active,
          'step_circle--focus': i === focus
        }"
        :data-step="i"
        @click="onClick"
      >
        {{ i }}
      </span>    
    </li>
  </ol>
</template>

<script setup>
defineProps({
  steps: {
    type: Number,
    required: true,
  },
  active: {
    type: Number,
    required: true,
  },
  focus: {
    type: Number,
    required: true,
  },
})

const emit = defineEmits([
  'update:steps',
  'update:active',
  'update:focus',
])

const onClick = (e) => {
  const step = e.target.getAttribute('data-step') * 1
  emit('update:focus', step)
}
</script>

<style scoped>
.step{
    @apply flex w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-100 after:border-4 after:inline-block dark:after:border-gray-700; 
}

.step--last{
    @apply w-14 after:hidden;
}

.step--active{
    @apply text-indigo-200 dark:text-indigo-200 after:border-blue-100 dark:after:border-indigo-800;
}

.step_circle{
    @apply flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full lg:h-12 lg:w-12 dark:bg-gray-700 shrink-0 cursor-pointer hover:dark:bg-indigo-600;
}

.step_circle--active{
    @apply dark:bg-indigo-800;
}

.step_circle--focus{
    @apply dark:bg-indigo-600;
}
</style>