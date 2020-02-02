<template>
  <div id="listDemand" class="panel flex flex-col md:flex-row md:flex-wrap lg:min-h-screen border-t border-gray-200">
    <div class="menu-demands order-1 w-full md:w-5/5 lg:w-1/5 lg:h-screen-full pb-6">
      <!-- @include('demands.components.filters')  -->
      <FiltersDemands :sectors="sectors" :communes="communes" :query="query"></FiltersDemands>
    </div>

    <div class="demands order-3 md:order-2 w-full lg:w-3/5 lg:border-l lg:border-gray-200 relative">
 
      <transition name="simple-fade">
         <div v-show="isLoading" class="loader absolute top-0 left-0 bottom-0 right-0 flex pt-16 justify-center">
               <div class="sk-folding-cube">
                  <div class="sk-cube1 sk-cube"></div>
                  <div class="sk-cube2 sk-cube"></div>
                  <div class="sk-cube4 sk-cube"></div>
                  <div class="sk-cube3 sk-cube"></div>
               </div>
         </div>
      </transition>
      <div v-if="demands">
         <Card v-for="demand in demands.data" v-bind:key="demand.id" :demand="demand"></Card>
          <div v-if="demands.data.length">
            <PaginatationCard ref="paginationComponent" :demands="demands" :query="query"></PaginatationCard>
          </div>
          <div v-else>
            <div class="flex items-center justify-center w-full p-12 title l4 text-blue-primary">Aucune demandes ne corespond à ces critéres.</div>
          </div>  
      </div> 
       
    </div>

    <div class="other-demand order-2 md:order-3 w-full md:w-2/5 lg:w-1/5 lg:border-l lg:border-gray-200">
      <div class="bg-blue-primary p-5 pb-8 hidden lg:block">
        <div class="title l5 white center">Besoin d'un service ?</div>
        <div class="w-full text-center mt-4">
          <a href="/demands/create" class="btn btn-inverse small inline-block">Créer votre demande</a>
        </div>
      </div>
      <div class="p-5 pb-8 hidden border-b border-gray-200 lg:block">
        <div class="title l5 blue separator s-sm">34 demandes en urgences</div>
         <div class="text-blue-primary">
               Placez vous aussi votre demande à la une en souscrivant à une offre Margoze
            </div>
        <div class="w-full mt-4">
          
          <a href="#" class="btn small inline-block">Voir les offres</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Card from "./CardListDemand.vue"
import PaginatationCard from "./PaginatationCard.vue"
import FiltersDemands from "./FiltersDemands.vue"

export default {
   components: {Card, PaginatationCard, FiltersDemands},
   props: ['sectors', 'communes'],
   data() {
      return {
         isLoading: false,
         demands: {
            data: []
         },
         query: {}
      }
   },
  mounted() {
     this.getQueryParam()
     this.demands = this.loadDemands(window.location.href)
  },
  methods: {
      
     loadDemands: function(url) {
      

        this.isLoading = true
        let vm = this
        //this.demands.data = []
        axios.get(url)
         .then(function(response){
            vm.demands = response.data.demands
            vm.isLoading = false
         })
     },

     setNewQuery: function(key, value) {
          let query = this.addQuery(key, value)
          if(key == "" && value == ""){
              this.loadDemands('/demands')
              this.changeUrl('/demands')
          }else{
             this.loadDemands('/demands?'+query)
             this.changeUrl('/demands?'+query)
          }
         
     },

     getQueryParam(){
        let uri = window.location.href.split('?');
        if (uri.length == 2)
        {
          let vars = uri[1].split('&');
          let getVars = {};
          let tmp = '';
          vars.forEach(function(v){
            tmp = v.split('=');
            if(tmp.length == 2)
            getVars[tmp[0]] = tmp[1];
          });
          this.query = getVars
          return this.query
        }
     },
     changeUrl: function(url) {
         if (typeof (history.pushState) != "undefined") {
            var obj = { Title: "Margoze app change url", Url: url };
            history.pushState(obj, obj.Title, obj.Url);
         } else {
            alert("Browser does not support HTML5.");
         }
      },
      addQuery: function(queryKey, queryValue) {
         var query = ""
        //  this.$set(this.query, queryKey, queryValue)
         this.query[queryKey] = queryValue
         for (var key in this.query) {
            query += key+"="+this.query[key]+"&"
         }
         query = query.substring(0, query.length - 1)
         return query
      },
      removeQuery: function( key) {
         delete this.query[key]
      },

      setActivePage: function(pageId) {
        if(this.demands.data.length){
          this.$refs.paginationComponent.pages.forEach(function(page){
              page.active = false
          })
        
          this.$refs.paginationComponent.pages.find(page => page.id===pageId).active = true
        }
         
      }
  }
};
</script>

