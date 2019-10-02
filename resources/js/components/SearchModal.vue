<template>
   <div class="z-10">
      <transition name="search" >
            <div v-if="searchModalIsOpen" class="modal bg-white   full min-h-screen min-w-screen  md:flex md:flex-wrap ">
                <div class="w-full h-full relative pt-12 container mx-auto">
                       <div class="search_input mt-3 w-full px-5">
                           <input type="text" role="search" v-model="search" v-focus
                           class="w-full  py-8 title l4 focus:outline-none border-b border-gray-100"
                           placeholder="Search something..."
                           >
                       </div>

                        <transition name="search" mode="out-in">

                           <div v-if="results && results.length" key="results" class="results_box px-5 mt-6">
                                 <div class="title l3 text-gray-600"><span class="text-blue-primary">{{results.length}}</span> demandes trouvées</div>
                                 <div class="results">
                                       <CardListDemand v-for="demand in results" v-bind:key="demand.id" :demand="demand"></CardListDemand>
                                 </div>
                           </div>
            
                           <div v-else-if="isLoading" key="loading" class="">
                              <Loader/>
                           </div>
                           
                         </transition>
                         

                     </div>
                       
               
            </div>
      </transition>
   </div>
</template>


<script>
import CardListDemand from "@/components/demands/CardListDemand.vue"
import Loader from "./LoaderAnim.vue"

export default {
   components: {CardListDemand, Loader},
   data() {
      return {
         searchModalIsOpen: false,
         search: '',
         timer :'',
         results : [],
         isLoading : false

      }
   },
   directives: {
      focus: {
         inserted: function (el) {
            el.focus()
         }
      }
   },
   created() {
         
   },
   watch: {
      search: function(value) {
        clearTimeout(this.timer);
        let vm = this
        if(value.trim().length > 3) {

            vm.timer = setTimeout(function(){
               vm.searchQuery(value)  
            }, 1000)
        }else{
           vm.results = []
        }
      }
   },
   methods: {

      openSearchModal() {
         this.searchModalIsOpen = ! this.searchModalIsOpen
      },
      searchQuery(query) {
         let vm = this
         this.isLoading = true
         axios.get('/search?search='+query)
            .then(function(response) {
               console.log(response.data)
               vm.results = response.data
               vm.isLoading = false
            })
      }

     
   }
}
</script>

<style scoped>
.search-enter-active, .search-leave-active {
   transition: opacity .3s, transform .3s;
}
.search-enter, .search-leave-to /* .fade-leave-active below version 2.1.8 */ {
   opacity: 0;
   transform: translateY(30px)
}
</style>