<template lang="">
  <div class="flex flex-col h-full md:overflow-y-auto  px-4">
      <div class="adress">
         <div>
            <h3 class="title l3 white w-full ">A quel endroit ?</h3>
         </div>
         <div class="mt-6">
            <div class="text-gray-600 italic mb-2">Soyez le plus précis possible. Le titre de la demande est la premiére information que les candidats verront.</div>
            <div class="flex flex-wrap space-between -mx-3">
               <div class="w-full xl:w-1/2 px-3 relative">
                    <!-- <FormSelect  placeholder="Choisir une commune" name="commune_id"
                        selectClass="w-full  text-white border-2 border-blue-800 bg-blue-darken rounded p-6 focus:outline-none focus:border-blue-600"
                        :data="communes"
                        :name="'commune_id'"
                        /></FormSelect> -->
                         <vSelect 
                        label="name" 
                        :options="communes" 
                        @input="setCommuneSelected"
                        :value="communeSelected"
                        placeholder="Choisir une communes"
                        class="create_demand_form focus:outline-none focus:border-blue-600">
                        <div slot="no-options">Aucunes communes trouvés</div></vSelect>
                        <div v-if="!$parent.getField('commune_id').validated" class="absolute italic bottom-0 -mb-6  ml-3 text-sm left-0 text-blue-500">{{$parent.errorMessage('commune_id')}}</div>
               </div>
   
              <div class="w-full xl:w-1/2  mt-10 xl:mt-0 px-3 relative">
                     <vSelect 
                        label="name" 
                        :options="districts" 
                        @input="setDistrictSelected"
                        :value="districtSelected"
                        placeholder="Choisir une ville"
                        class="create_demand_form focus:outline-none focus:border-blue-600">
                        <div slot="no-options">Aucunes villes trouvés</div></vSelect>
                        <div v-if="!$parent.getField('district_id').validated" class="absolute italic bottom-0 -mb-6  ml-3 text-sm left-0 text-blue-500">{{$parent.errorMessage('district_id')}}</div>
               </div>

               <div class="w-full px-3 mt-3 mt-10 relative">
                     <input type="select" placeholder="Adresse compléte " v-model="address1Value"
                     class="w-full  text-white border-2 border-blue-800 bg-blue-darken rounded p-6  focus:outline-none focus:border-blue-600">
                     <div v-if="!$parent.getField('address_1').validated" class="absolute italic bottom-0 -mb-6  ml-3 text-sm left-0 text-blue-500">{{$parent.errorMessage('address_1')}}</div>
               </div>
              <div class="w-1/4 px-3 mt-3 mt-10 relative">
                     <input type="number" placeholder="Code postal" v-model="postalValue"
                     class="w-full  text-white border-2 border-blue-800 bg-blue-darken rounded p-6  focus:outline-none focus:border-blue-600">
                     <div v-if="!$parent.getField('postal').validated" class="absolute italic bottom-0 -mb-6  ml-3 text-sm left-0 text-blue-500">{{$parent.errorMessage('postal')}}</div>
               </div>
               <div class="w-3/4 px-3 mt-3 mt-10 relative">
                     <input type="select" placeholder="Adresse compléte (complément) " v-model="address2Value"
                     class="w-full  text-white border-2 border-blue-800 bg-blue-darken rounded p-6  focus:outline-none focus:border-blue-600">
                     <div v-if="!$parent.getField('address_2').validated" class="absolute italic bottom-0 -mb-6  ml-3 text-sm left-0 text-blue-500">{{$parent.errorMessage('address_2')}}</div>
               </div>
               

            </div>

            
         </div>
      </div>
   
      <div class="px-1 py-1  border-b border-blue-darken my-12 block" style="height:1px; width: 60px;"></div>
   
      <div class="time">
         <div>
            <h3 class="title l3 white w-full ">Et quand ?</h3>
         </div>
         <div class="mt-6">
            <div class="text-gray-600 italic mb-2">Soyez le plus précis possible. Le titre de la demande est la premiére information que les candidats verront.</div>
            <div class="flex flex-wrap space-between -mx-3">
               <div class="w-1/2 px-3 relative">
                  <!-- <input id="date" type="date" placeholder="Commune"
                  class="w-full  text-white border-2 border-blue-800 bg-blue-darken rounded p-6 focus:outline-none focus:border-blue-600 myInput"> -->
                   <date-pick 
                   class="w-full"
                     v-model="date"
                     :value="'ffdsf'"
                     :format="format"
                     :parseDate="parseDate"
                     :formatDate="formatDate"
                     :isDateDisabled="isFutureDate"
                     :nextMonthCaption="nextMonthCaption"
                     :prevMonthCaption="prevMonthCaption"
                     :setTimeCaption="setTimeCaption"
                     :weekdays="weekdays"
                     :months="months"
                      :pickTime="true"
                     :inputAttributes="{readonly: true}"
                     ref="datePickerValue"
                   ></date-pick>
                   <input type="hidden" v-model="beDoneAtValue" name="be_done_at">
                    <div v-if="!$parent.getField('be_done_at').validated" class="absolute italic bottom-0 -mb-6  ml-3 text-sm left-0 text-blue-500">{{$parent.errorMessage('be_done_at')}}</div>

               </div>
   
               <!-- <div class="w-1/2 px-3">
                  <input type="time" placeholder="Quartier"
                  class="w-full   text-white border-2 border-blue-800 bg-blue-darken rounded p-6 focus:outline-none focus:border-blue-600">
               </div> -->
            </div>

          
            <!-- <div class="btn inline-block btn-white mt-4">
               <span class="text-yellow-primary">+ </span> Ajouter un jour
            </div> -->
         </div>
        
      </div>

   </div>
