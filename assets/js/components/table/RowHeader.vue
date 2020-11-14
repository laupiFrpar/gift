<template>
  <tr>
    <th
      v-for="(name, index) in columns"
      :key="index"
    >
      {{ name }}
    </th>
    <th>&nbsp;</th>
  </tr>
</template>

<script>
export default {
  name: 'RowHeader',
  props: {
    columns: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style>

</style>
