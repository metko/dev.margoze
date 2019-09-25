<template lang="">
   <div class="flex flex-col justify-center md:-mt-10 h-full px-4">
      <div>
         <h3 class="title l3 white w-full ">Vous désirez ajouter des photos ?</h3>
      </div>
      <div class="mt-6">
         <div class="text-gray-600 italic mb-2">Une demande avec photo est en moyenne 3 fois plus consulter.</div>
           <div class="">
              <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" class="flex -mx-3 flex-wrap w-full"></vue-dropzone>

               <!-- <div class="p-3">
                     <div class="h-32 w-32 text-xl bg-blue-darken rounded flex align-center justify-center items-center text-white font)-bold">+</div>
                     <input type="hidden" name='picture_one' value='img_path'>
               </div>
               <div class="p-3">
                     <div class="h-32 w-32 text-xl bg-blue-darken rounded flex align-center justify-center items-center text-white font)-bold">+</div>
                     <input type="hidden" name='picture_one' value='img_path'>
               </div>
               <div class="p-3">
                     <div class="h-32 w-32 text-xl bg-blue-darken rounded flex align-center justify-center items-center text-white font)-bold">+</div>
                     <input type="hidden" name='picture_one' value='img_path'>
               </div>
               <div class="p-3">
                     <div class="h-32 w-32 text-xl bg-blue-darken rounded flex align-center justify-center items-center text-white font)-bold">+</div>
                     <input type="hidden" name='picture_one' value='img_path'>
               </div>
               <div class="p-3">
                     <div class="h-32 w-32 text-xl bg-blue-darken rounded flex align-center justify-center items-center text-white font)-bold">+</div>
                     <input type="hidden" name='picture_one' value='img_path'>
               </div> -->
           </div>
 
           <div>
               <h3 class="title l3 white w-full mt-12">Plus de photos ?</h3>
           </div>
           <div class="flex mt-3">
               <button class="btn inline-block btn-white">Passer à un abonnement supérieur</button>
           </div>
      </div>
   </div>
</template>

<script>

import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

export default {
      components: {
            vueDropzone: vue2Dropzone
      },
       props: ["data"],
      data: function () {
            return {
                  dropzoneOptions: {
                        url: 'https://httpbin.org/post',
                        thumbnailWidth: 150,
                        thumbnailHeight: 150,
                        maxFilesize: 0.5,
                        headers: { "My-Awesome-Header": "header value" }
                  }
            }
      }
}
</script>