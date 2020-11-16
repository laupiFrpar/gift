<template>
  <div class="table-responsive-sm">
    <table class="table table-hover table-sm">
      <thead>
        <table-header
          :headers="['First name', 'Last name']"
        />
      </thead>
      <tbody v-if="peoples.length">
        <table-row
          v-for="people in peoples"
          :key="people['@id']"
          :item="people"
          @edit-item="edit"
          @remove-item="remove"
        >
          <td>{{ people.firstName }}</td>
          <td>{{ people.lastName }}</td>
        </table-row>
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
import TableHeader from '@/components/table/TableHeader.vue';
import TableRow from '@/components/table/TableRow';

export default {
  name: 'PeopleList',
  components: {
    TableHeader,
    TableRow,
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
