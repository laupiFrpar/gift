<template>
  <component
    :is="element"
    :class="componentClass"

    :type="componentType"

    :href="href"
    :role="role"
  >
    <slot />
  </component>
</template>

<script>
export default {
  name: 'ButtonComponent',
  props: {
    href: {
      type: String,
      default: null,
    },
    type: {
      type: String,
      default: 'button',
    },
    role: {
      type: String,
      default: 'button',
    },
    variant: {
      type: String,
      default: 'primary',
    },
  },
  computed: {
    /**
     * Computes the component classes
     *
     * @return string[]
     */
    componentClass() {
      const classes = [`btn-${this.variant}`];

      if (this.variant !== 'close') {
        classes.push('btn');
      }

      return classes;
    },
    /**
     * Computes the component type depending on href value
     *
     * @return string
     */
    componentType() {
      return this.href ? null : this.type;
    },
    /**
     * Computes the component role depending on href value
     *
     * @return string
     */
    componentRole() {
      return this.href ? this.role : null;
    },
    /**
     * Computes the component element depending on href value
     *
     * @return string
     */
    element() {
      return this.href ? 'a' : 'button';
    },
  },
};
</script>
