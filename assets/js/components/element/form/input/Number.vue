<template>
  <input-component
    id="number"
    label="Number"
    type="number"
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
