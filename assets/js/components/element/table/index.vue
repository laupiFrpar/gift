<template>
  <div class="table-responsive-sm">
    <table class="table table-hover table-sm">
      <thead>
        <table-header :fields="fields" />
      </thead>
      <!-- <tbody v-if="items.length"> -->
      <tbody>
        <table-row
          v-for="item in items"
          v-show="!loading && items.length > 0"
          :key="item['@id']"
          :item="item"
          :fields="fields"
          @edit-item="editItem"
          @remove-item="removeItem"
        />
        <tr
          v-show="loading || items.length === 0"
          class="text-center"
        >
          <td colspan="3">
            <loading v-show="loading" />
            <span v-show="!loading && items.length === 0">{{ emptyMessage }}</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import Loading from '@/components/loading';
import TableRow from '@/components/element/table/TableRow';
import TableHeader from '@/components/element/table/TableHeader';

export default {
  name: 'TableComponent',
  components: {
    Loading,
    TableHeader,
    TableRow,
  },
  props: {
    emptyMessage: {
      type: String,
      default: 'No items',
    },
    fields: {
      type: Array,
      required: true,
    },
    items: {
      type: Array,
      required: true,
    },
    loading: {
      type: Boolean,
      required: true,
    },
  },
  methods: {
    editItem(id) {
      this.$emit('edit-item', id);
    },
    removeItem(id) {
      this.$emit('remove-item', id);
    },
  },
};
</script>
