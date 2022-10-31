<template>
  <select :data-index="fromIndex ? fromIndex : 0" :id="id">
    <slot></slot>
  </select>
</template>

<script type="text/javascript">
export default {
  props: ['options', 'modelValue', 'placeholder', 'onChange', 'fromIndex', 'search', 'id'],
  emits: ["update:modelValue"],
  mounted: function() {
    var vm = this;
    $(this.$el)
      .select2({
        data: this.options,
        placeholder: this.placeholder ? this.placeholder : 'Pilih',
        minimumResultsForSearch: this.search == 'hide' ? -1 : 5,
      })
      .val(this.modelValue)
      .trigger('change')
      .on('change', function() {
        vm.$emit("update:modelValue", this.value);
        if (vm.onChange) {
          vm.onChange($(this).val());
        }
      });
  },
  watch: {
    modelValue: function(value) {
      $(this.$el)
        .val(value)
        .trigger('change');

      this.$emit('change', this.modelValue);
    },
    options: function(options) {
      $(this.$el)
        .empty()
        .select2({ data: options });
    },
  },
  destroyed: function() {
    $(this.$el)
      .off()
      .select2('destroy');
  },
};
</script>
