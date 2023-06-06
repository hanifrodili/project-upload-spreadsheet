<template>
    <dialog class="modal backdrop-blur-sm w-full h-full" :class="openDialog? 'modal-open' : ''">
      <div class="modal-box bg-white w-full sm:min-w-[800px]">
        <slot></slot>
      </div>
    </dialog>
</template>

<script setup>

const props = defineProps({
  openDialog: {
    type: Boolean,
    default: false
  }
})
</script>