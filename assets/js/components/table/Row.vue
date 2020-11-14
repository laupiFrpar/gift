<template>
  <tr>
    <slot>
      <td
        v-for="(value, name, index) in item"
        :key="index"
      >
        {{ value }}
      </td>
    </slot>
    <slot name="action">
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
    </slot>
  </tr>
</template>

<script>
export default {
  name: 'Row',
  props: {
    item: {
      type: Object,
      required: true,
    },
  },
  methods: {
    edit() {
      this.$emit('edit-item', this.item['@id']);
    },
    remove() {
      this.$emit('remove-item', this.item['@id']);
    },
  },
};
</script>

<style>

</style>
