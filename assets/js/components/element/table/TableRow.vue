<template>
  <tr>
    <td
      v-for="(field, index) in fields"
      :key="index"
    >
      {{ item[field.key] }}
    </td>
    <td>
      <button-component @click="edit()">
        <i class="fas fa-edit" />
      </button-component>
      &nbsp;
      <button-component
        variant="danger"
        @click="remove()"
      >
        <i class="fas fa-trash" />
      </button-component>
    </td>
  </tr>
</template>

<script>
import ButtonComponent from '@/components/element/button';

export default {
  name: 'TableRow',
  components: {
    ButtonComponent,
  },
  props: {
    fields: {
      type: Array,
      required: true,
    },
    fieldKey: {
      type: String,
      default: '@id',
    },
    item: {
      type: Object,
      required: true,
    },
  },
  methods: {
    edit() {
      this.$emit('edit-item', this.item[this.fieldKey]);
    },
    remove() {
      this.$emit('remove-item', this.item[this.fieldKey]);
    },
  },
};
</script>
