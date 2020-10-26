<template>
  <div
    id="security-login"
    class="row no-gutters justify-content-center"
  >
    <div class="col-4 align-self-center">
      <form-login
        @user-authenticated="onUserAuthenticated"
      />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import FormLogin from '@component/form/Login';

export default {
  name: 'SecurityLogin',
  components: {
    FormLogin,
  },
  methods: {
    onUserAuthenticated(userUri) {
      axios
        .get(userUri)
        .then(() => {
          // redirect to admin page
          document.location.href = '/admin';
        });
    },
  },
};
</script>
