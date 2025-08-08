<template>
  <section class="container">
    <h1>Admin Realtime Console</h1>
    <courier-requests-list @review="openReview" />
    <passport-review-modal v-if="reviewItem" :item="reviewItem" @close="reviewItem = null" />
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import CourierRequestsList from './components/CourierRequestsList.vue';
import PassportReviewModal from './components/PassportReviewModal.vue';

const reviewItem = ref<any | null>(null);
function openReview(item: any) { reviewItem.value = item; }
</script>

<style>
.container { max-width: 980px; margin: 2rem auto; font-family: sans-serif; }
</style>