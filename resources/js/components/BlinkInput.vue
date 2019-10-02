<template>
   <div @click="setFocus" class="bg-red-100 h-7 flex items-center" v-click-outside="closeEvents">
      <span class="relative">
         <span class="text-gray-700">{{placeholder}}</span>
         <span class="text--blink"></span>
      </span>
   </div>

</template>

<script>
export default {
   created() {
      this.loopExamples()
   },
   data() {
      return {
         placeholder: "",
         examples: [
            'Faire du jardinage', 
            'Réparer une voiture', 
            'Cours particuliers', 
            'Peindre toute ma maison'],
         lettersCount : 0,
         speed: 70,
         reverse: false,
         currentIndex :  Math.floor(Math.random() * 4),
      }
   },
   directives: {
      'click-outside': {
        bind: function (el, binding, vnode) {
            el.clickOutsideEvent = function (event) {
               // here I check that click was outside the el and his childrens
               if (!(el == event.target || el.contains(event.target))) {
               // and if it did, call method provided in attribute value
               vnode.context[binding.expression](event);
               }
            };
            document.body.addEventListener('click', el.clickOutsideEvent)
         },
         unbind: function (el) {
            document.body.removeEventListener('click', el.clickOutsideEvent)
         },
      }
   },
   
   methods: {
      closeEvents(){
         //lert('fdfdf')
      },
      setFocus(){
         this.$parent.inputFocus()
      },

      loopExamples() {
         if(this.reverse){
            if(this.lettersCount == 0){
               this.reverse = false
            }
             setTimeout(this.removeChar, this.speed);
         }

         else if (this.lettersCount <= this.examples[this.currentIndex].length) {
            if(this.lettersCount == this.examples[this.currentIndex].length){
               this.reverse = true
            }
            setTimeout(this.addChar, this.speed);
         }

      },

      addChar() {
         
         this.placeholder += this.examples[this.currentIndex].charAt(this.lettersCount);
         this.lettersCount++;
         if(this.isLastLetter()) {
            this.speed = 2000
         }else{
            this.speed = 80
         }
         // this.firstOrLast() 
         this.loopExamples()
         
      },
      removeChar() {
         // this.firstOrLast() 
         this.lettersCount--;
         if(this.isFirstLetter()) {
            this.speed = 2000
            this.currentIndex = Math.floor(Math.random() * 4)
         }else{
            this.speed = 80
         }
         this.placeholder = this.placeholder.substring(0, this.placeholder.length - 1);
         
         this.loopExamples() 
      },
     
      isFirstLetter() {
         if(this.lettersCount == 0){
            return true
         }
      },
      isLastLetter() {
         if(this.lettersCount == this.examples[this.currentIndex].length  ){
            //alert('fd')
            return true
         }
      },
   }
}
</script>


<style lang="scss" scoped>
   .text--blink{
      @apply bg-gray-700 rounded;
      width: 1px;
      height: 23px;
      display: inline-block;
      position: absolute;
      right: -5px;
      top: 50%;
      transform: translateY(-50%);
      -webkit-animation-name: blink; /* Safari 4.0 - 8.0 */
      -webkit-animation-duration: .7s; /* Safari 4.0 - 8.0 */
      -webkit-animation-iteration-count: infinite;
      animation-name: blink;
      animation-duration: .7s;
      animation-iteration-count: infinite;
   }

   /* Safari 4.0 - 8.0 */
@-webkit-keyframes blink {
  0% {opacity: 1}
  70% {opacity: 0}
  100% {opacity: 1}
}

/* Standard syntax */
@keyframes blink {
  0% {opacity: 1}
  70% {opacity: 0}
  100% {opacity: 1}
}
</style>
