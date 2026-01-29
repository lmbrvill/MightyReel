<template>
	<div class="video-wrapper">
  <div ref="vimeoContainer" ></div>
  </div>
   <v-btn color="primary" @click="loadNewVideo">Vuetify Works</v-btn>
</template>

<script setup>
	import { ref, onMounted, onBeforeUnmount } from 'vue'
	import Player from '@vimeo/player'

	const vimeoContainer = ref(null)
	let player = null

	onMounted(() => {
		player = new Player(vimeoContainer.value, {
			id: 130878140,     // Vimeo video ID
			width: 640,
			autoplay: false,
			loop: false
		})

		player.on('play', () => {
			console.log('Video playing')
		})

		player.on('pause', () => {
			console.log('Video paused')
		})
		player.on('ended', async () => {
			await player.loadVideo(1159277016)
			await player.play()
		})
	})

	onBeforeUnmount(() => {
		if (player) {
			player.destroy()
		}
	})

	function loadNewVideo(){
		player.loadVideo(1159277016)
	}
</script>
<style scoped>
.video-wrapper {
  border-radius: 16px;
  overflow: hidden;      /* ← THIS is the magic */
  background: #000;      /* hides edge flashes while loading */
}

/* optional — makes sure iframe fills wrapper */
.video-wrapper iframe {
  width: 100%;
  height: 100%;
}
</style>
