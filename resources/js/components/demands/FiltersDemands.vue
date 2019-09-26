<template>
   <div>
      <div class="selected w-full p-5 bg-gray-300">
         <div class="flex mb-3">
               <div class="title text-gray-800 l5 ">Filtres</div>
               <a href="#" 
               v-on:click.prevent="resetFilter()" 
               v-show="filters.sectors != '' || filters.communes != '' "
               class="btn small btn-danger ml-auto">Reset</a>
         </div>
         <ul class="flex flex-wrap -mx-1 ">
            <li v-if="filters.sectors" class="my-1 inline-block mx-1"><a href="#" 
            class="rounded-full  text-white bg-blue-darken  text-sm  py-1 px-2 ">
            {{filters.sectors.name}}</a></li> 
            <li v-if="filters.communes" class="my-1 inline-block mx-1"><a href="#" 
            class="rounded-full  text-white bg-blue-darken  text-sm  py-1 px-2 ">
            {{filters.communes.name}}</a></li> 
         </ul> 
      </div>

       <div class="flex p-5 pb-0 -mx-2 lg:hidden">
         <div class="text-gray-600 px-2 font-title text-xl" v-bind:class="{'text-blue-primary' : selectedTab=='sectors'}">
            <a v-on:click.prevent="selectedTab='sectors'" href="#">Sectors</a>
         </div>
         <div class="text-gray-600 px-2 font-title text-xl" v-bind:class="{'text-blue-primary' : selectedTab=='communes'}">
            <a v-on:click.prevent="selectedTab='communes'" href="#">Communes</a>
            </div>
      </div>

      
      <div v-show="isTab('sectors')" class="overflow-x-auto py-3">
         <div class="title blue l5 separator s-sm hidden lg:block lg:px-5 lg:pb-0">Secteurs</div>
         <ul class="flex  lg:flex lg:flex-wrap  lg:px-5 ">

            <li v-for="sector in sectors" v-bind:key="sector.id" 
            class="mx-1 my-1 flex-none lg:flex lg:m-0 ">
               <a href="#" class="rounded-full lg:rounded text-gray-600 bg-gray-100 hover:text-blue-primary lg:bg-transparent lg:border-gray-600
                  border border-gray-300 py-2 px-3 lg:mr-2 lg:mb-2 lg:px-1 lg:py-0 lg:inline-block  "
                  v-bind:class="{'lg:border-blue-primary text-blue-primary': sector.id === filters.sectors.id}"
                  v-on:click.prevent="selectSector(sector)">
               {{sector.name}}</a>
            </li>
         </ul>
      </div>

      <div v-show="isTab('communes')" class=" overflow-x-auto py-3  ">
         <div class="title blue l5 separator s-sm hidden lg:block lg:px-5 lg:pb-0">Communes</div>
         <ul class="flex  lg:flex lg:flex-wrap  lg:px-5">
            <li v-for="commune in filteredCommunes" v-bind:key="commune.id" 
            class="mx-1 my-1 flex-none lg:m-0">
               <a href="#" 
               v-on:click.prevent="selectCommune(commune)"
               class="rounded-full  text-gray-600 bg-gray-100 hover:text-blue-primary lg:bg-transparent lg:border-0 border border-gray-300 py-2 px-3 lg:pr-2 lg:pl-0 lg:py-1 lg:inline-block  "
                v-bind:class="{'text-blue-primary': commune.id === filters.communes.id}">
               
               {{commune.name}}</a></li>
         </ul>
      </div>

       

   </div>
</template>

<script>
   
   export default {
      props: ['sectors', 'communes', 'query'],
      data() {
         return {
            filters: {
               sectors: '',
               communes: '',
            },
            selectedTab: 'sectors',
            windowSize: 'sectors',

         }
      },

      computed: {
         filteredCommunes: function() {
            if(this.filters.sectors){
               
               return this.communes.filter(commune => commune.sector_id === this.filters.sectors.id)
            }else{
               return this.communes
            }
         },
         
      },
      mounted() {
          this.windowSize = window.innerWidth
          window.addEventListener('resize', this.handleResize)
         this.setDefaultFilter()
      },
      methods: {
         handleResize: function() {
            this.windowSize = window.innerWidth
         },
         selectSector: function(sector) {
            this.filters.sectors = sector
            this.filters.communes = ''
            this.$parent.removeQuery('commune')
            this.$parent.removeQuery('page')
            this.$parent.setNewQuery('sector', sector.id)
            this.$parent.setActivePage(1)
         },
         selectCommune: function(commune) {
            this.filters.communes = commune
            this.filters.sectors = this.sectors.find(sector => sector.id === commune.sector_id )
            this.$parent.removeQuery('page')
            this.$parent.addQuery('sector', commune.sector_id)
            this.$parent.setNewQuery('commune', commune.id)
            this.$parent.setActivePage(1)
         },
         resetFilter: function() {
            
            this.$parent.removeQuery('commune')
            this.$parent.removeQuery('page')
            this.$parent.removeQuery('sector')
            this.$parent.setActivePage(1)  
            this.$parent.setNewQuery('','')
            this.filters.sectors = ""  
            this.filters.communes = ""  
         },

         isTab: function(tab){
            if(this.windowSize >= 1024){
               return true
            }
            if(this.selectedTab == tab){
               return true
            }else{
               return false
            }
         },
         setDefaultFilter: function () {
             let query = this.$parent.getQueryParam() 
             if(typeof query != "object"){
                query = {}
             }
             console.log(query)
            if(query.sector){
               this.filters.sectors = this.sectors.find(sector => sector.id == query.sector)
            }
            if(query.commune){
               this.filters.communes = this.communes.find(commune => commune.id == query.commune)
            }
         }
      }
   }

</script>