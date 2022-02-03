<template>
  <div class="row justify-content-md-center">
    <div class="col-lg-6 mt-4">
      <h2 class="text-center">
        My profile
      </h2>
      <my-profile-form />
    </div>
  </div>
</template>

<script>
import MyProfileForm from '@/components/myProfile/Form';
// import { fetchPeoples } from '@/services/peoples-service.js';

export default {
  name: 'MyProfilePage',
  components: {
    MyProfileForm,
  },
  data() {
    return {
      // peoples: [],
    };
  },
  created() {
    // if (!this.user.people) {
    //   this.user.people = {
    //     firstName: null,
    //     lasteName: null,
    //   };
    // }
    // this.loadPeoples(this.currentPage);
  },
  methods: {
    // async loadPeoples() {
    //   let response;

    //   try {
    //     response = await fetchPeoples();
    //   } catch (e) {
    //     return;
    //   }

    //   this.peoples = response.data['hydra:member'];
    // },
  },
};
</script>
