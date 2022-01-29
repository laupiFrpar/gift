<template>
  <form @submit.prevent="handleSubmit">
    <h2
      v-if="title"
      class="text-center"
    >
      {{ title }}
    </h2>
    <div
      v-if="error"
      class="alert alert-danger"
      role="alert"
    >
      {{ error }}
    </div>
    <slot />
  </form>
</template>

<script>
export default {
  name: 'FormComponent',
  props: {
    error: {
      type: String,
      default: '',
    },
    title: {
      type: String,
      default: '',
    },
  },
  methods: {
    handleSubmit() {
      this.$emit('submitted');
    },
  },
};
</script>
