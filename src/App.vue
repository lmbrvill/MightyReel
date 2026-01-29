

<template>
	<v-app>
		<v-main>
			<v-container>
				<v-btn @click="showPlayer = !showPlayer" class="mb-4">
					<span v-if="showPlayer">Hide Player</span>
					<span v-else>Show Player</span>
				</v-btn>
				<v-row>
					<v-col cols="6">
						<v-card v-if="showPlayer" elevation="4" class="pa-0">
							<Player  />
						</v-card>
					</v-col>
					<v-col cols="6">

					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12">
						<!-- <div v-if="vimeo.loading">
						Loading videos…
						</div>

						<div v-else-if="vimeo.error">
						{{ vimeo.error }}
						</div> -->

						<!-- <ul v-else-if="vimeo.videos.length">
						<li v-for="v in vimeo.videos" :key="v.uri">
							{{ v.name }}
						</li> -->
						<!-- <v-expansion-panels
							v-else-if="vimeo.videos.length" 
							style="min-width: calc(4 * 170px);"
							> -->
							<v-expansion-panels style="min-width:calc(4 * 200px);">
							<v-expansion-panel title="Reels">
							</v-expansion-panel>
							<v-expansion-panel title="Folders">
							</v-expansion-panel>
							<v-expansion-panel title="Videos" >
								<v-expansion-panel-text>
								<v-container >
									<v-row>
										<v-col cols="12">
											<v-text-field 
												label="Search"
												v-model="filterTxt"
												prepend-inner-icon="mdi-magnify"
												hide-details
												density="compact"
											/>
										</v-col>
									</v-row>
									<v-row>
										<v-col cols="3">
											<v-select
												chips
												label="By Director"
												v-model="directorSearch"
												:items="['director:Lance_accord', 'Steven Spielberg', 'Florida', 'Georgia', 'Texas', 'Wyoming']"
												multiple
												hide-details
												density="compact"
												></v-select>
										</v-col>
									</v-row>
									<v-row>
										<!-- :class="'vis',[
												{'noVis': video.name == filterTxt}
											]" -->
										<div class="filterThrough"
											v-for="(video, index) in vimeo.videos"
											:key="index">
										<v-col 
											cols="3"
											v-if="
											(!filterTxt ) || 
											(filterTxt && filterTxt.length == 0) || 
											(filterTxt && video.name.indexOf(filterTxt) > -1) 
											
											 

											"
										>
											<v-card	
												class="mb-4 pa-0 vidChip"
												hide-details
											
											>
												<img :src="video.pictures.sizes[2].link" width="100%" >
												<v-card-text 
													class="pa-2 pt-0 titleTxt"> 
													{{ video.name }}
												</v-card-text>
												<div v-if="video.tags.length > 0">
													{{ video.tags[0].name }}
												</div>
											</v-card>
										</v-col>
										</div>
									</v-row>
								</v-container>
									
								</v-expansion-panel-text>
							</v-expansion-panel>
						</v-expansion-panels>
						

						<!-- <div v-else>
						No videos found.
						</div> -->
					</v-col>
				</v-row>
			</v-container>
		</v-main>
	</v-app>
</template>
<script setup> 
import {ref, onMounted, onBeforeMount, computed} from 'vue'
import Player from '@/_VimeoPlayer/vim2.vue'
import { useVimeoStore } from '@/stores/VimeoStore.js'
import vThumb from '@/components/vThumb.vue'
const vimeo = useVimeoStore()
const filterTxt = ref(null)
const showPlayer = ref(false)
const directorSearch = ref(null)
onMounted(async() =>{
	await vimeo.fetchVideos()
	console.log(vimeo.videos)
	//  const res = await fetch('/MightyReel/api/vimeo-projects.php?per_page=10')
	// const data = await res.json()
	// console.log(data)
	// await vimeo.fetchProjects()
	// await vimeo.fetchMightyReel()
	// console.log(vimeo.MightyReel)
	// // console.log(vimeo.reelVideos)
	// // await vimeo.fetchMe()
	// // console.log(vimeo.me)
	
})
</script>
<style scoped>
.vidChip{
	width:180px;
}
.titleTxt{
	font-size:.75rem !important;
	 white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
  	padding:4px;
}
.v-col.noVis{
	display:none !important;
}
div.filterThrough{
	display:contents;
}

</style>
