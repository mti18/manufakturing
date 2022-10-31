<template>
  <input v-bind="$props" />
</template>

<script>
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
      ...vm.options
    });
  },
  watch: {
    modelValue(val) {
      $(this.$el).flatpickr().setDate(val)
    }
  },
  destroyed() {
    $(this.$el).flatpickr().destroy();
  },
}
</script>

<style>

</style>