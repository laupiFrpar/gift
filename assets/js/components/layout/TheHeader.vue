<template>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <span class="navbar-brand h1">Gift</span>
        <ul
          v-if="user"
          class="navbar-nav mr-auto"
        >
          <li class="nav-item">
            <a
              :class="[activeClass('people'), 'nav-link']"
              href="/peoples"
            >Peoples</a>
          </li>
          <li class="nav-item">
            <a
              :class="[activeClass('gift'), 'nav-link']"
              href="/gifts"
            >Gifts</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li
            v-if="user"
            class="nav-item dropdown"
          >
            <a
              id="navbar-user-dropdown"
              class="nav-link dropdown-toggle"
              href="#"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >{{ user.email }}</a>
            <div
              class="dropdown-menu"
              aria-labelledby="navbar-user-dropdown"
            >
              <a
                href="/api/logout"
                class="dropdown-item"
              >Log out</a>
            </div>
          </li>
          <li
            v-else
            class="nav-item active"
          >
            <a
              class="btn btn-primary"
              href="/login"
            >Login</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
</template>

<script>
export default {
  name: 'HeaderLayout',
  data() {
    return {
      user: null,
    };
  },
  mounted() {
    if (window.user) {
      this.user = window.user;
    }
  },
  methods: {
    activeClass(pageName) {
      if (pageName === window.currentPageName) {
        return 'active';
      }

      return '';
    },
  },
};
</script>
