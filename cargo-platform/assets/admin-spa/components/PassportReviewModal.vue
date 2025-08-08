<template>
  <div class="modal">
    <div class="modal-body">
      <h3>Passport Review</h3>
      <img v-if="item?.id" :src="`/api/admin/passport/${item.id}`" alt="passport" />
      <div class="actions">
        <button @click="verify">Verify</button>
        <button @click="$emit('close')">Close</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import axios from 'axios';
const props = defineProps<{ item: any }>();

async function verify() {
  await axios.post(`/api/admin/courier/${props.item.id}/verify`);
  window.dispatchEvent(new CustomEvent('verified', { detail: props.item.id }));
}
</script>

<style scoped>
.modal { position: fixed; inset: 0; background: rgba(0,0,0,.5); display: grid; place-items: center; }
.modal-body { background: #fff; padding: 1rem; border-radius: 8px; width: 600px; max-width: 95vw; }
img { max-width: 100%; }
.actions { display: flex; gap: .5rem; margin-top: 1rem; }
</style>