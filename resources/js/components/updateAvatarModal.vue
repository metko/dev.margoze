<template>
   <div>
      <a href=""  @click.prevent="$modal.show('updateAvatarModal')"  class="btn small">Changer d'avatar</a>

      <modal name="updateAvatarModal"  classes="bg-white h-auto rounded shadow-lg flex flex-col" 
         width="90%" :maxWidth="600" :adaptive="true" >

            <div class="flex items-center flex-col  py-6 pb-0 bg-white h-full">   
                  <div>
                       <img class="w-16 h-16 rounded-full" :src="avatar" alt="Avatar of Jonathan Reinink">
                  </div>
                 <div class="flex w-full h-full items-center justify-center bg-grey-100">
                  <label class="w-64 flex flex-col items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-gray-100 hover:text-blue-primary">
                     <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                           <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                     </svg>
                     <span class="mt-2 text-base leading-normal font-sm">Select a file</span>
                     <input type='file' class="hidden" ref="file" @change.prevent='uploadFile' />
                  </label>
               </div> 
               
            </div>

            <div class="text-gray-500 flex w-full items-center mt-auto">
               <a href="#"
               @click.prevent="$modal.hide('updateAvatarModal')" 
               class="w-full text-center bg-gray-200 text-red-600 hover:bg-gray-400 py-4">Retour</a>
            </div>
      </modal>
   </div>
</template>


<script>
   export default {
      props: ['user', 'avatar'],
      data() {
         return {
               file: null
         }
      },
      methods: {
         uploadFile () {
            this.file = this.$refs.file.files[0];
            this.submitFile();
         },
         submitFile(){
            let formData = new FormData();
            formData.append('file', this.file);
            console.log(formData)
            axios.post('/dashboard/profile/avatar',
                formData,
                {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }
            ).then(function(){
               console.log('SUCCESS!!');
            })
            .catch(function(){
               console.log('FAILURE!!');
            });
            },
      }
   
   }
</script>