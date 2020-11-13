<template>
  <div class="table-responsive-sm">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col">
            Label
          </th>
          <th scope="col">
            Price
          </th>
          <th scope="col" />
        </tr>
      </thead>
      <tbody v-if="gifts.length">
        <row
          v-for="gift in gifts"
          :key="gift['@id']"
          :gift="gift"
        />
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
import Row from '@/components/gift/Row';

export default {
  name: 'GiftList',
  components: {
    Row,
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
    remove(giftId) {
      this.$emit('remove', giftId);
    },
  },
};
</script>
