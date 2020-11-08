<template>
  <div class="mt-4">
    <nav class="navbar">
      <button
        class="btn btn-primary"
        type="button"
        data-toggle="modal"
        data-target="#generic-modal"
      >
        Add
      </button>
    </nav>

    <people-list
      :peoples="peoples"
      :total-items="totalItems"
      @remove-people="remove"
      @change-page="changePage"
    />

    <!-- Modal -->
    <people-form-modal @submitted="updateList" />
  </div>
</template>

<script>
import axios from 'axios';
import { fetchPeoples } from '@/services/peoples-service';
import PeopleFormModal from '@/components/modal/form/People';
import PeopleList from '@/components/people-list';

export default {
  name: 'HomePeople',
  components: {
    PeopleFormModal,
    PeopleList,
  },
  data() {
    return {
      peoples: [],
      totalItems: 0,
    };
  },
  created() {
    this.loadPeoples(this.currentPage);
  },
  methods: {
    changePage(page) {
      this.loadPeoples(page);
    },
    // edit(peopleId) {
    //   axios.get(peopleId);
    // },
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
    },
    remove(index) {
      axios
        .delete(this.peoples[index]['@id'])
        .then(() => {
          this.peoples.splice(index, 1);
        });
    },
    updateList(people) {
      this.peoples.push(people);
    },
  },
};
</script>
