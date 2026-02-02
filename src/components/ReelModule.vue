<template>
	<v-card style="border:1px solid #ccc;background-color:#ddd">
	<v-row align="center">
		<v-col cols="11">
			<div class="vidGrid" v-if="!reelVids.length">{{ loadingText }}</div>
			<v-card :class="['vidGrid',{'wider':!pointdown}]" v-else elevation="0" style="border-radius:0">
				
					<v-card	
						class="mb-4 pa-0 vidChipSm" 
						hide-details 
						v-for="(video, index) in reelVids"
					>
						<img :src="video.pictures.sizes[2].link" class="thumb">
						<v-card-text class="pa-2 pt-0 titleTxt"> 
							{{ video.name }}
						</v-card-text>
					</v-card>	
				
				</v-card>	
				
		</v-col>
		<v-col cols="1" >
			<div data-direction="down" @click="pointdown=!pointdown" >
				<v-btn icon rounded="circle" size="x-small" >
					<v-icon :icon="pointdown? 'mdi-chevron-down': 'mdi-chevron-up'" />
				</v-btn>
			</div>
		</v-col>
		
	</v-row>
	</v-card>
</template>
<script setup>
import { ref, computed, onMounted} from 'vue'
import { useVimeoStore } from '@/stores/VimeoStore.js'
import Player from '@/_VimeoPlayer/vim2.vue'
const vimeo = useVimeoStore()
const loadingText = ''
const pointdown = ref(true)
vimeo.fetchReels()
onMounted(() =>{
	console.log(vimeo.reelList)
})

const reelVids = computed(() =>{
	// var reelList = null
	console.log (vimeo.currentReelID)
	if (!vimeo.currentReelID){
		return []
	} else {
		var reelList = vimeo.reelList.find((reel) => reel.id == vimeo.currentReelID).videos
		var reelListStrings = reelList.map(String)
		const reelArr = vimeo.videos.filter(vid => reelListStrings.includes(vid.uri.replace('/videos/','')))
		return reelArr
	}
	
	
})
</script>
<style scoped>
.vidChip{
		width:180px;
		flex: 0 0 auto;
	}
	.vidChipSm{
		width:120px;
		flex: 0 0 auto;
	}
	.titleTxt{
		font-size:.75rem !important;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		padding:4px;
	}
	.vidGrid {
		display: flex;
		
		flex-wrap: no-wrap;
		overflow-x:scroll;
		gap: 16px;
		background-color:#ddd;
		padding:16px 8px 4px 16px;
		border-radius:4px;
		/* border:1px solid #ccc; */
		min-height:120px
	}
.vidGrid.wider{
	flex-wrap: wrap;
}


.thumb {
  width: 100%;
  display: block;
}
</style>