<template>
   <div class="flex p-5 lg:p-8 items-center justify-center">

         <a v-for="page in pages" v-bind:key="page.id"
               v-on:click.prevent="loadPage(page.id)"
               href="#"
               v-bind:class="{'active' : page.active}"
               class="paginateItem p-2 ">
               {{page.id}}
         </a>

   </div>
</template>

<script>
export default {
   props: ['demands','query'],
   data() {
      return {
         pages: [],
      }
   },
   mounted() {
      this.createPages()
   },
   computed: {
      totalPages: function() {
         return this.demands.last_page
      },
      totalDemand: function(){
         return this.demands.data.length
      }
   },
   watch: {
      totalDemand: function(newValue) {
         this.pages = []
         this.createPages()
      }
   },
   methods: {
      createPages: function(){
         let i
         for (i = 0; i < this.totalPages; i++) { 
            let active = this.isActive(i+1)
            this.pages.push({
               id: i + 1,
               query: "page="+ (i + 1),
               active: active
            })
         }  
      },
      isActive: function(page) {
         if(!this.query.page && page == 1){
            return true
         }
         if(this.query.page == page){
            return true
         }
         return false
      }, 

      loadPage: function(page) {
            this.$parent.setNewQuery('page', page)
            this.$parent.setActivePage(page)
            //var myDiv = document.getElementById('listDemand').scrollTop;
            //scroll to page

      },
     
   }

}   
</script>

<style scoped>
   .active{
      @apply bg-red-100
   }
</style>