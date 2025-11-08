<template>
  <div 
    :class="[
      'fixed bg-gray-100 right-4 bottom-10 mb-14 rounded-2xl shadow-lg chatbox-size flex flex-col overflow-hidden text-white popup',
      show ? 'show' : 'hide'
    ]"
  >
    <div 
      class="relative w-full 
      bg-indigo-500 p-4 
      flex flex-row justify-between items-center 
      overflow-hidden"
      style="height: 100px"
    >
      <div 
        class="absolute inset-0 rounded-full bg-gradient-to-r 
        from-pink-500 via-indigo-500 to-blue-500
        blur-xl
        animate-gradient"
      />
      <div 
        class="flex flex-row items-center gap-2 z-10"
      >
        <div 
          class="relative w-9 h-9"
        >
          <img 
            :src="Logo" 
            class="w-full h-full rounded-full"
          />
          <div 
            class="
            w-2 h-2 bg-green-300 rounded-full 
            absolute top-0 right-0 
            border border-solid border-white"
          />
        </div>
        <div 
          class="text-lg flex flex-row items-center font-bold"
        >
          Chat
        </div>
      </div>
      <button 
        class="text-md
        w-4 h-4 p-4 rounded-full
        hover:bg-gray-50/10 
        flex flex-row justify-center items-center
        z-10" 
      >
        <font-awesome-icon :icon="['fas', 'rotate-right']" />
      </button>
    </div>
    <div class="flex flex-col p-4 gap-4 overflow-y-auto">
      <ChatBubble 
        type="llm"
        :time="new Date()"
        message="That's awesome. I think our users will really appreciate the improvements."
        status="Delivered"
      />
      <ChatBubble 
        type="human"
        :time="new Date()"
        message="That's awesome. I think our users will really appreciate the improvements."
        status="Delivered"
      />
      <ChatBubble 
        type="llm"
        :time="new Date()"
        message="That's awesome. I think our users will really appreciate the improvements."
        status="Delivered"
      />
      <ChatBubble 
        type="human"
        :time="new Date()"
        message="That's awesome. I think our users will really appreciate the improvements."
        status="Delivered"
      />
      <ChatBubble 
        type="llm"
        :time="new Date()"
        message="That's awesome. I think our users will really appreciate the improvements."
        status="Delivered"
      />
      <ChatBubble 
        type="human"
        :time="new Date()"
        message="That's awesome. I think our users will really appreciate the improvements."
        status="Delivered"
      />
    </div>
    <div 
      class="p-4" 
      style="height: 100px;"
    >
      <ChatInput />
    </div>
  </div>
</template>

<script setup>
import Logo from '@/../../public/images/logo.webp'
import ChatBubble from '@/Chat/Message/ChatBubble.vue'
import ChatInput from '@/Chat/Input/ChatInput.vue'

const props = defineProps({
  show: {
    required: true,
    type: Boolean,
  },
})

const close = () => {
  emit('close')
}

const emit = defineEmits(['close'])
</script>

<style scoped>
.chatbox-size {
    width: 400px;
    height: 500px;
}

@keyframes gradient-shift {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.animate-gradient {
  background-size: 200% 200%;
  animation: gradient-shift 6s ease infinite;
}

.popup {
  transform-origin: bottom right;
  opacity: 0;
  transform: scale(0);
  transition: transform 0.4s ease, opacity 0.4s ease;
  pointer-events: none;
}

.popup.show {
  opacity: 1;
  transform: scale(1) translateY(0);
  pointer-events: auto;
}

.popup.hide {
  opacity: 0;
  transform: scale(0);
  pointer-events: none;
}
</style>