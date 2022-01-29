<template>
  <div class="row justify-content-md-center">
    <div class="col-lg-6 mt-4">
      <h2 class="text-center">
        My profile
      </h2>
      <form-component
        :error="error"
        @submitted="handleSubmit"
      >
        <email-input
          id="email-readonly"
          :value="user.email"
          :readonly="true"
        />
        <password-input @updated-value="onUpdatedPassword" />
        <!-- <div
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
        </div> -->
        <text-input
          id="firstname"
          label="First name"
          placeholder="Tony"
          @updated-value="onUpdatedFirstNameInput"
        />
        <text-input
          id="lastname"
          label="Last name"
          placeholder="Stark"
          @updated-value="onUpdatedLastNameInput"
        />
        <div class="text-center">
          <submit-button :is-loading="isLoading">
            Save
          </submit-button>
        </div>
      </form-component>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

import EmailInput from '@/components/element/form/input/Email';
import PasswordInput from '@/components/element/form/input/Password';
import TextInput from '@/components/element/form/input/Text';
import SubmitButton from '@/components/element/button/Submit';
import FormComponent from '@/components/element/form';
// import { fetchPeoples } from '@/services/peoples-service.js';

export default {
  name: 'MyProfilePage',
  components: {
    FormComponent,
    EmailInput,
    PasswordInput,
    SubmitButton,
    TextInput,
  },
  data() {
    return {
      isLoading: false,
      // peoples: [],
      user: window.user,
    };
  },
  created() {
    // if (!this.user.people) {
    //   this.user.people = {
    //     firstName: null,
    //     lasteName: null,
    //   };
    // }
    // this.loadPeoples(this.currentPage);
  },
  methods: {
    onUpdatedPasswordInput(event) {
      this.user.password = event.value;
    },
    onUpdatedFirstNameInput(event) {
      this.user.people.firstName = event.value;
    },
    onUpdatedLastNameInput(event) {
      this.user.people.lastName = event.value;
    },
    // async loadPeoples() {
    //   let response;

    //   try {
    //     response = await fetchPeoples();
    //   } catch (e) {
    //     return;
    //   }

    //   this.peoples = response.data['hydra:member'];
    // },
    handleSubmit() {
      this.isLoading = true;
      axios
        .put(this.user['@id'], {
          password: this.user.password,
          people: this.user.people,
        })
        .then(() => {
          this.isLoading = false;
          this.user.password = null;
        }).catch((error) => {
          if (error.response.data.error) {
            this.error = error.response.data.error;
          } else {
            this.error = 'Unknown error';
          }
        }).finally(() => {
          this.isLoading = false;
        });
    },
  },
};
</script>
