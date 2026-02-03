<template>
	<v-container >
		<v-row>
			<v-col cols="11">

				<div>
					<v-text-field
						v-model="search"
						label="Search"
						prepend-inner-icon="mdi-magnify"
						
						hide-details
						density="compact"
						single-line
					></v-text-field>
					<!-- show-expand -->
					<v-data-table 
						:items="videoList" 
						:headers="videoHeaders"
						item-value="title"
						class="imgTable"
						density="compact"
						 :row-props="rowProps"
						 @click:row="onRowClick"
						 :search="search"
						 :items-per-page="button == 1? 5: 10"
						  :filter-keys="['name','director','agency']"
						 > 
						  <template v-slot:item.add="{ item }">
							<v-btn icon rounded="circle" size="x-small" :color="item.inReel ? 'red': 'green'">
								<v-icon :icon="item.inReel ? 'mdi-minus': 'mdi-plus'" @click="addToReel(item)"/>
							</v-btn>
							
						 </template>
						 <template v-slot:item.pictures="{ item }">
							<img height="50" :src="item.pictures.sizes[2].link" style="border-radius:4px;margin-top:4px;"></img>
							
						 </template>
						 
						</v-data-table>
				</div>
			</v-col>
		</v-row>
	</v-container>

</template>
<script setup>
import {ref, computed, onMounted} from 'vue'
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
const search = ref('')
const props = defineProps({
        button:Number
    })
const added = ref(false)
function addToReel(item){
	item.inReel = !item.inReel
	console.log(item)
}
function rowProps({ item }) {
	var obj = {}
	var director, client
	var dTag = item.tags.filter(item => item.tag.includes('d:'))
	if (dTag.length){
		// director = dTag[0].name.replace('d:','')
		obj.director = dTag[0].name.replace('d:','')
	}
	var cTag = item.tags.filter(item => item.tag.includes('c:'))
	if (cTag.length){
		// client = cTag[0].name.replace('c:','')
		obj.client = cTag[0].name.replace('c:','')
	}
	return obj
//   return {
    
//     'data-director': director,
// 	'data-client': client
//   }
}
const items = computed(() =>{
	const dirArr = directorSearch.value.map(v =>
		`d:${v.trim().toLowerCase().replace(/\s+/g, '_')}`
	)
	const taggedVids = vimeo.videos.filter(video => video.tags && video.tags.length > 0)
	const tagArr = taggedVids.filter(video =>
			video.tags.some(tag => dirArr.includes(tag.name.toLowerCase()))
		)	
	let myArr = vimeo.videos
	return myArr
})
const tagFormat = (item,t) => {
	var tagList =  item.tags.map(item => item.tag)
	var tagArr = []
	var tagObj = {}
	var ret = ''
	tagList.forEach((tag) =>{
		if(t == 'd'){
			if (tag.includes("d:")){
				ret = decodeURIComponent(tag.replace('d:',''))
			}
		} else if (t == 'c'){
			if (tag.includes("c:")){
				ret = decodeURIComponent(tag.replace('c:',''))
			}
		}
		
		
	})
	// return tagObj
	return  ret
}
const videoList = computed(() =>{
	if (!vimeo.videos.length){
		return []
	} else {
		// return vimeo.videos.slice(0,5)
		return vimeo.videos
	}
})
const videoHeaders = ref([])
const showAdd = ref(null)
if (props.button == 1) {
	videoHeaders.value = [
		{ width: 10, title: 'Add', key: 'add', align: 'start', sortable: true },
		{ width: 100, title: 'Thumbnail', key: 'pictures', align: 'start', sortable: true },
		{ width: 100, title: 'Name', key: 'name', align: 'start', sortable: true },
		
		{ width: 100, title: 'Director', key: 'director', align: 'start', sortable: true },
		{ width: 100, title: 'Agency', key: 'agency', align: 'start', sortable: true },
		
		// {name}
		// {pictures}
		// {tags}
		// {uri}
	]
} else {
	videoHeaders.value = [
		
		{ width: 100, title: 'Thumbnail', key: 'pictures', align: 'start', sortable: true },
		{ width: 100, title: 'Name', key: 'name', align: 'start', sortable: true },
		
		{ width: 100, title: 'Director', key: 'director', align: 'start', sortable: true },
		{ width: 100, title: 'Agency', key: 'agency', align: 'start', sortable: true },
		
		// {name}
		// {pictures}
		// {tags}
		// {uri}
	]
}
// const videoHeaders = ref([
	
// 	{ width: 100, title: 'Thumbnail', key: 'pictures', align: 'start', sortable: true },
// 	{ width: 100, title: 'Name', key: 'name', align: 'start', sortable: true },
	
// 	{ width: 100, title: 'Director', key: 'director', align: 'start', sortable: true },
// 	{ width: 100, title: 'Agency', key: 'agency', align: 'start', sortable: true },
// 	// {name}
// 	// {pictures}
// 	// {tags}
// 	// {uri}
// ])
onMounted(async() =>{
	
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
	:deep(.v-data-table .v-table__wrapper tbody tr:hover) {
		background-color: rgba(0, 0, 0, 0.04);
		cursor: pointer;
	}
		:deep(.v-data-table.imgTable .v-table__wrapper tbody tr) {
		height: auto !important;
	}

	:deep(.v-data-table.imgTable .v-table__wrapper tbody td) {
		height: auto !important;
		max-height: none !important;
		overflow: visible !important;
	}
	.v-data-table{
		font-size:.75rem;
	}
</style>