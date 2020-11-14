<template>
  <tr>
    <td>{{ people.firstName }}</td>
    <td>{{ people.lastName }}</td>
    <td>
      <button
        type="button"
        class="btn btn-primary"
        @click="edit(people['@id'])"
      >
        <i class="fas fa-edit" />
      </button>
      &nbsp;
      <button
        type="button"
        class="btn btn-danger"
        @click="remove(people['@id'])"
      >
        <i class="fas fa-trash" />
      </button>
    </td>
  </tr>
</template>

<script>
export default {
  name: 'RowPeople',
  props: {
    people: {
      type: Array,
      required: true,
    },
  },
  methods: {
    edit(id) {
      this.$emit('edit-people', id);
    },
    remove(id) {
      this.$emit('remove-people', id);
    },
  },
};
</script>

<style>

</style>
