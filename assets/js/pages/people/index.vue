<template>
  <div class="mt-4">
    <nav class="navbar">
      <button
        class="btn btn-primary"
        type="button"
        data-toggle="modal"
        data-target="#add-new-people-modal"
      >
        Add
      </button>
    </nav>
    <table class="table table-hover">
      <thead class="thead-light">
        <th scope="col">
          First name
        </th>
        <th scope="col">
          Last name
        </th>
        <th scope="col" />
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
              Edit
            </button>
            <button
              type="button"
              class="btn btn-danger"
              @click="remove(index)"
            >
              Delete
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
    <div
      id="add-new-people-modal"
      class="modal fade"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      aria-labelledby="addNewUserModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <form @submit.prevent="handleSubmit">
          <div class="modal-content">
            <div class="modal-header">
              <h5
                id="addNewUserModalLabel"
                class="modal-title"
              >
                Add new user
              </h5>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label
                  for="first-name"
                  class="col-form-label"
                >
                  First name
                </label>
                <input
                  id="first-name"
                  v-model="peopleForm.firstName"
                  type="text"
                  name="first-name"
                  class="form-control"
                >
              </div>
              <div class="form-group">
                <label
                  for="last-name"
                  class="col-form-label"
                >
                  Last name
                </label>
                <input
                  id="last-name"
                  v-model="peopleForm.lastName"
                  type="text"
                  name="last-name"
                  class="form-control"
                >
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                Close
              </button>
              <button
                type="submit"
                class="btn btn-primary"
              >
                Create
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { fetchPeoples } from '@/services/peoples-service';
import axios from 'axios';

export default {
  name: 'HomePeople',
  data() {
    return {
      peoples: [],
      peopleForm: {
        firstName: null,
        lastName: null,
      },
    };
  },
  async created() {
    const response = await fetchPeoples();

    this.peoples = response.data['hydra:member'];
    console.log(this.peoples);
  },
  methods: {
    handleSubmit() {
      return axios
        .post('/api/peoples', this.peopleForm)
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
