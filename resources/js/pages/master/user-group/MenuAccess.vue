<template>
  <form class="card mb-12" id="form-menu-access" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          Akses Menu : {{ userGroup?.name }}
        </h3>
        <button
          type="button"
          class="btn btn-light-danger btn-sm ms-auto"
          @click="($parent.openMenuAccess = false, $parent.selected = undefined)"
        >
          <i class="las la-times-circle"></i>
          Batal
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-12">
          <div class="checkbox-list">
            <template v-for="menu in menus" :key="menu.uuid">
              <checkbox-nested v-if="!!menu.children.length" :menu="menu"></checkbox-nested>
              <checkbox-item v-else :menu="menu"></checkbox-item>
            </template>
          </div>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary btn-sm ms-auto mt-8 d-block">
            <i class="las la-save"></i>
            Simpan
          </button>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import { useQuery, useQueryClient, useMutation } from "vue-query";
import axios from "@/libs/axios";

import CheckboxItem from "./components/CheckboxItem.vue";
import CheckboxNested from "./components/CheckboxNested.vue";

export default {
  components: {
    "checkbox-item": CheckboxItem,
    "checkbox-nested": CheckboxNested,
  },
  props: {
    selected: {
      type: String,
      default: null,
    }
  },
  setup({ selected }) {
    const queryClient = useQueryClient();

    const { data: userGroup = [] } = useQuery(["user-group", selected], () => axios.get(`/user-group/${selected}`).then(res => res.data), {
      placeholderData: []
    });

    const { data: menus = [] } = useQuery(["dashboard", "menus", "access"], () => axios.post('/dashboard/menu/access', {
      user_group_uuid: selected
    }).then(res => res.data), {
      cacheTime: 0,
      placeholderData: []
    });

    const { data: checkedMenus = [] } = useQuery(["dashboard", "menus", "access", "checked"], () => axios.post('/dashboard/menu/access/checked', {
      user_group_uuid: selected
    }).then(res => res.data), {
      cacheTime: 0,
      placeholderData: []
    });

    const { mutate: submit } = useMutation((data) => axios.post('/dashboard/menu/access/save', {
      user_group_uuid: selected,
      menus: data
    }).then(res => res.data), {
      onMutate: () => KTApp.block('#form-menu-access'),
      onSettled: () => KTApp.unblock('#form-menu-access'),
      onError: (error) => toastr.error(error.response.data.message),
    });

    return {
      menus,
      queryClient,
      submit,
      checkedMenus,
      userGroup
    }
  },
  methods: {
    onSubmit() {
      // filter deep nested array of object
      const vm = this;
      const checkedMenus = this.queryClient.getQueryData(["dashboard", "menus", "access", "checked"]);
      this.submit(checkedMenus, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openMenuAccess = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/user-group/paginate"]);
          vm.queryClient.invalidateQueries(["dashboard", 'menus'], { exact: true });
          vm.queryClient.invalidateQueries(["dashboard", "menus", "access"], { exact: true });
        }
      });
    }
  }
}
</script>

<style>

</style>