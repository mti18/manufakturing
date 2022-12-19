<template>
  <form class="card mb-12" id="form-position" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            position?.uuid
              ? `Edit Permintaan Internal : ${position.name}`
              : "Tambah Permintaan Internal"
          }}
        </h3>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Tipe Barang : </label>
            <input
              type="text"
              name="name"
              id="name"
              placeholder="Nama"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.tipe_barang"
            />
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="code" class="form-label required"> Nama Barang : </label>
            <input
              type="text"
              name="code"
              id="code"
              placeholder="Kode"
              class="form-control"
              required
              readonly
              autoComplete="off"
              v-model="form.barang_id"
            />
          </div>
        </div>
        <div class="col-4">
          <div class="mb-8">
            <label for="code" class="form-label required"> Volume : </label>
            <input
              type="text"
              name="volume"
              id="volume"
              placeholder="Volume"
              class="form-control"
              required
              readonly
              autoComplete="off"
              v-model="form.volume"
            />
          </div>
        </div>
        <div class="col-4">
          <div class="mb-8">
            <label for="code" class="form-label required"> Harga : </label>
            <input
              type="text"
              name="harga"
              id="harga"
              placeholder="Harga"
              class="form-control"
              required
              readonly
              autoComplete="off"
              v-model="form.harga"
            />
          </div>
        </div>
        <div class="col-4">
          <div class="mb-8">
            <label for="code" class="form-label required"> Jumlah : </label>
            <input
              type="text"
              name="jumlah"
              id="jumlah"
              placeholder="Jumlah"
              class="form-control"
              required
              readonly
              autoComplete="off"
              v-model="form.jumlah"
            />
          </div>
        </div>
        <div class="col-12">
          <div class="mb-8">
            <label for="code" class="form-label required"> Keterangan : </label>
            <textarea
              name="code"
              id="code"
              placeholder="Keterangan"
              class="form-control"
              required
              readonly
              autoComplete="off"
              v-model="form.keterangan"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <button
            type="submit"
            class="btn btn-primary btn-sm ms-auto mt-8"
          >
            <i class="las la-save"></i>
            Simpan
          </button>
          <button
              type="button"
              class="btn btn-danger btn-sm ms-5 mt-8"
              @click="($parent.openFormInternal = false), ($parent.selected = undefined)"
          >
              <i class="las la-times-circle"></i>
              Batal
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

    const { data: position } = useQuery(
      ["position", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-position"), 100);
        return axios.get(`/position/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-position"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected ? `/position/${selected}/update` : "/position/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-position");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-position");
        },
      }
    );

    return {
      position,
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
      const data = new FormData(document.getElementById("form-position"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openFormInternal = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/position/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
