<template>
  <button-component
    type="submit"
    :class="{'disabled': isLoading }"
  >
    <span
      v-show="isLoading"
      class="spinner-border spinner-border-sm"
      role="status"
      aria-hidden="true"
    />
    <span
      v-show="isLoading"
      class="visually-hidden"
    >&nbsp;{{ labelLoading }}</span>
    <span v-show="!isLoading">
      <slot />
    </span>
  </button-component>
</template>

<script>
import ButtonComponent from '@/components/element/button';

export default {
  name: 'SubmitButton',
  components: {
    ButtonComponent,
  },
  props: {
    isLoading: {
      type: Boolean,
      default: false,
    },
    labelLoading: {
      type: String,
      default: 'Loading...',
    },
  },
};
</script>
