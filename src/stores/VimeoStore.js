import { defineStore } from 'pinia'

export const useVimeoStore = defineStore('vimeo', {
  state: () => ({
    videos: [],
	projects:[],
    currentVideoId: null,
    loading: false,
    error: null
  }),
 
  actions: {
    async fetchVideos() {
      this.loading = true
      this.error = null

      try {
        // const res = await fetch('/MightyReel/api/vimeo-videos.php')
		const res = await fetch('/MightyReel/api/vimeo-allVideos.php')
        const json = await res.json()
		
        this.videos = json.data || []

        if (!this.currentVideoId && this.videos.length) {
          this.currentVideoId = this.videos[0].uri.replace('/videos/', '')
        }
      } catch (err) {
        this.error = String(err)
      } finally {
        this.loading = false
      }
    },
	async fetchProjects() {
      this.loading = true
      this.error = null

      try {
        const proj = await fetch('/MightyReel/api/vimeo-projects.php?per_page=10')
        const proj_json = await proj.json()
		console.log(proj_json.data)
        this.projects = proj_json.data || []
      } catch (err) {
        this.error = String(err)
      } finally {
        this.loading = false
      }
    },
	async fetchMightyReel() {
      this.loading = true
      this.error = null

      try {
        const mr = await fetch('/MightyReel/api/vimeo-reelVideos.php')
        const mr_json = await mr.json()
        this.MightyReel= mr_json.data || []
      } catch (err) {
        this.error = String(err)
      } finally {
        this.loading = false
      }
    },
	async fetchMe() {
      this.loading = true
      this.error = null

      try {
        const myInfo = await fetch('/MightyReel/api/vimeo-test.php')
        const info = await myInfo.json()
		console.log(info)
        this.me = info.data || []
		// this.reels = this.projects.filter((folder) => folder.name == "PrivateReels")
        // if (!this.currentVideoId && this.videos.length) {
        //   this.currentVideoId = this.videos[0].uri.replace('/videos/', '')
        // }
      } catch (err) {
        this.error = String(err)
      } finally {
        this.loading = false
      }
    },
    setCurrentVideo(id) {
      this.currentVideoId = id
    }
  }
})