<template>
  <div class="col-lg-4">
    <form-login
      @user-authenticated="onUserAuthenticated"
    />
  </div>
</template>

<script>
import axios from 'axios';
import FormLogin from '@/component/form/Login';

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
          document.location.href = '/';
        });
    },
  },
};
</script>
