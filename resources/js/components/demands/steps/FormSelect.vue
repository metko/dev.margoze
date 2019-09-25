<template>
   <div class="relative w-full ">
      <div class="cursor-pointer" @click="openList()">
         <input  :class="selectClass" :placeholder="emptyPLaceholder" :value="selectedName" disabled>
      </div>
      <ul v-if="showList" class="absolute top-0 left-0 w-full bg-white shadow rounded z-10 max-h-48 overflow-y-scroll" >
         <li 
         @click="selectItem(item)"
         v-for="(item) in this.data" v-bind:key="item.id"
         class="px-2 py-5 cursor-pointer border-b border-gray-200 hover:bg-gray-100">{{item.name}}</li>
      </ul>
      <input type="hidden" :name="name" :value='selected.id'>
   </div>
</template>

<script>
   export default {
      data(){
         return{
            selected: "",
            showList : false,
            dataList : this.data,
            emptyPLaceholder: this.placeholder,
            defauldSelected: this.oldSelected
         }
      },

      mounted() {
         if(this.$parent.$parent.getField(this.name).data){
            this.selected = this.$parent.$parent.getField(this.name).data
         }
      },

      props: ['selectClass', 'name', 'placeholder', 'data', 'oldSelected'],
      
      computed: {
         selectedName: function(){
            if(this.selected.name){
               return this.selected.name
            }
            return ""
         }
      },
      watch: {
         dataList: function(){
            console.log('changed')
         }
      },
      methods: {
         openList: function(){
           this.showList= true
         },
         selectItem: function(item) {
            this.selected = item
            this.showList = false
            this.$parent.$parent.getField(this.name).data = item 
            this.$parent.$parent.getField(this.name).validated = true 
         },
      
      }
   }
</script>