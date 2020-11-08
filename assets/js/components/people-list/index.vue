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
          v-for="(people, index) in peoples"
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
              @click="remove(index)"
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
    <pagination
      :total-items="totalItems"
      @change-page="changePage"
    />
  </div>
</template>

<script>
import Pagination from '@/components/layout/ThePagination';

export default {
  name: 'PeopleList',
  components: {
    Pagination,
  },
  props: {
    peoples: {
      type: Array,
      required: true,
    },
    totalItems: {
      type: Number,
      required: true,
    },
  },
  methods: {
    changePage(page) {
      this.$emit('change-page', page);
    },
    edit(peopleId) {
      console.log(`Not implemented yet - edit ${peopleId}`);
    },
    remove(index) {
      this.$emit('remove-people', index);
    },
  },
};
</script>
