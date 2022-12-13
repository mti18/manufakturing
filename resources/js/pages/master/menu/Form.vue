<template>
  <form class="card mb-12" id="form-menu" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{ menu?.uuid ? `Edit Menu : ${menu.name}` : "Tambah Menu" }}
        </h3>
        <button
          type="button"
          class="btn btn-light-danger btn-sm ms-auto"
          @click="($parent.openForm = false), ($parent.selected = undefined)"
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
            <input
              type="text"
              name="name"
              id="name"
              placeholder="Nama Menu"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.name"
            />
          </div>
        </div>

        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label"> Icon : </label>
            <input
              type="text"
              name="icon"
              id="name"
              placeholder="Icon Menu"
              class="form-control"
              autoComplete="off"
              v-model="form.icon"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-6">
            <div class="mb-8">
              <label for="name" class="form-label"> URL : </label>
              <input
                type="text"
                name="url"
                id="name"
                placeholder="URL Menu */master/menu*"
                class="form-control"
                autoComplete="off"
                v-model="form.url"
              />
            </div>
          </div>
          <div class="col-2 ms-3">
            <div class="mb-8">
              <label for="shown" class="form-label"> Shown : </label>
              <div
                class="form-check form-check-custom form-check-solid form-check-primary form-switch mt-2"
              >
                <input
                  class="form-check-input w-50px h-35px"
                  type="checkbox"
                  true-value="true"
                  false-value="false"
                  name="shown"
                  v-model="form.shown"
                  id="kt_builder_rtl"
                  :checked="form.shown == 1 ? true : false"
                />
              </div>
            </div>
          </div>
          <div class="col-2" style="margin-left: -70px">
            <div class="mb-8">
              <label for="auth" class="form-label"> Auth : </label>
              <div
                class="form-check form-check-custom form-check-solid form-check-primary form-switch mt-2"
              >
                <input
                  class="form-check-input w-50px h-35px"
                  type="checkbox"
                  true-value="true"
                  false-value="false"
                  name="auth"
                  v-model="form.auth"
                  id="kt_builder_rtl"
                  :checked="form.auth == 1 ? true : false"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-6">
            <div class="mb-8">
              <label for="name" class="form-label"> Route : </label>
              <input
                type="text"
                name="route"
                id="name"
                placeholder="Route Menu *dashboard.master.menu*"
                class="form-control"
                autoComplete="off"
                v-model="form.route"
              />
            </div>
          </div>

          <div class="col-md-5 form-group ms-4">
            <label class="form-label required">Level</label>
            <div
              class="form-check mb-2 form-check-custom form-check-solid form-check-sm"
            >
              <input
                class="form-check-input"
                type="checkbox"
                value="admin"
                id="flexRadioLg"
                name="level[]"
                :checked="form.level.includes('admin') ? true : false"
              />
              <label class="form-check-label" for="flexRadioLg"> Admin </label>
            </div>
            <div
              class="form-check mb-2 form-check-custom form-check-solid form-check-sm"
            >
              <input
                class="form-check-input"
                type="checkbox"
                value="user"
                id="flexRadioLg"
                name="level[]"
                :checked="form.level.includes('user') ? true : false"
              />
              <label class="form-check-label" for="flexRadioLg"> User </label>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-6">
            <div class="mb-8">
              <label for="name" class="form-label"> Component : </label>
              <input
                type="text"
                name="component"
                id="name"
                placeholder="Component Menu *master/menu/Index*"
                class="form-control"
                autoComplete="off"
                v-model="form.component"
              />
            </div>
          </div>
        </div>

        <div class="col-6">
          <div class="mb-8">
            <label for="nm_gudang" class="form-label"> Parent : </label>
            <select2
              class="form-control"
              name="parent_id"
              placeholder="Pilih Parent"
              id="parent_id"
              v-model="form.parent_id"
            >
              <option value="" disabled>Pilih Parent</option>
              <option v-for="item in menus" :value="item.id" :key="item.id">
                {{ item.id + " | " + item.name }}
              </option>
            </select2>
          </div>
        </div>

        <div class="col-12">
          <button
            type="submit"
            class="btn btn-primary btn-sm ms-auto mt-8 d-block"
          >
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
    },
  },
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({
      level: [],
    });

    const { data: menus } = useQuery(["menus"], () =>
      axios.get("/menus/get").then((res) => res.data)
    );

    const { data: menu } = useQuery(
      ["menu", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-menu"), 100);
        return axios.get(`/menus/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-menu"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(selected ? `/menus/${selected}/update` : "/menus/store", data)
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-menu");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-menu");
        },
      }
    );

    return {
      menu,
      menus,
      submit,
      form,
      queryClient,
    };
  },
  methods: {
    addlevel(item) {
      var app = this;

      var index = app.form.level.findIndex((cat) => cat.id == item.id);

      if (index == -1) {
        app.form.level.push(item);
      } else {
        app.form.level.splice(index, 1);
      }
    },

    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-menu"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/kategori/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
