<template>
   <div>
      <form :action="registerUrl" method="POST" v-on:submit.prevent="onSubmit">
                <div class="mt-6">
                    <div class="title l4 text-gray-800">Identifiants de connexion</div>
                
                    
                     <div v-if="hasErrors" class="w-full ">
                           <div class="w-1/2 mx-auto text-center bg-red-600 p-2 rounded mt-4 text-white ">
                              Merci de corriger les erreurs
                           </div>   
                     </div>
                   
                    <div class="lg:flex mt-6 lg:-mx-3">
                        <div class="w-full lg:w-1/2 lg:px-3 my-8 lg:my-6 relative">
                            <input type="tedt" v-on:input="checkEmail()" name="email" value="" v-model="data.email"
                            class="input" placeholder="Email">
                            <div v-if="errors.email"
                              class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{errors.email[0]}}</div>
                        </div>    
                    </div>
                    <div class="lg:flex lg:-mx-3">
                            <div class="w-full lg:w-1/2 lg:px-3 my-8 lg:my-6 relative">
                                <input v-on:input="errors.password[0] = ''" type="password" name="password" value="" v-model="data.password"
                                class="input " placeholder="Mot de passe">
                                <div v-if="errors.password"
                              class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{errors.password[0]}}</div>
                            </div>
                            <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                                <input type="password" v-on:input="errors.password_confirmation[0] = ''" name="password_confirmation" value="" v-model="data.password_confirmation"
                                class="input " placeholder="Confirmez le mot de passe">
                               <div v-if="errors.password_confirmation"
                              class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{errors.password_confirmation[0]}}</div>
                            </div>
                    </div>
                </div>
                <div class="mt-6">
                    
                    <div class="title l4 text-gray-800">Informations personnelles</div>

                    <div class="lg:flex  lg:-mx-3">
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                            <input type="text" name="first_name"  v-on:input="errors.first_name[0] = ''" class="input" value="" v-model="data.first_name"
                             placeholder="Prénom"  id="first_name">

                           <div v-if="errors.first_name"
                           class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{errors.first_name[0]}}</div>
                        </div>
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                            <input type="text" name="last_name" v-on:input="errors.last_name[0] = ''"  class="input" value="" v-model="data.last_name"
                            placeholder="Nom" >
                            <div v-if="errors.last_name"
                           class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{errors.last_name[0]}}</div>
                            
                        </div>
                    </div>
                     <div class="lg:flex mt-12 lg:mt-0 lg:-mx-3">
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                           <input type="hidden" v-on:change="errors.commune_id[0] = ''" v-model="data.commune_id"  name="commune_id">
                           <vSelect
                              label="name"
                              :reduce="option => option.id"
                              @input="setCommune"
                              :options="communes"
                              v-model="data.commune_id" 
                              placeholder="Choisir une communes"
                              class="register_select_form focus:outline-none focus:border-blue-600"
                           ></vSelect>
                           <div v-if="errors.commune_id"
                           class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{errors.commune_id[0]}}</div>
                        </div>
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                           <input type="hidden"  v-on:change="errors.district_id[0] = ''" v-model="data.district_id"   name="district_id">
                           <vSelect
                              label="name"
                              :reduce="option => option.id"
                              :options="districts"
                              @input="setDistrict"
                              v-model="data.district_id" 
                              placeholder="Choisir un quartier"
                              class="register_select_form focus:outline-none focus:border-blue-600"
                           ></vSelect>
                               <div v-if="errors.district_id"
                              class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{errors.district_id[0]}}</div>
                        </div>
                     </div>

                    <div class="lg:flex mt-12 lg:mt-0 lg:-mx-3">
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                        <input type="date" name="date_of_birth" v-on:input="errors.date_of_birth[0] = ''"  class="input" placeholder="Date de naissance" value=""  v-model="data.date_of_birth">
                           <div v-if="errors.date_of_birth"
                              class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{errors.date_of_birth[0]}}</div>
                        </div>
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                            <input type="text" name="phone_1"  v-on:input="errors.phone_1[0] = ''" class="input" value="" v-model="data.phone_1"
                            placeholder="Portable" >
                           <div v-if="errors.phone_1"
                              class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{errors.phone_1[0]}}</div>
                        </div>
                    </div>
                    <div v-if="!isLoading" class="lg:flex lg:-mx-3 mt-6">
                        
                        <button class="btn lg:mx-3">Créer un compte</button>
                    </div>
                    <div v-else class="mt-10">
                       <loader></loader>
                    </div>
                </div>
            </form>
   </div>
</template>

