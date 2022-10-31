<template>
  <div class="form-check mb-4 form-check-custom form-check-solid form-check-sm form-check-success flex-column align-items-start">
    <label class="form-check-label">
      <input class="form-check-input me-2" type="checkbox" :checked="!!menu?.checked" @change="onChange" />
      {{ menu.name }}
    </label>
  </div>
  <div class="checkbox-list my-4 mx-10">
    <template v-for="child in menu.children" :key="child.uuid">
      <checkbox-nested v-if="!!child.children.length" :menu="child"></checkbox-nested>
      <checkbox-item v-else :menu="child"></checkbox-item>
    </template>
  </div>
</template>

<script>
import { useQueryClient } from "vue-query";

import CheckboxItem from "./CheckboxItem.vue";

export default {
  name: "checkbox-nested",
  components: {
    'checkbox-item': CheckboxItem
  },
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

        // check children
        if (menu.children.length) {
          menu.children.forEach((child) => {
            child.checked = ev.target.checked;
          });
        }

        return prev;
      });

      this.queryClient.setQueryData(["dashboard", "menus", "access", "checked"], (prev) => {
        let children = [];
        getPropertyDeep(this.menu.children, 'uuid', (uuid) => children.push(uuid));
      
        if (ev.target.checked) {
          return [...new Set([...prev, this.menu.uuid, ...children])];
        } else {
          return prev.filter((uuid) => uuid !== this.menu.uuid && !children.includes(uuid));
        }
      });
    }
  }
}
</script>

<style>

</style>