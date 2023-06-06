<template>
  <div class="fixed inset-0" :class="!toggle ? 'pointer-events-none' : ''">
    <aside class="bg-white h-full max-w-[350px] w-[70%] drop-shadow-md absolute top-0 pt-24 px-4 sm:px-6 lg-px8  transition-all z-10" :class="toggle ? 'translate-x-[0px] opacity-100' : 'translate-x-[-100%] opacity-0'">
      <slot/>
    </aside>
    <div :class="toggle ? 'overlay' : ''" @click="$emit('close', false)"></div>
  </div>
</template>

<script setup>

  const props = defineProps({
    toggle: Boolean
  })
</script>

<style>
.overlay{
    @apply h-[100vh] bg-gray-500 bg-opacity-20 backdrop-blur-sm opacity-100 inset-0 transition-all absolute
  }
</style>