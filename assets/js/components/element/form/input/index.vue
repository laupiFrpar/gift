<template>
  <div :class="componentClass">
    <label
      :for="id"
      :class="labelClass"
    >{{ label }}</label>
    <div
      :class="{
        'col-sm-8': inline,
        'input-group': group
      }"
    >
      <span
        v-if="group"
        class="input-group-text"
      >{{ groupText }}</span>
      <input
        :id="id"
        v-model="modelValue"
        :name="computeName"
        :type="type"
        :class="inputClass"
        :placeholder="placeholder"
        :readonly="readonly"
        @input="onInput"
      >
    </div>
  </div>
</template>

<script>
export default {
  name: 'InputComponent',
  props: {
    group: {
      type: Boolean,
      default: false,
    },
    groupText: {
      type: String,
      default: null,
    },
    id: {
      type: String,
      required: true,
    },
    inline: {
      type: Boolean,
      default: true,
    },
    label: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      default: null,
    },
    placeholder: {
      type: String,
      default: null,
    },
    readonly: {
      type: Boolean,
      default: false,
    },
    type: {
      type: String,
      default: 'text',
    },
    value: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      modelValue: '',
    };
  },
  computed: {
    componentClass() {
      const classes = ['mb-3'];

      // if (this.group) {
      //   classes.push('input-group');
      // }

      if (this.inline) {
        classes.push('row');
      }

      return classes;
    },
    labelClass() {
      let classes = ['form-label'];

      if (this.inline) {
        classes = ['col-sm-4', 'col-form-label'];
      }

      return classes;
    },
    inputClass() {
      let classes = ['form-control'];

      if (this.readonly) {
        classes = ['form-control-plaintext'];
      }

      return classes;
    },
    computeName() {
      return this.name
        ? this.name
        : this.id;
    },
  },
  created() {
    this.modelValue = this.value;
  },
  updated() {
    if (!this.modelValue) {
      this.modelValue = this.value;
    }
  },
  methods: {
    onInput() {
      this.$emit('updated-value', { value: this.modelValue });
    },
  },
};
</script>
