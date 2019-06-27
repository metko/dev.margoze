<template>
   <modal :name="name" class="bg-white h-auto rounded shadow-lg" height="auto">
            <div class="flex justify-center items-center flex-col py-8 px-6">

               <div v-if="success" class="flex justify-center items-center flex-col">
                  <div class="font-bold text-xl text-indigo-800 mb-6 text-center">{{success}}</div>
                  <a 
                     @click.prevent="$modal.hide(name)"
                     href=""
                     class="bg-indigo-600 hover:bg-indigo-800 text-white text-sm font-semibold py-2 px-4 rounded-full">
                     Fermer
                  </a>
               </div>
               <div v-else>
                  <div class="font-bold text-xl text-indigo-800 mb-6 text-center">Postuler a la demande {{ demand.title }}</div>
               </div>

               <form v-if="!success" @submit.prevent="validateBeforeSubmit" class="w-full" :action='action' method="POST">
                    
                     <div class="flex flex-wrap justify-center -mx-3 mb-6">
                           <div class="w-full px-3 mb-6 md:mb-0">
                              <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="content">
                              Ecrivez un message à {{ demand.owner.username }}
                              </label>
                              <textarea 
                                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-indigo-600 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800" 
                                    id="content" 
                                    v-validate="'required|min:20'" 
                                    name="content" 
                                    v-model="content" 
                                    type="text"
                                    v-on:change="onChangeFields"
                                    :disabled="sending"
                                    v-bind:class="disabledInput"
                                    :placeholder="placeholder"></textarea>                  
                              <p v-if="errors.has('content')" class="text-red-500 text-xs italic">{{ errors.first('content') }}</p>
                           </div>
                          
                           <div class="w-full px-3 my-4 mb-4 text-gray-700 text-center text-xs ">
                              <p>Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos</p>
                           </div>

                           <div class="w-full px-3 mb-6">
                              <label class="block text-center text-gray-700">
                                 <input  
                                    v-on:change="onChangeFields" 
                                    v-validate="'required'" 
                                    name="conditions"
                                    :disabled="sending"
                                    v-bind:class="disabledInput"
                                    v-model="conditions" 
                                    class="mr-2 leading-tight" 
                                    type="checkbox">
                                 <span class=" tracking-wide text-gray-700 text-xs font-bold mb-2">
                                    J'accepte les conditions
                                 </span>
                              </label>
                              <p v-if="errors.has('conditions')" class="text-center text-red-500 text-xs italic">{{ errors.first('conditions') }}</p>
                           </div>

                           <div v-if="!sending">
                              <button
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
                              <button
                                 v-on:click="emitIncrementCandidature"
                                 class="ml-auto bg-teal-600 focus:outline-none text-white text-sm font-semibold py-2 px-4 rounded-full"
                                  >
                                 Event
                              </button>
                           </div>
                           
                           <div v-else>
                              <span
                                 class="ml-auto bg-orange-600   text-white text-sm font-semibold py-2 px-4 rounded-full"
                                  >
                                 Sending...
                              </span>
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
         content:"",
         conditions: false,
         isValid: false, 
         sending: false,
         success: false
      }
   },
   created(){
      const dict = {
         custom: {
            content: {
               min: (field, params, data) => 'Un message de minimum '+params+' caractéres! Faites un effort...',
               required: 'Il est obligatoire de se présenter!'
            },
            conditions: {
               required: 'Vous devez accepter les conditions',
            }
         }
      };
      this.$validator.localize('fr', dict);
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
            'bg-indigo-600 hover:bg-indigo-800 ': this.isValid ,
            'cursor-not-allowed bg-gray-300':  !this.isValid
         }
      },
      disabledInput: function () {
         return {
            'bg-gray-300 cursor-not-allowed ': this.sending ,
         }
      },
   },
   methods: {
      emitIncrementCandidature () {
         this.$emit('incrementCandidature', 1)
      },
      onChangeFields () {
         if(this.fields.content.valid && this.conditions){
            this.isValid = true
         }else{
            this.isValid = false
         }     
      },
      validateBeforeSubmit () {
         if(!this.isValid){
            return;
         }
         this.$validator.validateAll().then((isValidated) => {
         if (isValidated) {
            this.sending = true;
            // eslint-disable-next-line
            let vm = this;
            axios.post(this.action, {
               content: this.content,
               conditions: this.conditions
            })
            .then(function (response) {
               vm.sending = false;
               vm.success = 'La candidature à bien été envoyer à ' + vm.demand.owner.username;
               vm.$emit('incrementCandidature', 1)
            })
            .catch(function (error) {
               vm.success = error.response.data.message;
            });
            return;
         }
         
         alert('Correct them errors!');
         })
      }
   }
}
</script>
