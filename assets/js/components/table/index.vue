<template>
  <div class="table-responsive-sm">
    <table class="table table-hover table-sm">
      <thead>
        <table-header
          :fields="fields"
        />
      </thead>
      <tbody v-if="items.length">
        <table-row
          v-for="item in items"
          :key="item['@id']"
          :item="item"
          :fields="fields"
          @edit-item="editItem"
          @remove-item="removeItem"
        />
      </tbody>
      <tbody v-else>
        <tr class="text-center">
          <td colspan="3">
            {{ emptyMessage }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import TableRow from '@/components/table/TableRow';
import TableHeader from '@/components/table/TableHeader';

export default {
  name: 'TableComponent',
  components: {
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
