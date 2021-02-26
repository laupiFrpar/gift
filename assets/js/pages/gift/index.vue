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

    <table-component
      empty-message="No gift"
      :fields="fields"
      :items="items"
      :loading="loading"
      @edit-item="edit"
      @remove-item="removalRequest"
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
import formatPrice from '@/helpers/format-price.js';
import AlertModal from '@/components/modal/Alert';
import PaginationComponent from '@/components/pagination';
import TableComponent from '@/components/table';
import GiftFormModal from '@/components/gift/FormModal';

export default {
  name: 'GiftPage',
  components: {
    AlertModal,
    PaginationComponent,
    TableComponent,
    GiftFormModal,
  },
  data() {
    return {
      currentPage: 1,
      gifts: [],
      giftId: null,
      loading: false,
      totalItems: 0,
      fields: [
        {
          key: 'title',
          label: 'Label',
        },
        {
          key: 'formattedPrice',
          label: 'Price',
        },
      ],
    };
  },
  computed: {
    totalPage() {
      return Math.ceil(this.totalItems / window.lopiConfig.pagination.items_per_page);
    },
    items() {
      return this.gifts.map(
        (gift) => {
          gift.formattedPrice = this.price(gift.price);

          return gift;
        },
      );
    },
  },
  created() {
    this.loadgifts(this.currentPage);
  },
  methods: {
    edit(giftId) {
      this.giftId = giftId;

      Modal
        .getInstance(document.getElementById('gift-form-modal'))
        .show();
    },
    async loadgifts(page) {
      this.loading = true;
      let response;

      try {
        response = await fetchGifts(page);
        this.loading = false;
      } catch (e) {
        this.loading = false;

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
    price(price) {
      return formatPrice(price);
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
