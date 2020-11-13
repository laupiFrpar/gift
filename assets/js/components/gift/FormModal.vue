<template>
  <base-modal
    id="gift-form-modal"
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
          for="label"
          class="form-label"
        >Label</label>
        <input
          id="label"
          v-model="gift.title"
          type="text"
          class="form-control"
          name="label"
        >
      </div>
      <div class="mb-3">
        <label
          for="price"
          class="form-label"
        >Price</label>
        <div class="input-group">
          <span class="input-group-text">€</span>
          <input
            id="price"
            v-model="gift.price"
            type="number"
            class="form-control"
            name="price"
          >
        </div>
      </div>
    </form>
  </base-modal>
</template>

<script>
import axios from 'axios';
import { fetchGift } from '@/services/gifts-service.js';
import BaseModal from '@/components/modal';

export default {
  name: 'GiftFormModal',
  components: {
    BaseModal,
  },
  props: {
    giftId: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      gift: {
        title: null,
        price: null,
      },
    };
  },
  computed: {
    titleText() {
      if (this.giftId) {
        return 'Edit a gift';
      }

      return 'Add a new gift';
    },
    buttonText() {
      if (this.giftId) {
        return 'Edit';
      }

      return 'Add';
    },
  },
  methods: {
    confirm() {
      const param = {
        title: this.gift.title,
        price: parseFloat(this.gift.price),
      };
      if (this.giftId) {
        axios
          .put(this.giftId, param)
          .then(() => {
            this.$emit('updated');
          });
      } else {
        axios
          .post('/api/gifts', param)
          .then(() => {
            this.$emit('created');
          });
      }
    },
    hide() {
      this.gift = {
        firstName: null,
        lastName: null,
      };
      this.$emit('hide');
    },
    async shown() {
      if (this.giftId) {
        const response = await fetchGift(this.giftId);
        this.gift = response.data;
      }
    },
  },
};
</script>
