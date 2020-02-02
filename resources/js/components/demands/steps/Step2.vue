<template lang="">
   <div class="flex flex-col h-full md:overflow-y-auto  px-4">

   <div class="categ">
      <div>
         <h3 class="title l3 white w-full ">Quel est le type de votre demande ?</h3>
      </div>
      <div class="mt-6 ">
         <div class="text-gray-600 italic mb-2">Soyez le plus précis possible. Le titre de la demande est la premiére information que les candidats verront.</div>
         <div class="flex flex-wrap space-between -mx-3">
             <div class="w-full xl:w-1/2 px-3 relative">
           
              
            <!-- <FormSelect  placeholder="Ex: Informatique" name="category_id"
               selectClass="w-full md:w-1/2 text-white border-2 border-blue-800 bg-blue-darken rounded p-6 focus:outline-none focus:border-blue-600"
               :data="categories"
               :name="'category_id'"
               /></FormSelect> -->
                <vSelect 
                label="name" 
                :options="categories" 
                @input="setSelected"
                :value="categorySelected"
                placeholder="Choisir une categorie"
                class="create_demand_form focus:outline-none focus:border-blue-600">
                <div slot="no-options">Aucunes catégories trouvées</div></vSelect>
               <div v-if="!$parent.getField('category_id').validated" class="absolute italic bottom-0 -mb-6  ml-3 text-sm left-0 text-blue-500">{{$parent.errorMessage('category_id')}}</div>

            </div>

            <!-- <div class="w-1/2 mx-3 ">
              <FormSelect  placeholder="Ex: Informatique" name="category_id"
               selectClass="w-full text-white border-2 border-blue-800 bg-blue-darken rounded p-6 focus:outline-none focus:border-blue-600"
               :data="categories"/></FormSelect>
            </div> -->
         </div>
      </div>
   </div>

   <div class="px-1 py-1  border-b border-blue-darken my-12 block" style="height:1px; width: 60px;"></div>

   <div class="description_short">
      <div>
         <h3 class="title l3 white w-full ">Expliquez votre demande simplement</h3>
      </div>
      <div class="mt-6">
            <div class="text-gray-600 italic mb-2">Soyez le plus précis possible. Le titre de la demande est la premiére information que les candidats verront.</div>
            <div class="flex space-between -mx-3">
               <div class="w-full mx-3 relative">
                  <textarea type="select" placeholder="Ex: Informatique" v-model="descriptionValue"
                  class="w-full  text-white border-2 border-blue-800 bg-blue-darken rounded p-6 focus:outline-none focus:border-blue-600"> </textarea>
                  <div v-if="!$parent.getField('description').validated" class="absolute italic bottom-0 -mb-6  ml-1 text-sm left-0 text-blue-500">{{$parent.errorMessage('category_id')}}</div>

               </div>
            </div>
      </div>
   </div>

   <div class="px-1 py-1  border-b border-blue-darken my-12 block" style="height:1px; width: 60px;"></div>

   <div class="description_short">
      <div>
         <h3 class="title l3 white w-full ">Detaillez vbotre demande le plus possible</h3>
      </div>
      <div class="mt-6">
            <div class="text-gray-600 italic mb-2">Soyez le plus précis possible. Le titre de la demande est la premiére information que les candidats verront.</div>
            <div class="flex space-between -mx-3">
               <div class="w-full mx-3 relative">
                  <textarea type="select" placeholder="Ex: Informatique" v-model="contentValue"
                  class="w-full  text-white border-2 border-blue-800 bg-blue-darken rounded p-6 focus:outline-none focus:border-blue-600"> </textarea>
                  <div v-if="!$parent.getField('content').validated" class="absolute italic bottom-0 -mb-6  ml-1 text-sm left-0 text-blue-500">{{$parent.errorMessage('content')}}</div>

               </div>
            </div>
      </div>
   </div>

</div>
</template>

<script>
import FormSelect from "./FormSelect.vue"
import vSelect from 'vue-select'
import "vue-select/src/scss/vue-select.scss";


export default {
   components : { FormSelect, vSelect},
   
   props: ['data'],

   data() {
      return {
         categories: []
      }
   },
   methods: {
      setSelected(value) {
         console.log(value)
         if(value){
            this.$parent.getField('category_id').data = value 
            this.$parent.getField('category_id').validated = true 
         }else{
            this.$parent.getField('category_id').data = {} 
            this.$parent.getField('category_id').validated = false 
         }
          this.$parent.isValidated(this.data)
       
      }
   },

   computed: {
      categorySelected: function(){
         if(this.$parent.getField('category_id').data.id){
            return this.$parent.getField('category_id').data
         }
          this.$parent.getField('category_id').data = {}
          this.$parent.getField('category_id').validated = false
         return "Choisisez une categorie"
      },
      descriptionValue: {
         get: function() {
            return this.$parent.getField('description').value
         },
         set: function(newValue) {
            this.$parent.getField('description').value = newValue
         }
      },
      contentValue: {
         get: function() {
            return this.$parent.getField('content').value
         },
         set: function(newValue) {
            this.$parent.getField('content').value = newValue
         }
      },

   },
   watch: {

      descriptionValue: function(newDescription, oldDescrption){
         if(newDescription.trim().length > 1) {
            this.$parent.getField('description').validated = true
         }else{
            this.$parent.getField('description').validated = false
         }
         this.$parent.isValidated(this.data)
      },

      contentValue: function(newContent, oldContent){
         if(newContent.trim().length > 1) {
            this.$parent.getField('content').validated = true
         }else{
            this.$parent.getField('content').validated = false
         }
         this.$parent.isValidated(this.data)
      }
   },

   mounted() {
      let vm = this
      axios.get('/demands/categories')
         .then(function (response) {
            vm.categories = response.data
         })
         .catch(function(error){
            console.log(error)
         })
   }
}
</script>


<style lang="scss" scoped>
   .vs__dropdown-toggle{
      @apply w-full  text-white border-2 border-blue-800 bg-blue-darken rounded p-6;
      
   }
   .create_demand_form .vs__search::placeholder,
  .create_demand_form .vs__dropdown-toggle,
  .create_demand_form .vs__dropdown-menu {
    @apply bg-blue-darken;
    border: none;
    color: white;
    
  }

  .create_demand_form .vs__clear,
  .create_demand_form .vs__open-indicator {
    fill: #394066;
  }
  .create_demand_form .vs__selected{
     color: white;
  }
  .create_demand_form .vs__dropdown-menu li{
     color: white;
  }
</style>