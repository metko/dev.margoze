<transition name="fromRight">
   <div
      v-if="messagesTabIsOpen"  v-bind:class="{'block-imp': messagesTabIsOpen }" 
      class="absolute z-0 bg-white w-3/4 md:w-1/5 right-0 h-full" style="top:55px" >
         <ul class="px-4 bg-white text-right  ">
            <li class="">
               Messagerie
            </li>
         </ul>
   </div>
   </transition>