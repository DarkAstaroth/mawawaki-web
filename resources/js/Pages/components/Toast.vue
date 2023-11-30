<template>
  <Toast :position="position" :group="group" :autoZIndex="autoZIndex" />
</template>
  
  <script>
import { ref, provide, inject } from "vue";
import { useToast } from "primevue/usetoast";

export default {
  setup() {
    // const toast = ref(null);
    const toast = useToast();
    console.log("TOAST", toast);

    provide("toast", toast);

    return {
      toast,
    };
  },
};
</script>
  