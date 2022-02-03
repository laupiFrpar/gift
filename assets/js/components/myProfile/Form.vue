<template>
  <form-component
    :error="error"
    @submitted="handleSubmit"
  >
    <email-input
      id="email-readonly"
      :value="user.email"
      :readonly="true"
    />
    <password-input
      :value="user.password"
      @updated-value="onUpdatedPassword"
    />
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
</template>

<script>
import axios from 'axios';

import FormComponent from '@/components/element/form';
import EmailInput from '@/components/element/form/input/Email';
import PasswordInput from '@/components/element/form/input/Password';
import SubmitButton from '@/components/element/button/Submit';
import TextInput from '@/components/element/form/input/Text';

export default {
  name: 'MyProfileForm',
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
    handleSubmit() {
      this.isLoading = true;
      axios
        .put(this.user['@id'], {
          password: this.user.password,
          // people: this.user.people,
        })
        .then(() => {
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
