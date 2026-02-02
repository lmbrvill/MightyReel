import { defineStore } from 'pinia'

export const useVimeoStore = defineStore('vimeo', {
  state: () => ({
    videos: [],
	projects:[],
    currentVideoId: null,
	currentReelID: 1,
    loading: false,
    error: null
  }),
 
  actions: {
    async fetchVideos() {
		this.loading = true
		this.error = null
		try {
			const res = await fetch('/MightyReel/api/vimeo-allVideos.php')
			const json = await res.json()
			const videosRaw = json.data || []
			this.videos = videosRaw.map(video => {
				var dir, agcy
				const directorTag = video.tags?.find(t => t.tag?.startsWith("d:"));
				const agencyTag = video.tags?.find(t => t.tag?.startsWith("a:"));
				directorTag ? dir = decodeURIComponent(directorTag.tag.replace(/^d:/, "")) : dir = '';
				agencyTag ? agcy = decodeURIComponent(agencyTag.tag.replace(/^a:/, "")) : agcy = '';
				return {
					...video,
					director:dir,
					agency:agcy,
					inReel:false
				};
			});
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
	async fetchReels() {
		this.loading = true
		this.error = null
		this.reelList = [
			{
				id:1,
				videos:[
					1159257417,
					1158526288,
					198256341,
					118870787,
					121778131,
					198253825
				]
			},
			{
				id:2,
				videos:[
					1159257417,
					1158526288,
					198256341,
					118870787,
					// 121778131,
					// 198253825
				]
			}
			
		]
		// try {
		// 	const proj = await fetch('/MightyReel/api/vimeo-projects.php?per_page=10')
		// 	const proj_json = await proj.json()
		// 	console.log(proj_json.data)
		// 	this.projects = proj_json.data || []
		// } catch (err) {
		// 	this.error = String(err)
		// } finally {
		// 	this.loading = false
		// }
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
		} catch (err) {
			this.error = String(err)
		} finally {
			this.loading = false
		}
    },
	setCurrentReel(reelID){
		this.currentReelID = reelID
	},
    setCurrentVideo(id) {
      this.currentVideoId = id
    }
  }
})