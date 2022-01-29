<template>
  <input-component
    id="password"
    label="Password"
    type="password"
  />
</template>

<script>
import InputComponent from '@/components/element/form/input';

export default {
  name: 'PasswordInput',
  components: {
    InputComponent,
  },
};
</script>
