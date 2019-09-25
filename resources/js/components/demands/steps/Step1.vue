<template lang="">
  <div class="flex flex-col justify-center md:-mt-12 h-full px-4">
   <div>
      <h3 class="title l3 white w-full ">Quel est le titre de votre demande ?</h3>
   </div>
   <div class="mt-6">
      
      <div class="text-gray-600 italic mb-2">Soyez le plus précis possible. Le titre de la demande est la premiére information que les candidats verront.</div>
      <div class="relative">
         <input type="text" placeholder="Ex: Recherche professeur d'anglais"
         class="w-full text-white border-2 border-blue-800 bg-blue-darken rounded p-6 focus:outline-none focus:border-blue-600"
         v-model="titleValue" name="title">
         <div v-if="!$parent.getField('title').validated" class="absolute italic bottom-0 -mb-6  ml-1 text-sm left-0 text-blue-500">{{$parent.errorMessage('title')}}</div>
      </div>

   </div>
</div>
</template>

<script>
   export default {
      props: ['data'],
      data() {
         return {
            //title : this.data.fields.find(field => field.name === 'title'),  
         }
      },
      mounted() {
        //console.log(this.title)
      },
      computed: {

         titleValue: {
            get: function() {
               return this.$parent.getField('title').value
            },
            set: function(newValue) {
               this.$parent.getField('title').value = newValue
            }
         },

      },

      watch: {
         titleValue: function(newTitle, oldTitle){
            if(newTitle.trim().length > 7) {
               this.$parent.getField('title').validated = true
            }else{
               this.$parent.getField('title').validated = false
            }
            this.$parent.isValidated(this.data)
         }
      },

      methods : {


      }
      
   }
</script>