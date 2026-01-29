<template>
  <img
    :src="thumbnail"
    alt="Vimeo thumbnail"
  />
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  videoUrl: String
});

const thumbnail = computed(() => {
  const id = props.videoUrl.split("/").pop();
  return `https://vumbnail.com/${id}.png?v=${Date.now()}`;
});
</script>
