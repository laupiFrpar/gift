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
        <div class="modal-header">
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
              type="button"
              class="btn-close"
              data-dismiss="modal"
              aria-label="Close"
            />
          </slot>
        </div>
        <div class="modal-body">
          <slot />
        </div>
        <div class="modal-footer">
          <slot name="footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              {{ cancelText }}
            </button>
            <button
              type="submit"
              class="btn btn-primary"
            >
              {{ confirmText }}
            </button>
          </slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Modal } from 'bootstrap';

export default {
  name: 'GenericModal',
  props: {
    cancelText: {
      type: String,
      default: 'Cancel',
    },
    centered: {
      type: Boolean,
      default: false,
    },
    confirmText: {
      type: String,
      default: 'Confirm',
    },
    extraLarge: {
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
      };
    },
  },
  mounted() {
    const genericModal = new Modal(
      this.$el,
      {
        show: false,
        backdrop: 'static',
      },
    );

    // console.log(modalElement._config);
    setTimeout(() => {
      genericModal.show();
    });
  },
};
</script>
