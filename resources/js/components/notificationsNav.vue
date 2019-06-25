<template> 
   <div>
     <a class="font-light text-gray-800 px-3 py-1"  @click.prevent="openNotifications"  href="#">
         Notifications <span class="absolute top-0 right-0 block w-4 h-4 text-xs text-center rounded-full bg-red-600 text-white" v-if="unreadCount != 0" >{{ unreadCount }} </span>
      </a>
      <ul class="absolute  right-0" style="top:40px; width: 300px" v-if="isOpen">
         <li class="p-2 text-xs bg-gray-400 text-center bg-gray-700">
                  <a class="block text-white " href="#">
                     Tout marquer comme lu
                  </a>
            </li>
            <notification v-for="(notification, index) in notifications" v-bind:key="notification.id"
               :index="index"
               :message="notification.data.message"
               :readAt="notification.read_at"
               :urlAction="notification.data.action_url"
               :notification="notification"></notification>

         <li v-if="! notifications.length" class="p-4 text-xs bg-gray-200 text-center">
               Chargement...
         </li>
         <li class="p-1 text-xs bg-black text-center ">
               <a class="block text-white " href="#">
                  Supprimer les notifications
               </a>
         </li>
      </ul>
   </div>
</template>

<script>
export default {
   props: ['user'],
   data(){
      return {
         isOpen : false,
         notifications : [],
         unreadCount : 0
      }
   },
   mounted(){
      this.loadNotification();
      let vm = this;
      console.log(this.user.id);
      Echo.private('users.'+this.user.id)
            .notification((notification) => {
                
                let newNotification = {
                  data : {
                     message: notification.message,
                     action_url: notification.action_url,
                  },
                  id: notification.id,
                  read_at: null
                }
                Array.prototype.unshift.call(vm.notifications, newNotification);
                vm.unreadCount++;
            });
      
   },
   methods: {
      openNotifications(){
         this.isOpen = !this.isOpen;
         if(this.unreadCount >= 1){
            this.markAsRead()
            
         }
         let newNotification = {
                  data : {
                     message: "A message",
                     action_url: "notification.action_url",
                  },
                  id: 1223434,
                  read_at: null
                } 
      },
      markAsRead() {
         let vm = this;
         axios.post('/users/notifications/read')
            .then(function(response) {
               vm.unreadCount = 0;
            })
            .catch(function(error) {
               console.log(error);
            })
      },
      loadNotification(){
         let vm = this;
         axios.get('/users/notifications')
            .then(function(response) {
               if(response.data.notifications[0]){
                  vm.notifications = response.data.notifications;
                  vm.unreadCount = response.data.unreadcount;
               }
            })
            .catch(function(error) {
               console.log(error);
         })
      }
   }
}
</script>
