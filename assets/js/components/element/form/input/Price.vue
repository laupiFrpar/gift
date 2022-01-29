<template>
  <input-component
    id="price"
    label="price"
    type="number"
    :group="true"
    group-text="€"
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
