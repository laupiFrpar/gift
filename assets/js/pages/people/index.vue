<template>
  <div class="mt-4">
    <nav class="navbar">
      <button
        class="btn btn-primary"
        type="button"
        data-toggle="modal"
        data-target="#people-form-modal"
      >
        Add
      </button>
    </nav>
    <people-form-modal
      :people-id="peopleId"
      @created="created"
      @hide="hide"
      @updated="updated"
    />

    <people-list
      :peoples="peoples"
      @edit="edit"
      @remove="remove"
    />

    <pagination-component
      :current-page="currentPage"
      :total-page="totalPage"
      @change-page="loadPeoples"
    />
  </div>
</template>

<script>
import axios from 'axios';
import { Modal } from 'bootstrap';
import { fetchPeoples } from '@/services/peoples-service';
import PeopleFormModal from '@/components/modal/form/People';
import PeopleList from '@/components/people-list';
import PaginationComponent from '@/components/pagination';

export default {
  name: 'PeoplePage',
  components: {
    PaginationComponent,
    PeopleFormModal,
    PeopleList,
  },
  data() {
    return {
      currentPage: 1,
      peoples: [],
      peopleId: null,
      totalItems: 0,
    };
  },
  computed: {
    totalPage() {
      return Math.ceil(this.totalItems / window.lopiConfig.pagination.items_per_page);
    },
  },
  created() {
    this.loadPeoples(this.currentPage);
  },
  methods: {
    async edit(peopleId) {
      this.peopleId = peopleId;

      Modal
        .getInstance(document.getElementById('people-form-modal'))
        .show();
    },
    async loadPeoples(page) {
      // this.loading = true;
      let response;

      try {
        response = await fetchPeoples(page);
        // this.loading = false;
      } catch (e) {
        // this.loading = false;

        return;
      }

      this.peoples = response.data['hydra:member'];
      this.totalItems = response.data['hydra:totalItems'];
      this.currentPage = page;

      if (this.totalPage < this.currentPage) {
        this.currentPage -= 1;
        this.loadPeoples(this.currentPage);
      }
    },
    remove(peopleId) {
      axios
        .delete(peopleId)
        .then(() => {
          this.loadPeoples(this.currentPage);
        });
    },
    created() {
      this.loadPeoples(this.currentPage);
    },
    updated() {
      this.loadPeoples(this.currentPage);
      // this.peopleId = null;
    },
    hide() {
      this.peopleId = null;
    },
  },
};
</script>
