<template>
  <div id="modal-backdrop" class="modal-backdrop relative z-10" @click.prevent="close">

    <div class=" mx-auto px-6 flex items-center z-50 h-full" >
       <div class="modal__container">
         
          <slot></slot>
         
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'modal',

    methods: {
      close(event) {
         let id = event.target.id
         console.log(event.target)
         if(id == "modal-backdrop" || id == "btn-close"){
            this.$emit('close');
         }
      },
    },
  };
</script>


<style>
  .modal-backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .modal__container {
     @apply flex flex-col bg-white rounded w-full  mx-auto h-auto;
      max-width: 600px;
      max-height: 90%;
     
  }


  .modal-header,
  .modal-footer {
    padding: 150px;
    display: flex;
  }

  .modal-header {
    border-bottom: 1px solid #eeeeee;
    color: #4AAE9B;
    justify-content: space-between;
  }

  .modal-footer {
    border-top: 1px solid #eeeeee;
    justify-content: flex-end;
  }

  .modal-body {
    position: relative;
    padding: 20px 10px;
  }

  .btn-close {
    border: none;
    font-size: 20px;
    padding: 20px;
    cursor: pointer;
    font-weight: bold;
    color: #4AAE9B;
    background: transparent;
  }

  .btn-green {
    color: white;
    background: #4AAE9B;
    border: 1px solid #4AAE9B;
    border-radius: 2px;
  }
</style>