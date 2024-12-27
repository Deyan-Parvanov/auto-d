<template>
  <main>
    <router-view />
  
    <div v-if="flashSuccess" class="success">
    {{ flashSuccess }}
    </div>
    <slot>Default</slot>

  </main>
</template>

<script setup>
import { computed } from 'vue'

const flashSuccess = computed(
    () => page.props.flash.success,
)
</script>

<style scoped>
  .success {
    background-color: green;
    color: white;
  }
</style>