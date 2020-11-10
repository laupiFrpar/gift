<template>
  <div
    :id="id"
    class="modal fade"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalLabel"
    aria-hidden="true"
  >
    <div
      :class="['modal-dialog', modalClass]"
    >
      <div class="modal-content">
        <div
          v-if="showHeader"
          class="modal-header"
        >
          <slot name="header">
            <h4
              id="modalLabel"
              class="modal-title"
            >
              <slot name="title">
                {{ title }}
              </slot>
            </h4>
            <button
              v-if="showCloseButton"
              type="button"
              class="btn-close"
              data-dismiss="modal"
              aria-label="Close"
              @click="close"
            />
          </slot>
        </div>
        <div class="modal-body">
          <slot />
        </div>
        <div
          v-if="showFooter"
          class="modal-footer"
        >
          <slot name="footer">
            <button
              v-if="showCancelButton"
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              @click="cancel"
            >
              {{ cancelButtonText }}
            </button>
            <button
              type="submit"
              :class="confirmButtonClass"
              @click="confirm"
            >
              {{ confirmButtonText }}
            </button>
          </slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// Inspired by https://github.com/hharchani/vuejs-bootstrap-modal
import { Modal } from 'bootstrap';

export default {
  name: 'BaseModal',
  props: {
    cancelButtonText: {
      type: String,
      default: 'Cancel',
    },
    centered: {
      type: Boolean,
      default: false,
    },
    closeByBackdrop: {
      type: Boolean,
      default: true,
    },
    closeByKeyboard: {
      type: Boolean,
      default: true,
    },
    confirmButtonText: {
      type: String,
      default: 'Confirm',
    },
    confirmButtonType: {
      type: String,
      default: 'primary',
    },
    extraLarge: {
      type: Boolean,
      default: false,
    },
    focus: {
      type: Boolean,
      default: true,
    },
    fullscreen: {
      type: Boolean,
      default: false,
    },
    fullscreenSmall: {
      type: Boolean,
      default: false,
    },
    fullscreenMedium: {
      type: Boolean,
      default: false,
    },
    fullscreenLarge: {
      type: Boolean,
      default: false,
    },
    fullscreenExtraLarge: {
      type: Boolean,
      default: false,
    },
    id: {
      type: String,
      default: 'generic-modal',
    },
    large: {
      type: Boolean,
      default: false,
    },
    scrollable: {
      type: Boolean,
      default: false,
    },
    show: {
      type: Boolean,
      default: false,
    },
    showCancelButton: {
      type: Boolean,
      default: true,
    },
    showCloseButton: {
      type: Boolean,
      default: true,
    },
    showFooter: {
      type: Boolean,
      default: true,
    },
    showHeader: {
      type: Boolean,
      default: true,
    },
    small: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: '',
    },
  },
  computed: {
    modalClass() {
      return {
        'modal-dialog-centered': this.centered,
        'modal-dialog-scrollable': this.scrollable,
        'modal-lg': this.large,
        'modal-sm': this.small,
        'modal-xlg': this.extraLarge,
        'modal-fullscreen': this.fullscreen,
        'modal-fullscreen-sm-down': this.fullscreenSmall,
        'modal-fullscreen-md-down': this.fullscreenMedium,
        'modal-fullscreen-lg-down': this.fullscreenLarge,
        'modal-fullscreen-xl-down': this.fullscreenExtraLarge,
      };
    },
    confirmButtonClass() {
      return `btn btn-${this.confirmButtonType}`;
    },
  },
  mounted() {
    this.$el
      .addEventListener('show.bs.modal', () => {
        this.$emit('show');
      });
    this.$el
      .addEventListener('shown.bs.modal', () => {
        this.$emit('shown');
      });
    this.$el
      .addEventListener('hide.bs.modal', () => {
        this.$emit('hide');
      });
    this.$el
      .addEventListener('hidden.bs.modal', () => {
        this.$emit('hidden');
      });
    this.$el
      .addEventListener('hidePrevented.bs.modal', () => {
        this.$emit('hide-prevented');
      });

    const modal = new Modal(
      this.$el,
      {
        backdrop: this.closeByBackdrop ? true : 'static',
        keyboard: this.closeByKeyboard,
        focus: this.focus,
        show: false,
      },
    );

    if (this.show) {
      // putting timeout as modal doesn't open without it
      setTimeout(() => {
        modal.show();
      });
    }
  },
  methods: {
    cancel() {
      this.$emit('cancel');
    },
    close() {
      this.$emit('close');
    },
    confirm() {
      this.$emit('confirm');
      this.hide();
    },
    hide() {
      Modal.getInstance(this.$el).hide();
    },
  },
};
</script>
