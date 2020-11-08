<template>
  <form
    class="border bg-white rounded p-3"
    @submit.prevent="handleSubmit"
  >
    <h2 class="text-center">
      Gift
    </h2>
    <div
      v-if="error"
      class="alert alert-danger"
      role="alert"
    >
      {{ error }}
    </div>
    <div class="mb-3">
      <label
        for="email"
        class="form-label"
      >Email address</label>
      <input
        id="email"
        v-model="email"
        type="email"
        class="form-control"
        placeholder="Enter email"
      >
    </div>
    <div class="mb-3">
      <label
        for="password"
        class="form-label"
      >Password</label>
      <input
        id="password"
        v-model="password"
        type="password"
        class="form-control"
        placeholder="Password"
      >
    </div>
    <div class="text-center">
      <button
        :class="{ disabled: isLoading }"
        type="submit"
        class="btn btn-primary"
      >
        Log in
      </button>
    </div>
  </form>
</template>

<script>
import axios from 'axios';

export default {
  name: 'LoginForm',
  props: {
    user: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      email: '',
      password: '',
      error: '',
      isLoading: false,
    };
  },
  methods: {
    handleSubmit() {
      this.isLoading = true;
      this.error = '';
      axios
        .post('/api/login', {
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          this.$emit('user-authenticated', response.headers.location);
          this.email = '';
          this.password = '';
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
