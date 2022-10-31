<template>
  <div class="form-check mb-4 form-check-custom form-check-solid form-check-sm form-check-success flex-column align-items-start">
    <label class="form-check-label">
      <input class="form-check-input me-2" type="checkbox" :checked="!!menu?.checked" @change="onChange" />
      {{ menu.name }}
    </label>
  </div>
</template>

<script>
import { useQueryClient } from "vue-query";

export default {
  props: {
    menu: {
      type: Object,
      required: true
    },
  },
  setup() {
    const queryClient = useQueryClient();

    return {
      queryClient,
    };
  },
  methods: {
    onChange(ev) {
      this.queryClient.setQueryData(["dashboard", "menus", "access"], (prev) => {
        const menu = findDeep(prev, this.menu.uuid, "uuid");
        if (menu) {
          menu.checked = ev.target.checked;
        }

        return prev;
      });

      this.queryClient.setQueryData(["dashboard", "menus", "access", "checked"], (prev) => {
        if (!prev) {
          prev = [];
        }

        if (ev.target.checked) {
          return [...new Set([...prev, this.menu.uuid])];
        } else {
          return prev.filter((uuid) => uuid !== this.menu.uuid);
        }
      });
    }
  }
}
</script>

<style>

</style>