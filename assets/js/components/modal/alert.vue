<template>
  <modal-component
    id="alert-modal"
    :centered="true"
    confirm-button-text="Remove"
    confirm-button-type="danger"
  >
    {{ message }}
  </modal-component>
</template>

<script>
import ModalComponent from '@/components/modal';

export default {
  name: 'AlertModal',
  components: {
    ModalComponent,
  },
  props: {
    message: {
      type: String,
      required: true,
    },
  },
  methods: {
    confirm() {
      this.$emit('confirm');
    },
  },
};
</script>
