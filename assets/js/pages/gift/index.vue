<template>
  <div class="mt-4">
    <nav class="navbar">
      <button
        class="btn btn-primary"
        type="button"
        data-toggle="modal"
        data-target="#gift-form-modal"
      >
        Add
      </button>
    </nav>
    <gift-form-modal
      :gift-id="giftId"
      @created="created"
      @hide="hide"
      @updated="updated"
    />

    <alert-modal
      message="Are you sure to remove this gift ?"
      @confirm="remove"
    />

    <gift-list
      :gifts="gifts"
      @edit="edit"
      @remove="removalRequest"
    />

    <pagination-component
      :current-page="currentPage"
      :total-page="totalPage"
      @change-page="loadgifts"
    />
  </div>
</template>

<script>
import axios from 'axios';
import { Modal } from 'bootstrap';
import { fetchGifts } from '@/services/gifts-service';
import AlertModal from '@/components/modal/alert';
import PaginationComponent from '@/components/pagination';
import GiftFormModal from '@/components/gift/FormModal';
import GiftList from '@/components/gift/List';

export default {
  name: 'GiftPage',
  components: {
    AlertModal,
    PaginationComponent,
    GiftFormModal,
    GiftList,
  },
  data() {
    return {
      currentPage: 1,
      gifts: [],
      giftId: null,
      totalItems: 0,
    };
  },
  computed: {
    totalPage() {
      return Math.ceil(this.totalItems / window.lopiConfig.pagination.items_per_page);
    },
  },
  created() {
    this.loadgifts(this.currentPage);
  },
  methods: {
    async edit(giftId) {
      this.giftId = giftId;

      Modal
        .getInstance(document.getElementById('gift-form-modal'))
        .show();
    },
    async loadgifts(page) {
      // this.loading = true;
      let response;

      try {
        response = await fetchGifts(page);
        // this.loading = false;
      } catch (e) {
        // this.loading = false;

        return;
      }

      this.gifts = response.data['hydra:member'];
      this.totalItems = response.data['hydra:totalItems'];
      this.currentPage = page;

      if (this.totalPage < this.currentPage) {
        this.currentPage -= 1;
        this.loadgifts(this.currentPage);
      }
    },
    removalRequest(giftId) {
      this.giftId = giftId;
      Modal.getInstance(document.getElementById('alert-modal')).show();
    },
    remove() {
      axios
        .delete(this.giftId)
        .then(() => {
          this.loadgifts(this.currentPage);
          return true;
        })
        .catch(() => {
          // Do nothing
        })
        .then(() => {
          this.giftId = null;
        });
    },
    created() {
      this.loadgifts(this.currentPage);
    },
    updated() {
      this.loadgifts(this.currentPage);
    },
    hide() {
      this.giftId = null;
    },
  },
};
</script>
