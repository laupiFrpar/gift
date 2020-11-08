<template>
  <div class="table-responsive-sm">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col">
            First name
          </th>
          <th scope="col">
            Last name
          </th>
          <th scope="col" />
        </tr>
      </thead>
      <tbody v-if="peoples.length">
        <tr
          v-for="(people) in peoples"
          :key="people['@id']"
        >
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
      </tbody>
      <tbody v-else>
        <tr class="text-center">
          <td colspan="3">
            No people registered
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: 'PeopleList',
  props: {
    peoples: {
      type: Array,
      required: true,
    },
  },
  methods: {
    edit(peopleId) {
      this.$emit('edit-people', peopleId);
    },
    remove(peopleId) {
      this.$emit('remove-people', peopleId);
    },
  },
};
</script>
