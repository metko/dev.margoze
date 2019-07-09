<template>
   <modal :name="name"  classes="bg-white h-auto rounded shadow-lg" style="overflow: visible" height="auto">
    <div class="flex justify-center items-center flex-col py-8 px-6">
            <form id="submitSettingsForm"  class="w-full" :action='action' method="POST" v-on:submit.prevent="onSubmit">
                  <input type="hidden" name='_token' :value="csrf">
                  <div class="flex flex-wrap justify-center -mx-3 mb-6">
                        <div class="w-full px-3 mb-6 md:mb-0">
                           <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="content">
                              Valider la date par default ou programmez un autre jour avec {{ user2.username }}
                           </label>
                        
                           <datepicker v-model="validDate" name="date_settings" :format="formatDate" class="w-full"
                              input-class="appearance-none block w-full bg-gray-100 text-gray-700 border border-indigo-600 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800"></datepicker>
                        </div>

                        <div class="w-full px-3 my-4 mb-4 text-gray-700 text-center text-xs ">
                           <p>Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos</p>
                        </div>    
                        <button
                           type="submit"
                           class="ml-auto bg-indigo-600 hover:bg-indigo-800 focus:outline-none text-white text-sm font-semibold py-2 px-4 rounded-full">
                           Propose 
                        </button>        
                  </div>
            </form>
         </div>
   </modal>
</template>


<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment'

export default {
   components: {
    Datepicker
  },
   props:['name', "user2", "action", 'be_done_at', 'csrf'],
   data(){
      return {
         validDate : this.be_done_at
      }
   }, 
   methods: {
   
      formatDate (date) {
         moment.locale("fr")
         return moment(date).format('LL');
      },
      onSubmit: function(){ 
         let form = document.getElementById('submitSettingsForm');
         let input = document.createElement("input");
         input.setAttribute("type", "hidden");
         input.setAttribute("name", "be_done_at");
         input.setAttribute("value", moment(this.validDate).format('YYYY-MM-DD hh:mm:ss'));
         //append to form element that you want .
         form.appendChild(input);
         form.submit()
      }  
   },

}
</script>
