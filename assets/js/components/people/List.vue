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
        <row
          v-for="people in peoples"
          :key="people['@id']"
          :people="people"
          @edit-people="edit"
          @remove-people="remove"
        />
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
import Row from '@/components/people/Row';

export default {
  name: 'PeopleList',
  components: {
    Row,
  },
  props: {
    peoples: {
      type: Array,
      required: true,
    },
  },
  methods: {
    edit(peopleId) {
      this.$emit('edit', peopleId);
    },
    remove(peopleId) {
      this.$emit('remove', peopleId);
    },
  },
};
</script>
