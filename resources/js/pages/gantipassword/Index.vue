<template>
  <form class="card mb-12" id="form-gantipassword" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>Ganti Password</h3>
        <div class="ms-auto">
          <Link :href="route('dashboard')">
            <button type="button" class="btn btn-light-danger btn-sm ms-auto">
              <i class="las la-times-circle"></i>
              Batal
            </button>
          </Link>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="oldpassword" class="form-label required">
              Password Lama :
            </label>
            <input
              type="text"
              name="oldpassword"
              id="name"
              placeholder="Password Lama"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.oldpassword"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="newpassword" class="form-label required">
              Password Baru :
            </label>
            <input
              type="text"
              name="newpassword"
              id="name"
              placeholder="Password Baru"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.newpassword"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="newpassword_confirmation" class="form-label required">
              Konfirmasi Password :
            </label>
            <input
              type="text"
              name="newpassword_confirmation"
              id="name"
              placeholder="Konfirmasi Password"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.newpassword_confirmation"
            />
          </div>
        </div>
      </div>
      <div class="row">
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
import { Inertia } from "@inertiajs/inertia";

export default {
  props: {
    selected: {
      type: String,
      default: null,
    },
  },
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({});

    const { data: user } = useQuery(
      ["user", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-gantipassword"), 100);
        return axios.get(`/gantipassword/updatepw`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-gantipassword"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios.post("/gantipassword/updatepw", data).then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-gantipassword");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-gantipassword");
        },
      }
    );

    return {
      user,
      submit,
      form,
      queryClient,
    };
  },
  methods: {
    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-gantipassword"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          Inertia.visit("/");

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
