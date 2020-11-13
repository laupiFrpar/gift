<template>
  <tr>
    <td>{{ gift.title }}</td>
    <td>{{ price }}</td>
    <td>
      <button
        type="button"
        class="btn btn-primary"
        @click="edit(gift['@id'])"
      >
        <i class="fas fa-edit" />
      </button>
      &nbsp;
      <button
        type="button"
        class="btn btn-danger"
        @click="remove(gift['@id'])"
      >
        <i class="fas fa-trash" />
      </button>
    </td>
  </tr>
</template>

<script>
import formatPrice from '@/helpers/format-price';

export default {
  name: 'Row',
  props: {
    gift: {
      type: Array,
      required: true,
    },
  },
  computed: {
    price() {
      return formatPrice(this.gift.price);
    },
  },
};
</script>
