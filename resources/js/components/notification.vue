<template> 
      <div class="p-4 text-xs bg-white hover:bg-gray-100" v-bind:class="{ 'bg-gray-100' : ! readAt }">
         <a :href="urlAction" class="block text-xs bg-white">
             <div v-html="message"></div>
         </a>
      </div>  
</template>

<script>
export default {
   props: ['notification', 'message', 'readAt', 'index', 'urlAction'],  
   mounted(){
      //console.log(this.notification);  
   },
   methods: {
   }
}
</script>
