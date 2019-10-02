<template>
   <div class="w-full lg:w-3/4">
       <div type="text" 
            class="shadow-md rounded border border-gray-100 w-full   p-4">

            <BlinkInput v-if="!showInput" ></BlinkInput>
            <input v-else id="querySearch" v-model="query" 

            class="bg-red-100 h-7 flex items-center focus:outline-none w-full">
      </div>
   </div>
</template>


<script>
import BlinkInput from './BlinkInput.vue'
export default {
   components : {BlinkInput},
   created() {
     
     
   },
   data() {
      return {
         query : "",
         showInput: false
      }
   },
   events: {
      closeEvents() {
         alert('dffdf')
      }
   },
   methods: {
      inputFocus(){
         this.showInput = true
         setTimeout(this.setFocusOnInput, 1)
      },
      setFocusOnInput() {
         document.getElementById('querySearch').focus()
      },
      resetInput(){
         alert('dffd')
      }
   }
}

</script>
