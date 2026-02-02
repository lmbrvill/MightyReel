<template>
	<v-expansion-panel title="Videos" >
		<v-expansion-panel-text>
			<v-container >
				<v-row>
					<v-col cols="6">
						<v-text-field 
							label="Search Titles"
							v-model="filterTitle"
							prepend-inner-icon="mdi-magnify"
							hide-details
							density="compact"
						/>
					</v-col>
					<v-col cols="6">
						<v-text-field 
							label="Search Tags"
							v-model="filterCustomTag"
							prepend-inner-icon="mdi-magnify"
							hide-details
							density="compact"
						/>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="4">
						<v-select
							ref="directorSelect"
							chips
							label="By Director"
							v-model="directorSearch"
							:items="['Lance Accord', 'Tomat']"
							multiple
							hide-details
							density="compact"
							clearable
							@update:modelValue="directorSelect?.blur()"
						>
							<template #chip="{ item, props }">
								<v-chip v-bind="props" label size="small" class="ma-1">
									{{ item.title }}
								</v-chip>
							</template>
						</v-select>
					</v-col>
					<v-col cols="4">
						<v-select
							ref="clientSelect"
							chips
							label="By Client"
							v-model="clientSearch"
							:items="['Toyota', 'Coke']"
							multiple
							hide-details
							density="compact"
							@update:modelValue="clientSelect?.blur()"
						>
							<template #chip="{ item, props }">
								<v-chip v-bind="props" label size="small" class="ma-1">
									{{ item.title }}
								</v-chip>
							</template>
						</v-select>
					</v-col>
					<v-col cols="4">
						<v-select
							ref="agencySelect"
							chips
							label="By Agency"
							v-model="agencySearch"
							:items="['W+K', 'Saatchi']"
							multiple
							hide-details
							density="compact"
							@update:modelValue="agencySelect?.blur()"
						>
							<template #chip="{ item, props }">
								<v-chip v-bind="props" label size="small" class="ma-1">
									{{ item.title }}
								</v-chip>
							</template>
						</v-select>
					</v-col>
				</v-row>
				<v-row>
					<div class="filterThrough"
						v-for="(video, index) in filteredVideos"
						:key="index"
					>
						<v-col cols="3">
							<v-card	class="mb-4 pa-0 vidChip" hide-details>
								<img :src="video.pictures.sizes[2].link" width="100%" >
								<v-card-text class="pa-2 pt-0 titleTxt"> 
									{{ video.name }}
								</v-card-text>
							</v-card>
						</v-col>
					</div>
				</v-row>
			</v-container>
		</v-expansion-panel-text>
	</v-expansion-panel>
</template>
<script setup>
import {ref, computed} from 'vue'
import { useVimeoStore } from '@/stores/VimeoStore.js'
const vimeo = useVimeoStore()
const filterTitle = ref('')
const filterCustomTag = ref('')
const directorSearch = ref([])
const directorSelect = ref(null)
const clientSearch = ref([])
const clientSelect = ref(null)
const agencySearch = ref([])
const agencySelect = ref(null)

const filteredVideos = computed(() =>{
	const dirArr = directorSearch.value.map(v =>
		`d:${v.trim().toLowerCase().replace(/\s+/g, '_')}`
	)
	const taggedVids = vimeo.videos.filter(video => video.tags && video.tags.length > 0)
	const tagArr = taggedVids.filter(video =>
			video.tags.some(tag => dirArr.includes(tag.name.toLowerCase()))
		)	
	let myArr = vimeo.videos
	// let taggedArr = []
	// if (filterTitle.value.length > 1 && dirArr.length == 0){
	// 	var search = filterTitle.value.trim().toLowerCase()
	// 	myArr = vimeo.videos.filter(video => video.name.toLowerCase().includes(search))
	// } else if (directorSearch.value.length > 0 && filterTitle.value.length == 0){
	// 	taggedArr = vimeo.videos.filter(video => video.tags && video.tags.length > 0)
	// 	myArr = taggedArr.filter(video =>
	// 		video.tags.some(tag => dirArr.includes(tag.name.toLowerCase()))
	// 	)	
	// }
	return myArr
})
// const filteredVideos = computed(() =>{
// 	const dirArr = directorSearch.value.map(v =>
// 		`d:${v.trim().toLowerCase().replace(/\s+/g, '_')}`
// 	)
// 	let myArr = vimeo.videos
// 	let taggedArr = []
// 	if (filterTitle.value.length > 1 && dirArr.length == 0){
// 		var search = filterTitle.value.trim().toLowerCase()
// 		myArr = vimeo.videos.filter(video => video.name.toLowerCase().includes(search))
// 	} else if (directorSearch.value.length > 0 && filterTitle.value.length == 0){
// 		taggedArr = vimeo.videos.filter(video => video.tags && video.tags.length > 0)
// 		myArr = taggedArr.filter(video =>
// 			video.tags.some(tag => dirArr.includes(tag.name.toLowerCase()))
// 		)	
// 	}
// 	return myArr
// })
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