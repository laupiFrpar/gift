<template>
  <tr>
    <th
      v-for="(field, index) in fields"
      :key="index"
    >
      {{ field.label }}
    </th>
    <th>&nbsp;</th>
  </tr>
</template>

<script>
export default {
  name: 'TableHeader',
  props: {
    fields: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style>

</style>
