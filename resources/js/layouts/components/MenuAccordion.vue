<template>
  <div
    ref="accordion"
    data-kt-menu-trigger="click"
    class="menu-item menu-accordion"
  >
    <span class="menu-link">
      <span class="menu-icon">
        <i :class="menu.icon"></i>
      </span>
      <span class="menu-title">{{ menu.name }}</span>
      <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion menu-active-bg">
      <template v-for="child in menu.children" :key="child.uuid">
        <menu-accordion v-if="!!child.children.length" :menu="child" :key="`accordion-${child.uuid}`" />
        <menu-item v-else :menu="child" :key="`item-${child.uuid}`" />
      </template>
    </div>
  </div>
</template>

<script>
export default {
  name: 'menu-accordion',
  props: {
    menu: {
      type: Object,
      required: true,
    },
  },
  mounted() {
    if (this.$refs.accordion.querySelector(".menu-link.active")) {
      this.$refs.accordion.classList.add("show");
    }
  }
}
</script>

<style>

</style>