<template>
  <modal-component
    id="people-form-modal"
    :centered="true"
    :confirm-text="buttonText"
    :title="titleText"
    @confirm="confirm"
    @hide="hide"
    @shown="shown"
  >
    <form-component>
      <text-input
        id="first-name"
        label="First name"
        :value="people.firstName"
        :inline="false"
        @updated-value="onUpdatedFirstName"
      />
      <text-input
        id="last-name"
        label="Last name"
        :value="people.lastName"
        :inline="false"
        @updated-value="onUpdatedLastName"
      />
    </form-component>
  </modal-component>
</template>

<script>
import axios from 'axios';
import { fetchPeople } from '@/services/peoples-service.js';
import FormComponent from '@/components/element/form';
import TextInput from '@/components/element/form/input/Text';
import ModalComponent from '@/components/modal';

export default {
  name: 'PeopleModalForm',
  components: {
    FormComponent,
    TextInput,
    ModalComponent,
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
      console.log('people modal hide');
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
    onUpdatedFirstName(event) {
      this.people.firstName = event.value;
    },
    onUpdatedLastName(event) {
      this.people.lastName = event.value;
    },
  },
};
</script>
