<template>
  <div class="mt-4">
    <nav class="navbar">
      <button-component
        data-bs-toggle="modal"
        data-bs-target="#people-form-modal"
      >
        Add
      </button-component>
    </nav>

    <people-modal-form
      :people-id="peopleId"
      @created="created"
      @hide="hide"
      @updated="updated"
    />

    <alert-modal
      message="Are you sure to remove this people ?"
      @confirm="remove"
    />

    <table-component
      empty-message="No people"
      :fields="fields"
      :items="peoples"
      :loading="loading"
      @edit-item="edit"
      @remove-item="removalRequest"
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

import AlertModal from '@/components/modal/Alert';
import ButtonComponent from '@/components/element/button';
import PaginationComponent from '@/components/pagination';
import PeopleModalForm from '@/components/peoples/ModalForm';
import TableComponent from '@/components/element/table';

import { fetchPeoples } from '@/services/peoples-service';

export default {
  name: 'PeoplePage',
  components: {
    AlertModal,
    ButtonComponent,
    PaginationComponent,
    PeopleModalForm,
    TableComponent,
  },
  data() {
    return {
      currentPage: 1,
      fields: [
        {
          key: 'firstName',
          label: 'First name',
        },
        {
          key: 'lastName',
          label: 'Last name',
        },
      ],
      loading: false,
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
      this.loading = true;
      let response;

      try {
        response = await fetchPeoples(page);
        this.loading = false;
      } catch (e) {
        this.loading = false;

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
    removalRequest(peopleId) {
      this.peopleId = peopleId;
      Modal.getInstance(document.getElementById('alert-modal')).show();
    },
    remove() {
      axios
        .delete(this.peopleId)
        .then(() => {
          this.loadPeoples(this.currentPage);
          return true;
        })
        .catch(() => {
          // Do nothing
        })
        .then(() => {
          this.peopleId = null;
        });
    },
    created() {
      this.loadPeoples(this.currentPage);
    },
    updated() {
      this.loadPeoples(this.currentPage);
    },
    hide() {
      this.peopleId = null;
    },
  },
};
</script>
