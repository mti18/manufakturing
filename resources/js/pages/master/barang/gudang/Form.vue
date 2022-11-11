<template>
  <form class="card mb-12" id="form-gudang" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            gudang?.uuid ? `Edit Gudang : ${gudang.nm_gudang}` : "Tambah Gudang"
          }}
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
            <label for="name" class="form-label required">
              Nama Gudang :
            </label>
            <input
              type="text"
              name="nm_gudang"
              id="name"
              placeholder="Nama Gudang"
              class="form-control"
              required
              @input.prevent="getcode()"
              autoComplete="off"
              v-model="form.nm_gudang"
            />
          </div>
        </div>

        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Kode : </label>
            <input
              readonly
              type="text codes"
              name="kode"
              id="name"
              placeholder="Kode (otomatis)"
              class="form-control"
              autoComplete="off"
              v-model="form.kode"
            />
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
  data() {
    return {
      code: null,
    };
  },
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({});

    const { data: gudang } = useQuery(
      ["gudang", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-gudang"), 100);
        return axios.get(`/gudang/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-gudang"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(selected ? `/gudang/${selected}/update` : "/gudang/store", data)
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-gudang");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-gudang");
        },
      }
    );

    return {
      gudang,
      submit,
      form,
      queryClient,
    };
  },
  methods: {
    getcode() {
      this.$http
        .get("gudang/getcode")
        .then((res) => {
          this.form.kode = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-gudang"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/gudang/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
