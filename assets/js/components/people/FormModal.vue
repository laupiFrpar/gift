<template>
  <base-modal
    id="people-form-modal"
    :centered="true"
    :confirm-text="buttonText"
    :title="titleText"
    @confirm="confirm"
    @hide="hide"
    @shown="shown"
  >
    <form>
      <div class="mb-3">
        <label
          for="first-name"
          class="form-label"
        >First name</label>
        <input
          id="first-name"
          v-model="people.firstName"
          type="text"
          class="form-control"
          name="first-name"
        >
      </div>
      <div class="mb-3">
        <label
          for="last-name"
          class="form-label"
        >Last name</label>
        <input
          id="last-name"
          v-model="people.lastName"
          type="text"
          class="form-control"
          name="last-name"
        >
      </div>
    </form>
  </base-modal>
</template>

<script>
import axios from 'axios';
import { fetchPeople } from '@/services/peoples-service.js';
import BaseModal from '@/components/modal';

export default {
  name: 'PeopleFormModal',
  components: {
    BaseModal,
  },
  props: {
    peopleId: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      people: {
        firstName: null,
        lastName: null,
      },
    };
  },
  computed: {
    titleText() {
      if (this.peopleId) {
        return 'Edit an user';
      }

      return 'Add a new user';
    },
    buttonText() {
      if (this.peopleId) {
        return 'Edit';
      }

      return 'Add';
    },
  },
  methods: {
    confirm() {
      if (this.peopleId) {
        axios.put(this.peopleId, this.people)
          .then(() => {
            this.$emit('updated');
          });
      } else {
        axios
          .post('/api/peoples', this.people)
          .then(() => {
            this.$emit('created');
          });
      }
    },
    hide() {
      this.people = {
        firstName: null,
        lastName: null,
      };
      this.$emit('hide');
    },
    async shown() {
      if (this.peopleId) {
        const response = await fetchPeople(this.peopleId);
        this.people = response.data;
      }
    },
  },
};
</script>
