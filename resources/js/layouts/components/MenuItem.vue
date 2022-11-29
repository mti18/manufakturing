<template>
  <div class="menu-item">
    <Link
      class="menu-link"
      :class="{ active: route().current() === menu.route }"
      :href="route(menu.route)"
    >
      <span class="menu-icon menu-bullet w-25px">
        <i :class="menu.icon"></i>
      </span>
      <span class="menu-title">{{ menu.name }}</span>
    </Link>
  </div>
</template>

<script>
export default {
  name: "menu-item",
  props: {
    menu: {
      type: Object,
      required: true,
    },
  },
};
</script>

<style></style>
