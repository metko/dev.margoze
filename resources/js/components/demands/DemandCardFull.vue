<template>
<div class=" bg-white rounded overflow-hidden shadow-lg">
   <div class="max-w-sm w-full lg:max-w-full lg:flex">
      <div class="h-48 lg:h-auto lg:w-48 flex-none bg-cover rounded-t lg:rounded-t-none lg:rounded-l text-center overflow-hidden" 
            style="background-image: url('https://picsum.photos/id/1/200/300')" 
            title="Woman holding a mug">
      </div>
      <div class="  bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
        <div class="mb-8">
          <p class="text-sm text-gray-600 flex items-center">
            <svg class="fill-current text-gray-500 w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <path d="M4 8V6a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z" />
            </svg>
            Mis en ligne {{ demand.created }}
          </p>
          <div class="text-gray-900 font-bold text-xl">{{demand.title}}</div>
          <div  class="mb-2 text-indigo-600  text-normal">{{ demand.category.name }}</div>

          <div>
            <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{validFor}} jours restants </span>
            <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{ candidaturesCount}} Candidatures</span>
            <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{ demand.sector.name }}</span>
            <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{ demand.location }}</span>
         </div>
          <p class="text-gray-700 text-base mt-4">{{demand.content}}</p>
        </div>
        <div class="flex items-center">
          <img class="w-10 h-10 rounded-full mr-4" :src="demand.owner.avatar" alt="Avatar of Jonathan Reinink">
          <div class="text-sm">
            <p class="text-gray-900 leading-none">{{demand.owner.username}}</p>
            <p class="text-gray-600">{{demand.owner.status}}</p>
          </div>
        </div>
      </div>
    </div>
</div>
</template>

<script>
export default {
   props:['demand', 'candidaturesCount'],
   data () {
      return {
         validFor : ''
      }
   },
   mounted () {
      this.validFor = this.$parent.validFor
   },
   
}
</script>