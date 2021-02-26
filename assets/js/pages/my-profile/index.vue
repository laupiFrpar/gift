<template>
  <div class="row justify-content-md-center">
    <div class="col-lg-6 mt-4">
      <h2 class="text-center">
        My profile
      </h2>
      <div class="text-center">
        <loading
          v-show="isLoading"
        />
      </div>
      <form
        v-show="!isLoading"
        class="border p-3 rounded"
        @submit.prevent="submit"
      >
        <div class="mb-3 row">
          <label
            for="staticEmail"
            class="col-sm-4 col-form-label"
          >Email</label>
          <div class="col-sm-8">
            <input
              id="staticEmail"
              type="text"
              readonly="readonly"
              class="form-control-plaintext"
              :value="user.email"
            >
          </div>
        </div>
        <div class="mb-3 row">
          <label
            for="inputPassword"
            class="col-sm-4 col-form-label"
          >
            Password
          </label>
          <div class="col-sm-8">
            <input
              id="inputPassword"
              v-model="user.password"
              type="password"
              class="form-control"
            >
          </div>
        </div>
        <div
          v-if="null === user.people"
          class="mb-3 row"
        >
          <label
            for="selectPeople"
            class="col-sm-4 col-form-label"
          >
            Linked with
          </label>
          <div
            class="col-sm-8"
          >
            <select
              id="selectPeople"
              class="form-select"
              aria-label="Default select example"
            >
              <option
                v-for="people in peoples"
                :key="people['@id']"
                :value="people['@id']"
              >
                {{ people.firstName }} {{ people.lastName }}
              </option>
            </select>
          </div>
        </div>
        <div class="mb-3 row">
          <label
            for="first-name"
            class="col-sm-4 form-label"
          >First name</label>
          <div class="col-sm-8">
            <input
              id="first-name"
              v-model="user.people.firstName"
              type="text"
              class="form-control"
              name="first-name"
            >
          </div>
        </div>
        <div class="mb-3 row">
          <label
            for="last-name"
            class="col-sm-4 form-label"
          >Last name</label>
          <div class="col-sm-8">
            <input
              id="last-name"
              v-model="user.people.lastName"
              type="text"
              class="form-control"
              name="last-name"
            >
          </div>
        </div>
        <div class="text-center">
          <button
            :class="{ disabled: isLoading }"
            type="submit"
            class="btn btn-primary"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { fetchPeoples } from '@/services/peoples-service.js';

import Loading from '@/components/loading';

export default {
  name: 'MyProfilePage',
  components: {
    Loading,
  },
  data() {
    return {
      isLoading: false,
      peoples: [],
      user: window.user,
    };
  },
  created() {
    this.loadPeoples(this.currentPage);
  },
  methods: {
    async loadPeoples() {
      let response;

      try {
        response = await fetchPeoples();
      } catch (e) {
        return;
      }

      this.peoples = response.data['hydra:member'];
    },
    submit() {
      this.isLoading = true;
      axios
        .put(this.user['@id'], {
          password: this.user.password,
        })
        .then(() => {
          this.isLoading = false;
          this.user.password = null;
        });
    },
  },
};
</script>
