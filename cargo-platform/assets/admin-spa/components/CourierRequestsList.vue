<template>
  <div>
    <h2>Pending Courier Verifications</h2>
    <div v-if="loading">Loading...</div>
    <ul v-else>
      <li v-for="item in items" :key="item.id" class="row">
        <div>
          <strong>User #{{ item.userId }}</strong>
          <div>Passport: {{ item.passportPath || 'uploaded' }}</div>
        </div>
        <verify-button :id="item.id" @verified="onVerified" />
        <button @click="$emit('review', item)">Review</button>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import axios from 'axios';
import VerifyButton from './VerifyButton.vue';

const items = ref<any[]>([]);
const loading = ref<boolean>(false);

async function fetchItems() {
  loading.value = true;
  try {
    const { data } = await axios.get('/api/admin/couriers/pending');
    items.value = data.items || [];
  } finally {
    loading.value = false;
  }
}

function onVerified(id: number) {
  items.value = items.value.filter(i => i.id !== id);
}

onMounted(fetchItems);
</script>

<style scoped>
.row { display: flex; gap: 1rem; align-items: center; padding: .5rem 0; }
</style>