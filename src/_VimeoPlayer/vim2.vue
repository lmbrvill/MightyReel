<template>
  <div class="wrap">
    <div ref="container"></div>

    <div class="mask" :class="{ show: masking }"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue"
import Player from "@vimeo/player"

const container = ref(null)
const masking = ref(false)

const playlist = [130878140, 1159277016, 130878140]
let idx = 0
let player

onMounted(async() => {
   

  player = new Player(container.value, {
    id: playlist[idx],
    controls: true,
    autoplay: true,
    muted:true,
    responsive: true
  })

  player.on("ended", async () => {
    masking.value = true

    idx = (idx + 1) % playlist.length
    try {
      await player.loadVideo(playlist[idx])
      await player.play()
      // don’t drop mask here yet—wait for play/loaded to be safe
    } catch (e) {
      masking.value = false
      console.error(e)
    }
  })

  // “play” is a good signal that UI + video are stable again
  player.on("play", () => {
    masking.value = false
  })

  // also handle cases where loaded happens before play
  player.on("loaded", () => {
    // keep it masked if autoplay gets blocked; play handler will unmask
    // but if you want it to unmask on loaded regardless, uncomment:
    // masking.value = false
  })
//    const res = await fetch('/MightyReel/api/vimeo-projects.php?per_page=10')
// const data = await res.json()
// console.log(data)
})

onBeforeUnmount(() => player?.destroy())
</script>

<style scoped>
.wrap {
  position: relative;
  border-radius: 4px;
  overflow: hidden;      /* ← THIS is the magic */
  background: #000;      /* hides edge flashes while loading */
}
.wrap iframe {
  width: 100%;
  height: 100%;
}
/* black overlay; swap for poster/blur if you want */
.mask {
  position: absolute;
  inset: 0;
  opacity: 0;
  pointer-events: none;
  transition: opacity 180ms ease;
  background: #000;
}

.mask.show {
  opacity: 1;
}
</style>
