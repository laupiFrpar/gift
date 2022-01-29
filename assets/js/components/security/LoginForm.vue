<template>
  <form-component
    class="border bg-white rounded p-3"
    title="Gift"
    :error="error"
    @submitted="handleSubmit"
  >
    <email-input
      placeholder="Enter email"
      :inline="false"
      @updated-value="onUpdatedEmail"
    />
    <password-input
      placeholder="Password"
      :inline="false"
      @updated-value="onUpdatedPassword"
    />
    <div class="text-center">
      <submit-button
        :is-loading="isLoading"
        label-loading="Logging..."
      >
        Log In
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

export default {
  name: 'LoginForm',
  components: {
    FormComponent,
    EmailInput,
    PasswordInput,
    SubmitButton,
  },
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
    onUpdatedEmail(event) {
      this.email = event.value;
    },
    onUpdatedPassword(event) {
      this.password = event.value;
    },
  },
};
</script>
