<template>
  <button @click="verify" :disabled="loading">{{ loading ? '...' : 'Verify' }}</button>
</template>

<script setup lang="ts">
import axios from 'axios';
const props = defineProps<{ id: number }>();
const emit = defineEmits(['verified']);
const loading = ref(false);

async function verify() {
  loading.value = true;
  try {
    await axios.post(`/api/admin/courier/${props.id}/verify`);
    emit('verified', props.id);
  } finally {
    loading.value = false;
  }
}
</script>