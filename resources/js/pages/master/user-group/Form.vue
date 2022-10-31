<template>
  <form class="card mb-12" id="form-user-group" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{ user-group?.uuid ? `Edit Jabatan : ${user-group.name}` : "Tambah Jabatan"  }}
        </h3>
        <button
          type="button"
          class="btn btn-light-danger btn-sm ms-auto"
          @click="($parent.openForm = false, $parent.selected = undefined)"
        >
          <i class="las la-times-circle"></i>
          Batal
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Nama : </label>
            <input type="text" name="name" id="name" placeholder="Nama"
              class="form-control" required autoComplete="off" v-model="form.name" />
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
import { ref } from "vue";
import { useQuery, useMutation } from "vue-query";
import axios from "@/libs/axios";
import { useQueryClient } from "vue-query";

export default {
  props: {
    selected: {
      type: String,
      default: null,
    }
  },
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({});

    const { data: userGroup } = useQuery(
      ["user-group", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-user-group"), 100);
        return axios.get(`/user-group/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: data => form.value = data,
        onSettled: () => KTApp.unblock("#form-user-group"),
      }
    );

    const { mutate: submit } = useMutation((data) => axios.post(selected ? `/user-group/${selected}/update` : '/user-group/store', data).then(res => res.data), {
      onMutate: () => {
        KTApp.block("#form-user-group");
      },
      onError: (error) => {
        toastr.error(error.response.data.message);
      },
      onSettled: () => {
        KTApp.unblock("#form-user-group");
      }
    });

    return {
      userGroup,
      submit,
      form,
      queryClient
    }
  },
  methods: {
    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-user-group"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/user-group/paginate"], { exact: true });
          vm.queryClient.invalidateQueries(["dashboard", "menus", "access", "checked"]);
        }
      });
    }
  }
};
</script>

<style>
</style>