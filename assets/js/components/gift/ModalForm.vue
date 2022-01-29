<template>
  <modal-component
    id="gift-form-modal"
    :centered="true"
    :confirm-text="buttonText"
    :title="titleText"
    @confirm="confirm"
    @hide="hide"
    @shown="shown"
  >
    <form-component>
      <text-input
        id="title"
        label="Title"
        :inline="false"
        :value="gift.title"
        @updated-value="onUpdatedTitle"
      />
      <price-input
        id="price"
        label="Price"
        :value="gift.price"
        @updated-value="onUpdatedPrice"
      />
    </form-component>
  </modal-component>
</template>

<script>
import axios from 'axios';
import { fetchGift } from '@/services/gifts-service.js';
import FormComponent from '@/components/element/form';
import TextInput from '@/components/element/form/input/Text';
import PriceInput from '@/components/element/form/input/Price';
import ModalComponent from '@/components/modal';

export default {
  name: 'GiftFormModal',
  components: {
    FormComponent,
    TextInput,
    PriceInput,
    ModalComponent,
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
    onUpdatedTitle(event) {
      this.gift.title = event.value;
    },
    onUpdatedPrice(event) {
      this.gift.price = event.value;
    },
  },
};
</script>
