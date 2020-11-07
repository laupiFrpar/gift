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
    <modal
      centered="true"
      confirm-text="Add"
      title="Add new user"
      @confirm="handleSubmit"
    >
      <form>
        <div class="mb-3">
          <label
            for="first-name"
            class="form-label"
          >First name</label>
          <input
            id="first-name"
            v-model="peopleModel.firstName"
            type="text"
            class="form-control"
            name="first-name"
          >
        </div>
        <div class="mb-3">
          <label
            for="last-name"
            class="form-label"
          >Last name</label>
          <input
            id="last-name"
            v-model="peopleModel.lastName"
            type="text"
            class="form-control"
            name="last-name"
          >
        </div>
      </form>
    </modal>
  </div>
</template>

<script>
import { fetchPeoples } from '@/services/peoples-service';
import axios from 'axios';
import Modal from '@/components/modal';

export default {
  name: 'HomePeople',
  components: {
    Modal,
  },
  data() {
    return {
      peoples: [],
      peopleModel: {
        firstName: null,
        lastName: null,
      },
    };
  },
  async created() {
    const response = await fetchPeoples();

    this.peoples = response.data['hydra:member'];
  },
  methods: {
    handleSubmit() {
      return axios
        .post('/api/peoples', this.peopleModel)
        .then((response) => {
          this.peoples.push(response.data);

          return true;
        });
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