<script>
import vSelect from "vue-select";
import loader from "@/components/LoaderAnim.vue"
export default {
      components : {vSelect, loader},
      props: ['register-url'],
      data() {
         return {
            data: {
               email: null,
               first_name: null,
               last_name: null,
               commune_id: null,
               district_id: null,
               password: null,
               password_confirmation: null,
               date_of_birth: null,
               phone_1: null,
            },
            communes: [],
            districts: [],
            isLoading: false,
            errors: {
               email: [],
               first_name: [],
               last_name: [],
               commune_id: [],
               district_id: [],
               password: [],
               password_confirmation: [],
               date_of_birth: [],
               phone_1: [],

            },
            hasErrors: false
         }
      },
      mounted() {
         console.log(this.registerUrl)
         let vm = this
         axios.get("/communes")
         .then(function(response) {
            vm.communes = response.data;
         })
         .catch(function(error) {
            console.log(error);
         });
      },
      methods: {
         checkEmail(value) {
            this.errors.email = []
            if(!this.data.email) {
               this.errors.email.push('Email requis')
               return false
            }
        
            let regexEmail = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/
            if(regexEmail.test(this.data.email)) {
               this.errors.email= ""
               return true
            }else{
               this.errors.email.push('Veuillez séléctionner une addresse éléctronique valide')
               return false
            }
         },
         checkError() {
            this.hasErrors = false
            if(!this.checkEmail()) {
               this.hasErrors = true
            }
            if(!this.data.password) {
               this.errors.password = []
               this.errors.password.push("Mot de passe requis")
               this.hasErrors = true
            }
            if(!this.data.password_confirmation) {
               this.errors.password_confirmation = []
               this.errors.password_confirmation.push("Mot de passe confirm requis")
               this.hasErrors = true
            }
            if(!this.data.first_name) {
               this.errors.first_name = []
               this.errors.first_name.push("First name requis")
               this.hasErrors = true
            }
            if(!this.data.last_name) {
               this.errors.last_name = []
               this.errors.last_name.push("Last name requis")
               this.hasErrors = true
            }
            if(!this.data.commune_id) {
               this.errors.commune_id = []
               this.errors.commune_id.push("commune  requis")
               this.hasErrors = true
            }
            if(!this.data.district_id) {
               this.errors.district_id = []
               this.errors.district_id.push("quartier  requis")
               this.hasErrors = true
            }
            if(!this.data.date_of_birth) {
               this.errors.date_of_birth = []
               this.errors.date_of_birth.push("date de niassance  requis")
               this.hasErrors = true
            }
            if(!this.data.phone_1) {
               this.errors.phone_1 = []
               this.errors.phone_1.push("Phone requis")
               this.hasErrors = true
            }
            
         },
         onSubmit() {
            let vm = this
            this.checkError()
             if(this.hasErrors) {
                return
             } 
              vm.isLoading = true
             axios.post(this.registerUrl, {
                email: this.data.email,
                password: this.data.password,
                password_confirmation: this.data.password_confirmation,
                first_name: this.data.first_name,
                last_name: this.data.last_name,
                commune_id: this.data.commune_id,
                district_id: this.data.district_id,
                date_of_birth: this.data.date_of_birth,
                phone_1: this.data.phone_1
            })
            .then(function (response) {
               console.log(response)
               
               if(response.status == 200) {
                  location.reload()
               }
            })
            .catch(function (error,) {
               console.log(error.response.data)
               vm.errors = error.response.data.errors
               vm.hasErrors = true
               vm.isLoading = false
            });
            console.log('submit')
         },
         setCommune(value) {
            if(value) {
               this.errors.commune_id = ""
               let vm = this
               axios
                  .get("/districts/commune/" + value)
                  .then(function(response) {
                     vm.districts = response.data;
                  })
                  .catch(function(error) {
                     console.log(error);
                  });
            }
         },
         setDistrict(value) {
            if(value) {
               this.errors.district_id = ""
            }
         
         }
      },
         
}
</script>

<style lang='scss'>

.register_select_form .vs__search::placeholder,
.register_select_form .vs__dropdown-toggle,
.register_select_form .vs__dropdown-menu {
  
   border: none;
   //color: white;
}
.register_select_form{
   @apply border-0 border-b  border-gray-600 ;
   .vs__search,.vs__search:focus, .vs__selected{
      padding-left: 0;
   }
}
.register_select_form .vs__clear,
.register_select_form .vs__open-indicator {
  //fill: #394066;
}
.register_select_form .vs__selected {
  //color: white;
}
.register_select_form .vs__dropdown-menu li {
  //color: white;
}
</style>