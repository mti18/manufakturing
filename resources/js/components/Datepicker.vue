<template>
  <input v-bind="$props" />
</template>

<script>
import { getCurrentInstance, ref } from "vue";
export default {
  props: {
    modelValue: {
      type: String,
      default: null,
    },
    id: {
      type: String,
      default: null,
    },
    placeholder: {
      type: String,
      default: "Pilih Tanggal",
    },
    onChange: {
      type: Function,
      default: null,
    },
    options: {
      type: Object,
      default: null,
    },
  },
  setup() {
    const ready = ref(false);
    return { ready };
  },
  emits: ["update:modelValue"],
  mounted() {
    const vm = this;
    $(this.$el).flatpickr({
      onChange: (selectedDates, dateStr) => {
        vm.$emit("update:modelValue", dateStr);
        if (vm.onChange) {
          vm.onChange(dateStr);
        }
      },
      defaultDate: vm.modelValue,
      ...vm.options,
    });
  },
  watch: {
    modelValue(val) {
      $(this.$el).flatpickr().setDate(val);
      this.$emit("update:modelValue", val);
    },
  },
  destroyed() {
    $(this.$el).flatpickr().destroy();
  },
};
</script>

<style></style>