<style scoped>
.loader{
   background-color: rgba(255,255,255,.7)
}

.simple-fade-enter-active, .simple-fade-leave-active {
  transition: opacity .5s;
}
.simple-fade-enter, .simple-fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}

.sk-folding-cube {
  margin: 0 auto;
  width: 30px;
  height: 30px;
  position: relative;
  top:15px;
  -webkit-transform: rotateZ(45deg);
          transform: rotateZ(45deg);
}

.sk-folding-cube .sk-cube {
  float: left;
  width: 50%;
  height: 50%;
  position: relative;
  -webkit-transform: scale(1.1);
      -ms-transform: scale(1.1);
          transform: scale(1.1); 
}
.sk-folding-cube .sk-cube:before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  @apply bg-blue-primary;
  -webkit-animation: sk-foldCubeAngle 2.4s infinite linear both;
          animation: sk-foldCubeAngle 2.4s infinite linear both;
  -webkit-transform-origin: 100% 100%;
      -ms-transform-origin: 100% 100%;
          transform-origin: 100% 100%;
}
.sk-folding-cube .sk-cube2 {
  -webkit-transform: scale(1.1) rotateZ(90deg);
          transform: scale(1.1) rotateZ(90deg);
}
.sk-folding-cube .sk-cube3 {
  -webkit-transform: scale(1.1) rotateZ(180deg);
          transform: scale(1.1) rotateZ(180deg);
}
.sk-folding-cube .sk-cube4 {
  -webkit-transform: scale(1.1) rotateZ(270deg);
          transform: scale(1.1) rotateZ(270deg);
}
.sk-folding-cube .sk-cube2:before {
  -webkit-animation-delay: 0.3s;
          animation-delay: 0.3s;
}
.sk-folding-cube .sk-cube3:before {
  -webkit-animation-delay: 0.6s;
          animation-delay: 0.6s; 
}
.sk-folding-cube .sk-cube4:before {
  -webkit-animation-delay: 0.9s;
          animation-delay: 0.9s;
}
@-webkit-keyframes sk-foldCubeAngle {
  0%, 10% {
    -webkit-transform: perspective(140px) rotateX(-180deg);
            transform: perspective(140px) rotateX(-180deg);
    opacity: 0; 
  } 25%, 75% {
    -webkit-transform: perspective(140px) rotateX(0deg);
            transform: perspective(140px) rotateX(0deg);
    opacity: 1; 
  } 90%, 100% {
    -webkit-transform: perspective(140px) rotateY(180deg);
            transform: perspective(140px) rotateY(180deg);
    opacity: 0; 
  } 
}

@keyframes sk-foldCubeAngle {
  0%, 10% {
    -webkit-transform: perspective(140px) rotateX(-180deg);
            transform: perspective(140px) rotateX(-180deg);
    opacity: 0; 
  } 25%, 75% {
    -webkit-transform: perspective(140px) rotateX(0deg);
            transform: perspective(140px) rotateX(0deg);
    opacity: 1; 
  } 90%, 100% {
    -webkit-transform: perspective(140px) rotateY(180deg);
            transform: perspective(140px) rotateY(180deg);
    opacity: 0; 
  }
}
</style>

