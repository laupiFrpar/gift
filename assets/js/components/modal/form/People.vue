<template>
  <modal
    centered="true"
    confirm-text="Add"
    title="Add new user"
    @confirm="handleSubmit"
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
  </modal>
</template>

<script>
import Modal from '@/components/modal';

export default {
  name: 'PeopleFormModal',
  components: {
    Modal,
  },
  data() {
    return {
      people: {
        firstName: null,
        lastName: null,
      },
    };
  },
  methods: {
    handleSubmit() {
      this.$emit('submit', this.people);
    },
  },
};
</script>
