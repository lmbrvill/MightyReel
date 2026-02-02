

<template>
	<v-app>
		<v-main>
			

    	<v-navigation-drawer
			class="navBarAlt"
			permanent
			location="left"
			width="140"
			color="blue"
		>
			<div style="position:fixed;bottom:0;width:100%">
				<img :src="menuImg" width="150px" style="position:relative;left:-5px;top:17px;">
				</img>
			</div>
		</v-navigation-drawer>
			<v-container>
				<div class="position-sticky" style="top:8px">
				<!-- <v-row >
			
					<v-col cols="12">
						<h2>Reel Builder</h2>
					</v-col>
				</v-row> -->
				<v-row>
				
					<v-col cols="7">
						<v-card elevation="4" class="pa-0" >
							<Player  />
							
						</v-card>
					</v-col>
						<v-col cols="5">
							<v-card elevation="2" class=" pa-3 pt-5 pb-5" style="height:100%;">
								<v-container class="pa-0 ma-0" >
									<v-row  class="pa-0 ma-0">
										<v-col cols="12" class="pa-0 ma-0 mb-2">
											<v-text-field density="compact" label="Reel Name" hide-details />
										</v-col>
									</v-row>
									<v-row class="pa-0 ma-0">
										<v-col cols="6" class="pa-0 ma-0 mb-2 pr-1">
											<v-select density="compact" label="Agency" hide-details />
										</v-col>
										<v-col cols="6" class="pa-0 ma-0 mb-2 pl-1">
											<v-select density="compact" label="Director" hide-details />
										</v-col>
									</v-row>
									<v-row class="pa-0 ma-0">
										<v-col cols="6" class="pa-0 ma-0 mb-2 pr-1">
											<v-select density="compact" label="Client/Brand" hide-details />
										</v-col>
										<v-col cols="6" class="pa-0 ma-0 mb-2 pl-1">
											<v-select density="compact" label="Job" hide-details />
										</v-col>
									</v-row>
									<v-row class="pa-0 ma-0 pb-3">
										<v-col cols="6" class="pa-0 ma-0 mb-2 pr-1">
											<v-select density="compact" label="Sales Rep" hide-details />
										</v-col>
										<v-col cols="6" class="pa-0 ma-0 mb-2 pl-1">
											<v-select density="compact" label="Region" hide-details />
										</v-col>
									</v-row>
									<v-row class="pa-0 ma-0">
										<v-col cols="3" class="pa-0 ma-0"></v-col>
										<v-col cols="6" class="pa-0 ma-0">
											<v-btn  class=" position-relative w-100 bottom-0" @click="changeReel('2')">Save</v-btn>
										</v-col>
									</v-row>
								</v-container>
							</v-card>
						</v-col>
					</v-row>
					
					<v-row>
						<v-col cols="12">
							<ReelMod />
						</v-col>
					</v-row>
					</div>
			
					
					<v-row>
					<v-col cols="12" >
						<v-expansion-panels style="min-width:calc(4 * 200px);background-color:#fff" v-model="Panels" variant="accordion">
							
							<v-expansion-panel title="Reel Library" >
								<v-expansion-panel-text>
									<LibraryReels />
								</v-expansion-panel-text>
							</v-expansion-panel>
							<!-- <v-expansion-panel title="Folders">
								<v-expansion-panel-text>
									<LibraryFolders />
								</v-expansion-panel-text>
							</v-expansion-panel> -->
							<v-expansion-panel title="Video Library" >
								<v-expansion-panel-text>
									<Library :button='1' />
								</v-expansion-panel-text>
							</v-expansion-panel>
						</v-expansion-panels>
					</v-col>
				</v-row>
			</v-container>
		</v-main>
	</v-app>
</template>
<script setup> 
import {ref, onMounted, onBeforeMount, computed} from 'vue'
import { useVimeoStore } from '@/stores/VimeoStore.js'
const vimeo = useVimeoStore()
const showPlayer = ref(true)
const Panels = ref(0)
 const menuImg = ref("../../images/ReelLong.png")
//COMPONENTS
	import Player from '@/_VimeoPlayer/vim2.vue'
	import Library from '@/components/LibraryBoxAlt.vue'
	import LibraryFolders from '@/components/FolderBox.vue'
	import LibraryReels from '@/components/ReelBox.vue'
	import ReelMod from '@/components/ReelModule.vue'
onMounted(async() =>{
	await vimeo.fetchVideos()
	await vimeo.fetchReels()
	
	
})
function changeReel(reelID){
	vimeo.setCurrentReel(reelID)
}
</script>
<style scoped>

</style>
