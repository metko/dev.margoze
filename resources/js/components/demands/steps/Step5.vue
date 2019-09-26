<template lang="">
   <div class="flex flex-col h-full md:overflow-y-scroll md:overflow-x-hidden">
      <div class="resume">
         <div>
            <div>
               <h3 class="title l3 white w-full ">{{title}}</h3>
            </div>
            <div class="flex mt-3">
               <div class="px-2 py-1 txt-sm text-white bg-blue-darken rounded mr-3">{{category}}</div>
            </div>
         </div>
         <div class="px-1 py-1  border-b border-blue-darken my-12 block" style="height:1px; width: 60px;"></div>
         <div>
            <div>
               <div class="font-bold text-white">Désciption courte</div>
               <div class="text-white mt-3 leading-relaxed text-gray-400">
                     {{description}}
               </div>
            </div>
            <div class="mt-8">
               <div class="font-bold text-white">Désciption longue</div>
               <div class="text-white mt-3 leading-relaxed text-gray-400">
                  {{content}}               
               </div>
            </div>
         </div>
         <div class="px-1 py-1  border-b border-blue-darken my-12 block" style="height:1px; width: 60px;"></div>
         <div>
            <div class="flex items-center">
               <div class="uppercase font-bold text-white  mr-4">à</div>
               <div class="btn btn-blue btn-inverse small mr-4">{{localisation}}</div>
               <div class="text-white">{{address}}, {{postal}}</div>
            </div>
            <div class="flex items-center mt-4">
               <div class="font-bold text-white  mr-4">Le</div>
               <div class="btn btn-white small">{{date}}    </div>
            </div>
         </div>
         <div class="px-1 py-1  border-b border-blue-darken my-12 block" style="height:1px; width: 60px;"></div>
         <div>
            <div class="flex -mx-3">
               <div class="px-3 h-28 w-28 rounded overflow-hidden shadow-sm">
                  <img src="http://lorempixel.com/100/100/" alt="">
               </div>
               <div class="px-3 h-28 w-28 rounded overflow-hidden shadow-sm">
                  <img src="http://lorempixel.com/100/100/" alt="">
               </div>
               <div class="px-3 h-28 w-28 rounded overflow-hidden shadow-sm">
                  <img src="http://lorempixel.com/100/100/" alt="">
               </div>

            </div>
         </div>
         <!-- <div class="px-1 py-1  border-b border-blue-darken my-12 block" style="height:1px; width: 60px;"></div>
         <div>
            <div>
               <div class="font-bold text-white">Ce que doit fournir le prestataire</div>
               <div class="flex flex-wrap -mx-2 mt-4">
                  <div class="px-2">
                        <div class="px-2 py-1 text-sm text-white bg-blue-darken rounded">Transports</div>       
                  </div>
                  <div class="px-2">
                        <div class="px-2 py-1 text-sm text-white bg-blue-darken rounded">Livres</div>       
                  </div>
                  <div class="px-2">
                        <div class="px-2 py-1 text-sm text-white bg-blue-darken rounded">Outils</div>       
                  </div>
               </div>
            </div>
            <div class="mt-6">
               <div class="font-bold text-white">Notes additionnelles</div>
               <div class="text-white leading-relaxed mt-2">
                  Plusieurs variations de Lorem Ipsum peuvent être trouvées ici ou là, mais la majeure partie d'entre elles a été altérée par l'addition d'humour ou de mots aléatoires qui ne ressemblent pas une seconde à du texte standard.
               </div>
            </div>

         </div> -->
         <div class="px-1 py-1  border-b border-blue-darken my-12 block" style="height:1px; width: 60px;"></div>
         <div class="bg-blue-darken py-6 px-6 rounded text-white flex items-center relative">
            <input type="checkbox" id="checkbox" v-model="conditionsValue"class="mr-4">
            <label for="checkbox">J'accepte les conditions general</label>
            <div v-if="!$parent.getField('conditions').validated" class="absolute italic bottom-0 -mb-6  ml-1 text-sm left-0 text-blue-500">{{$parent.errorMessage('conditions')}}</div>

         </div>
      </div>
   </div>
</template>

<script>
import fecha from 'fecha'
fecha.i18n = {
	dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
	dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
	monthNamesShort: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jui', 'Jui', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
	monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'],
	amPm: ['am', 'pm'],
	// D is the day of the month, function returns something like...  3rd or 11th
	DoFn: function (D) {
		return D + [ 'th', 'st', 'nd', 'rd' ][ D % 10 > 3 ? 0 : (D - D % 10 !== 10) * D % 10 ];
    }
}
export default {
   props: ["data"],
   computed: {
      conditionsValue: {
         get: function (){
            return this.$parent.getField('conditions').value 
         },
         set: function(newValue) {
            this.$parent.getField('conditions').value = newValue
         }
      },
      title: function() {
         return this.$parent.getField('title', 1).value
      },
      category: function() {
         return this.$parent.getField('category_id', 2).data.name
      },
      description: function() {
         return this.$parent.getField('description', 2).value
      },
      content: function() {
         return this.$parent.getField('content', 2).value
      },
      localisation: function() {
         return this.$parent.getField('commune_id', 3).data.name+', '+this.$parent.getField('district_id', 3).data.name
      },
      address: function() {
         return this.$parent.getField('address_1', 3).value+', '+this.$parent.getField('address_2', 3).value
      },
      postal: function() {
         return this.$parent.getField('postal', 3).value
      },
      date: function() {
         let date = this.$parent.getField('be_done_at', 3).value
         return fecha.format(date,  'dddd D MMMM, HH:mm')
      },

   },

   watch:  {
      conditionsValue: function(newCondition, oldCondition){
         if(newCondition === true) {
            this.$parent.getField('conditions').validated = true
         }else{
            this.$parent.getField('conditions').validated = false
         }
         this.$parent.isValidated(this.data)
      },
   }
  
}  
</script>