</template>

<script>
import FormSelect from "./FormSelect";
import vSelect from 'vue-select'
import "vue-select/src/scss/vue-select.scss";
import DatePick from "vue-date-pick";
import fecha from "fecha";
import "vue-date-pick/dist/vueDatePick.css";

fecha.i18n = {
  dayNamesShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
  dayNames: [
    "Dimanche",
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi"
  ],
  monthNamesShort: [
    "Jan",
    "Fev",
    "Mar",
    "Avr",
    "Mai",
    "Jui",
    "Jui",
    "Aou",
    "Sep",
    "Oct",
    "Nov",
    "Dec"
  ],
  monthNames: [
    "Janvier",
    "Fevrier",
    "Mars",
    "Avril",
    "Mai",
    "Juin",
    "Juillet",
    "Aout",
    "Septembre",
    "Octobre",
    "Novembre",
    "Decembre"
  ],
  amPm: ["am", "pm"],
  // D is the day of the month, function returns something like...  3rd or 11th
  DoFn: function(D) {
    return (
      D +
      ["th", "st", "nd", "rd"][
        D % 10 > 3 ? 0 : ((D - (D % 10) !== 10) * D) % 10
      ]
    );
  }
};

export default {
  components: { FormSelect, DatePick, vSelect },
  props: ["data"],
  data() {
    return {
      communes: [],
      districts: [],

      format: "dddd D MMMM, HH:mm",
      nextMonthCaption: "Mois suivant",
      prevMonthCaption: "Mois précédent",
      setTimeCaption: "Definir heure:",
      weekdays: ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"],
      months: [
        "Janvier",
        "Fevrier",
        "Mars",
        "Avril",
        "Mai",
        "Juin",
        "Juillet",
        "Aout",
        "Septembre",
        "Octobre",
        "Novembre",
        "Decembre"
      ]
    };
  },
  methods: {
    setCommuneSelected(value) {
         console.log(value)
         if(value){
            this.$parent.getField('commune_id').data = value 
            this.$parent.getField('commune_id').validated = true
            let vm = this
           axios
              .get("/districts/commune/" + value.id)
              .then(function(response) {
                vm.districts = response.data;
                vm.$parent.getField("district_id").data = {};
                vm.$parent.getField("district_id").validated = false;
              })
              .catch(function(error) {
                console.log(error);
              });
         }else{
            this.$parent.getField('commune_id').data = {} 
            this.$parent.getField('commune_id').validated = false 
            this.districts =  []
         }
          this.$parent.isValidated(this.data)
       
      },
    setDistrictSelected(value) {
         console.log(value)
         if(value){
            this.$parent.getField('district_id').data = value 
            this.$parent.getField('district_id').validated = true 
         }else{
            this.$parent.getField('district_id').data = {} 
            this.$parent.getField('district_id').validated = false 
         }
          this.$parent.isValidated(this.data)
       
      },
    parseDate(dateString, format) {
      return fecha.parse(dateString, format);
    },
    formatDate(dateObj, format) {
      this.$parent.getField("be_done_at").value = dateObj;
      return fecha.format(dateObj, format);
    },
    isFutureDate(date) {
      const currentDate = new Date();
      return date < currentDate;
    }
  },
  computed: {
    communeSelected: function(){
         if(this.$parent.getField('commune_id').data.id){
            return this.$parent.getField('commune_id').data
         }
          this.$parent.getField('commune_id').data = {}
          this.$parent.getField('commune_id').validated = false
         return "Choisisez une commune"
      },
    districtSelected: function(){
         if(this.$parent.getField('district_id').data.id){
            return this.$parent.getField('district_id').data
         }
          this.$parent.getField('district_id').data = {}
          this.$parent.getField('district_id').validated = false
         return "-"
      },
    date: {
      get: function() {
        if (this.$parent.getField("be_done_at").value !== "") {
          return fecha.format(
            this.$parent.getField("be_done_at").value,
            "dddd D MMMM, HH:mm"
          );
        } else {
          return fecha.format(new Date(), "dddd D MMMM, HH:mm");
        }
      },
      set: function(newDate) {}
    },

    selectedCommune: function() {
      return this.$parent.getField("commune_id").data;
    },

    beDoneAtValue: {
      get: function() {
        return this.$parent.getField("be_done_at").value;
      },
      set: function(newValue) {
        this.$parent.getField("be_done_at").value = newValue;
      }
    },

    postalValue: {
      get: function() {
        return this.$parent.getField("postal").value;
      },
      set: function(newValue) {
        this.$parent.getField("postal").value = newValue;
      }
    },

    address1Value: {
      get: function() {
        return this.$parent.getField("address_1").value;
      },
      set: function(newValue) {
        this.$parent.getField("address_1").value = newValue;
      }
    },

    address2Value: {
      get: function() {
        return this.$parent.getField("address_2").value;
      },
      set: function(newValue) {
        this.$parent.getField("address_2").value = newValue;
      }
    }
  },
  watch: {
    selectedCommune: function(newCommunes) {
      let vm = this;
      axios
        .get("/districts/commune/" + newCommunes.id)
        .then(function(response) {
          vm.districts = response.data;
          vm.$parent.getField("district_id").data = {};
          vm.$refs.districtSelect.selected = {};
          vm.$parent.getField("district_id").validated = false;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    date: function(newDate, oldDate) {
      if (newDate !== "") {
        this.$parent.getField("be_done_at").validated = true;
      } else {
        this.$parent.getField("be_done_at").validated = false;
        this.$parent.getField("be_done_at").value = "";
      }
      this.$parent.isValidated(this.data);
    },

    address1Value: function(newAddress, oldAdress) {
      if (newAddress.trim().length > 1) {
        this.$parent.getField("address_1").validated = true;
      } else {
        this.$parent.getField("address_1").validated = false;
      }
      this.$parent.isValidated(this.data);
    },

    address2Value: function(newAddress, oldAdress) {
      if (newAddress.trim().length > 1) {
        this.$parent.getField("address_2").validated = true;
      } else {
        this.$parent.getField("address_2").validated = false;
      }
      this.$parent.isValidated(this.data);
    },

    postalValue: function(newPostal, oldPostal) {
      if (newPostal.trim().length > 1) {
        this.$parent.getField("postal").validated = true;
      } else {
        this.$parent.getField("postal").validated = false;
      }
      this.$parent.isValidated(this.data);
    }
  },

  mounted() {
    let vm = this;
    axios
      .get("/communes")
      .then(function(response) {
        vm.communes = response.data;
      })
      .catch(function(error) {
        console.log(error);
      });
  }
};
</script>


<style style="scss">
.vdpComponent.vdpWithInput > input {
  @apply w-full p-6  text-white border-2 border-blue-800 bg-blue-darken rounded text-base;
}
.vdpComponent.vdpWithInput > input:focus {
  @apply outline-none border-blue-600;
}
</style>