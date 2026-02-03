<template>
	<v-card 
		
		style="min-height:150px;
		width=100%;" 
		class="pa-0" 
		elevation="0"
	>
	<v-container>
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
					/>
					<v-data-table 
						:items="reels" 
						:headers="reelHeaders"
						item-value="title"
						class="imgTable"
						density="compact"
						@click:row="onRowClick"
						:search="search"
					> 
						<template v-slot:item.notes="{ item }">
							<v-img :src="item.img"
								style="display:block;border-radius:4px;"
								cover
							/>
						</template>
					</v-data-table>
				</div>
			</v-col>
		</v-row>
		
		
	</v-container>		
	</v-card>
</template>
<script setup>
import { ref, computed, onMounted } from 'vue'
import { useVimeoStore } from '@/stores/VimeoStore.js'
import ReelMod from '@/components/ReelModule.vue'
const vimeo = useVimeoStore()
const loadingText = ''
const pointdown = ref(true)
const search = ref('')
const reelVids = computed(() =>{
	if (!vimeo.videos.length){
		return []
	} else {
		var reelList = [
			1159257417,
			1158526288,
			198256341,
			118870787,
			121778131,
			198253825
		]
		var reelListStrings = reelList.map(String)
		const reelArr = vimeo.videos.filter(vid => reelListStrings.includes(vid.uri.replace('/videos/','')))
		return reelArr
	}
	
	
})
 function loadReel(){
	var reelList = [
			1159257417,
			1158526288,
			198256341,
			118870787,
			121778131,
			198253825
		]
 }
 function onRowClick(event, row) {
	console.log(row.item)

}
 
onMounted(async() =>{
	await vimeo.fetchReels()
	console.log (vimeo.reelList)
})
const reelHeaders = [
	{ width: 200, title: 'Name', key: 'name', align: 'start', sortable: true },
    { width: 200, title: 'Date', key: 'date', sortable: true  },
    { width: 140, title: 'Notes', key: 'notes'  },
    { width: 100, title: 'Author', key: 'author', sortable: true  },
    
]
const reels = [
    {
      name: 'Jeff Davis for Toyota',
      date: '2024-10-27',
      img: 'https://i.vimeocdn.com/video/2113577651-519c28270f0fe169581840f105c2f99ca516ed0a92e1980ba46bc59f9f550331-d_295x166?&r=pad&region=us',
      author: 'Jeff McDougal',
    },
	{
      name: 'Milk Shoots',
      date: '2024-04-12',
      img: 'https://i.vimeocdn.com/video/2113577651-519c28270f0fe169581840f105c2f99ca516ed0a92e1980ba46bc59f9f550331-d_295x166?&r=pad&region=us',
      author: 'Danny Rosenbloom',
    },
	
    // ... more items
  ]
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
		border:1px solid #ccc;
		min-height:120px
	}
.vidGrid.wider{
	flex-wrap: wrap;
}


.thumb {
  width: 100%;
  display: block;
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
</style>