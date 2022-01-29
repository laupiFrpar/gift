<template>
  <div class="col-lg-4">
    <login-form
      @user-authenticated="onUserAuthenticated"
    />
  </div>
</template>

<script>
import axios from 'axios';
import LoginForm from '@/components/security/LoginForm';

export default {
  name: 'LoginPage',
  components: {
    LoginForm,
  },
  methods: {
    onUserAuthenticated(userUri) {
      axios
        .get(userUri)
        .then(() => {
          // redirect to admin page
          document.location.href = '/';
        });
    },
  },
};
</script>
