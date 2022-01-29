<template>
  <input-component
    id="email"
    label="Email"
    type="email"
  />
</template>

<script>
import InputComponent from '@/components/element/form/input';

export default {
  name: 'EmailInput',
  components: {
    InputComponent,
  },
};
</script>
