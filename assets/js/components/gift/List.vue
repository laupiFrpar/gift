<template>
  <div class="table-responsive-sm">
    <table class="table table-hover table-sm">
      <thead>
        <row-header
          :columns="['Label', 'Price']"
        />
      </thead>
      <tbody v-if="gifts.length">
        <row
          v-for="gift in gifts"
          :key="gift['@id']"
          :item="gift"
          @edit-item="edit"
          @remove-item="remove"
        >
          <td>{{ gift.title }}</td>
          <td>{{ price(gift.price) }}</td>
        </row>
      </tbody>
      <tbody v-else>
        <tr class="text-center">
          <td colspan="3">
            No gift registered
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import Row from '@/components/table/Row';
import RowHeader from '@/components/table/RowHeader.vue';
import formatPrice from '@/helpers/format-price';

export default {
  name: 'GiftList',
  components: {
    Row,
    RowHeader,
  },
  props: {
    gifts: {
      type: Array,
      required: true,
    },
  },
  methods: {
    edit(giftId) {
      this.$emit('edit', giftId);
    },
    price(price) {
      return formatPrice(price);
    },
    remove(giftId) {
      this.$emit('remove', giftId);
    },
  },
};
</script>
