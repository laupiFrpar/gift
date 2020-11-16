<template>
  <tr>
    <td
      v-for="(field, index) in fields"
      :key="index"
    >
      {{ item[field.key] }}
    </td>
    <td>
      <button
        type="button"
        class="btn btn-primary"
        @click="edit()"
      >
        <i class="fas fa-edit" />
      </button>
      &nbsp;
      <button
        type="button"
        class="btn btn-danger"
        @click="remove()"
      >
        <i class="fas fa-trash" />
      </button>
    </td>
  </tr>
</template>

<script>
export default {
  name: 'TableRow',
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
