<template>
  <base-modal
    id="alert-modal"
    :centered="true"
    confirm-button-text="Remove"
    confirm-button-type="danger"
  >
    {{ message }}
  </base-modal>
</template>

<script>
import BaseModal from '@/components/modal';

export default {
  name: 'AlertModal',
  components: {
    BaseModal,
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
