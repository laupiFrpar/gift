<template>
  <div class="table-responsive-sm">
    <table class="table table-hover table-sm">
      <thead>
        <row-header
          :columns="['First name', 'Last name']"
        />
      </thead>
      <tbody v-if="peoples.length">
        <row
          v-for="people in peoples"
          :key="people['@id']"
          :item="people"
          @edit-item="edit"
          @remove-item="remove"
        >
          <td>{{ people.firstName }}</td>
          <td>{{ people.lastName }}</td>
        </row>
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
import Row from '@/components/table/Row';
import RowHeader from '../table/RowHeader.vue';

export default {
  name: 'PeopleList',
  components: {
    Row,
    RowHeader,
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
