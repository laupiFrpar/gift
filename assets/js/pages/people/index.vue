<template>
  <div class="mt-4">
    <nav class="navbar">
      <button
        class="btn btn-primary"
        type="button"
        data-toggle="modal"
        data-target="#generic-modal"
      >
        Add
      </button>
    </nav>
    <table class="table table-hover">
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
              @click="edit(people['@id'], index)"
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

    <!-- Modal -->
    <people-form-modal @submitted="updateList" />
  </div>
</template>

<script>
import { fetchPeoples } from '@/services/peoples-service';
import axios from 'axios';
import PeopleFormModal from '@/components/modal/form/People';

export default {
  name: 'HomePeople',
  components: {
    PeopleFormModal,
  },
  data() {
    return {
      peoples: [],
      // peopleModel: {
      //   firstName: null,
      //   lastName: null,
      // },
    };
  },
  async created() {
    const response = await fetchPeoples();

    this.peoples = response.data['hydra:member'];
  },
  methods: {
    updateList(people) {
      this.peoples.push(people);
    },
    remove(index) {
      axios
        .delete(this.peoples[index]['@id'])
        .then(() => {
          this.peoples.splice(index, 1);
        });
    },
    edit(peopleId) {
      axios.get(peopleId);
    },
  },
};
</script>
