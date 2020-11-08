<template>
  <nav
    v-if="1 < totalPage"
    aria-label="pagination"
  >
    <ul class="pagination justify-content-center">
      <li
        :class="{
          'page-item': true,
          'disabled': currentPage === 1
        }"
      >
        <a
          href="#"
          class="page-link"
          tab-index="-1"
          @click.prevent="changePage(currentPage - 1)"
        >Previous</a>
      </li>
      <li
        v-for="page in totalPage"
        :key="page"
        :class="{
          'page-item': true,
          'active': currentPage === page,
        }"
      >
        <a
          href="#"
          class="page-link"
          @click.prevent="changePage(page)"
        >{{ page }}</a>
      </li>
      <li
        :class="{
          'page-item': true,
          'disabled': currentPage === totalPage,
        }"
      >
        <a
          href="#"
          class="page-link"
          @click.prevent="changePage(currentPage + 1)"
        >Next</a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: 'PaginationComponent',
  props: {
    currentPage: {
      type: Number,
      default: 1,
    },
    totalPage: {
      type: Number,
      required: true,
    },
  },
  methods: {
    changePage(page) {
      this.$emit('change-page', page);
    },
  },
};
</script>
