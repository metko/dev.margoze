<template>
   <modal :name="name" class="bg-white h-auto rounded shadow-lg" height="auto">
            <div class="flex justify-center items-center flex-col py-8 px-6">
            <div class="font-bold text-xl text-indigo-800 mb-6 text-center">Postuler a la demande {{ demand.title }}</div>
            <form class="w-full" :action='action' method="POST">
                    
                     <div class="flex flex-wrap justify-center -mx-3 mb-6">
                           <div class="w-full px-3 mb-6 md:mb-0">
                              <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="content">
                              Ecrivez un message à {{ demand.owner.username }}
                              </label>
                              <textarea class="appearance-none block w-full bg-gray-100 text-gray-700 border border-indigo-600 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800" 
                              id="content" v-model="message" type="text" :placeholder="placeholder"></textarea>
                              <p v-if="showError" class="text-red-500 text-xs italic">Please fill out this field. 20 charactére min</p>
                           </div>

                           <div class="w-full px-3 my-4 mb-8 text-gray-700 text-xs ">
                              <p>Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos</p>
                           </div>
                           <div>
                              <button
                                 @click.prevent="submit"
                                 type="submit"
                                 v-bind:class="classButton"
                                 class="ml-auto focus:outline-none text-white text-sm font-semibold py-2 px-4 rounded-full"
                                  >
                                 Create demande
                              </button>
                              <a 
                                 @click.prevent="$modal.hide(name)"
                                 href=""
                                 class="ml-auto bg-red-600 hover:bg-red-800 text-white text-sm font-semibold py-2 px-4 rounded-full">
                                 Annuler
                              </a>

                           </div>
                     </div>
               </form>
            </div>
         </modal>
</template>

<script>
export default {
   props: ['demand', 'action', 'name'],
   data() {
      return {
         hasError: true,
         message:"",
         showError: false
      }
   },
   mounted() {
      //console.log(this.demand);
   },
   computed : {
      placeholder: function(){
         return "Message pour "+this.demand.owner.username;
      },
      modalName: function(){
         return "create-candidature-"+this.demand.id;
      },
      classButton: function () {
         return {
            'bg-indigo-600 hover:bg-indigo-800 ': !this.hasError ,
            'cursor-not-allowed bg-gray-300': this.hasError
         }
      }
   },
   watch: {
      message : function(){

            if(this.message.length > 10){
               this.showError = true;
            }
            if(this.message.length > 20){
               this.showError = false;
               this.hasError = false;
            }else{
               this.hasError = true;
               
            }
      }
   },
   methods: {
      submit: function(){
         if(!this.hasError){

         }
      }
   }
}
</script>
