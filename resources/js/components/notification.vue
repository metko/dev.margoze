<template> 
      <div class="p-4 text-xs bg-gray-200 hover:bg-blue-100" v-bind:class="{ 'bg-gray-300' : ! readAt }">
         <a :href="urlAction" class="block">
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
