<template lang="">
   <div class="flex flex-col justify-center md:-mt-10 h-full px-4">
      <div>
         <h3 class="title l3 white w-full ">Vous désirez ajouter des photos ?</h3>
      </div>
      <div class="mt-6">
         <div class="text-gray-600 italic mb-2">Une demande avec photo est en moyenne 3 fois plus consulter.</div>
           <div class="flex flex-wrap -mx-3">
            <div v-if="errors.length" class="px-3 w-full ">
                  <div v-for="error in errors" v-if="error.errors.length" class="py-2 px-3  text-sm text-white bg-red-800 rounded my-1">
                        <strong>Image {{error.field_id}}</strong>  : 
                        <span v-for="message in error.errors">{{ message.message}} </span>  
                  </div>
            </div>   
            
              
              <div v-for="file in files" v-bind:key="file.id" div class="p-3" >
                     <div class="file_input_demand align-center ">
                           <img v-if="hasPreview(file.id)" :src="data.fields.find(field => field.id == file.id).src">
                           <span v-else >+</span>
                              <input :id="file.name" type="file" :name="'file['+file.id+']'" value='' v-on:change="openFile(file)"
                                    class="bg-red-200 opacity-0 absolute top-0 left-0 right-0 bottom-0" 
                              >
                     </div>
                     
                     <!-- <img src="" :id="'preview_'+file.id" height="200" alt="Image preview..."> -->
               </div>
            
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


export default {
      components: {
           
      },
      props: ["data"],
      data: function () {
           return {
            files : [],
            errors : [],
            formats: ['jpeg', 'jpg', 'png'],
            max_file_size: 500
           }
      },
      mounted() {
            this.createFields()
      },
      methods: {
            hasPreview: function(file_id) {
                  let file = this.data.fields.find(field => field.id == file_id)
                  if(file && file.src ){
                        return true
                  }else{
                        return false
                  }
                  
            },
            createFields: function() {
                  let i
                  for (i = 0; i < this.data.file_count; i++) { 
                        this.files.push({
                        id: i + 1,
                        name: "file_"+ (i + 1)
                        })
                  }  
            },
            openFile: function(file) {
                   

                  let reader  = new FileReader();
                  let uploadedFile  = document.getElementById(file.name).files[0];

                  let hasErrors = this.errors.find(error => error.field_id == file.id)   
                  if(hasErrors){
                       hasErrors.errors = []
                  }

                  let hasFile = this.data.fields.find(img => img.id == file.id)
                  if(hasFile){
                        hasFile.src = ""
                  }

                  if (uploadedFile) {
                        reader.readAsDataURL(uploadedFile);
                        this.validateFile(uploadedFile, file.id) 
                             
                        if(this.errors.length && this.errors[0].errors.length) {
                              return false
                        }

                  }
                  let vm = this
                  reader.addEventListener("load", function () {
                        let slotFile = vm.data.fields.find(img => img.id == file.id)
                        if(slotFile){
                              slotFile.src = reader.result
                              slotFile.uploadedFile = uploadedFile
                        }else{
                              vm.data.fields.push({
                                    id: file.id, 
                                    name : 'file['+file.id+']',
                                    src : reader.result,
                                    uploadedFile
                              })
                        }
                        
                  }, false);
          
            },

            validateFile: function(file, file_id) {
                  if(file.size > 500000) {
                        let errors = this.errors
                        let hasError = this.errors.find(error => error.field_id == file_id)
                        let message = "L'image ne doit pas dépasser 500ko" 
                        let type = "size"
                        let error = {type : type, message: message}
                       
                        if(hasError) {
                            hasError.errors.push(error)
                        }else{
                              errors.push({ 
                                    field_id : file_id,
                                    errors: [error],
                              }) 
                        }
                         
                  }
                  if(! this.validateFormat(file.type)) {
                        let errors = this.errors
                        let hasError = this.errors.find(error => error.field_id == file_id)
                        let message = "Les formats autorisé sont jpg et png" 
                        let type = "format"
                        let error = {type : type, message: message}
                        
                        if(hasError) {
                              // console.log(hasError)
                            hasError.errors.push(error)
                        }else{
                              errors.push({ 
                                    field_id : file_id,
                                    errors: [error],
                              }) 
                        }
                  }
            },
            
            validateFormat: function(type) {
                  type = type.split('/')
                  let valid = false
                  //console.log(type)
                  this.formats.forEach(function(format) {
                        if(format  ==  type[1]){
                              valid = true
                        }
                          
                  })
                  return valid
            }
      }
}
</script>


<style scoped>
      .file_input_demand{
            @apply h-32 w-32 text-xl bg-blue-darken rounded flex  justify-center items-center text-white font-bold relative overflow-hidden;
            
      }
      .file_input_demand input:hover{
            cursor: pointer;   
            
      }

</style>