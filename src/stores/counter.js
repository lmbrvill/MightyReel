import { defineStore } from "pinia"
import { ref } from "vue"

export const useAppStore = defineStore("app", () => {
  const message = ref("hello")  
    const video = 2212959312
    const playerHeight = ref(560)

  return { message, video, playerHeight}
})
