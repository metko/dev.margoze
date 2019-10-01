<template>
    <div >
         <div class="mb-6 text-white text-xl font-bold text-center">Connectez vous pour déposer votre candidature à Samuel</div>
         <div class="text-center"><a href="/login" 
            v-on:click.prevent="$root.openLoginModal"
            class="btn btn-white inline-block">Se connecter</a></div>
      </div> 
</template>

<script>
   export default {

   }
</script>