<template>
  <form class="card mb-12" id="form-retur" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>Retur Barang</h3>
        <button
          type="button"
          class="btn btn-light-danger btn-sm ms-auto"
          @click="($parent.openRetur = false), ($parent.selected = undefined)"
        >
          <i class="las la-times-circle"></i>
          Batal
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-12">
          <div class="mb-8">
            <label for="name" class="form-label"> Nomor Pemesanan : </label>
            <input
              type="text"
              name="no_pemesanan"
              id="name"
              class="form-control"
              required
              disabled
              autoComplete="off"
              v-model="form.no_pemesanan"
            />
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label"> Nama Barang : </label>
            <input
              type="text"
              name="nm_kategori"
              id="name"
              class="form-control"
              placeholder="Pilih"
              required
              autoComplete="off"
              v-model="form.nm_kategori"
            />
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label"> Jumlah : </label>
            <input
              type="text"
              name="jumlah"
              id="name"
              class="form-control"
              placeholder="Jumlah"
              required
              autoComplete="off"
              v-model="form.jumlah"
            />
          </div>
        </div>
        <div class="col-12">
          <div class="mb-8">
            <label for="name" class="form-label"> Keterangan : </label>
            <textarea
              class="form-control"
              name="keterangan"
              v-model="keterangan"
              placeholder="Keterangan"
            ></textarea>
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
    const form = ref({});

    const { data: salesorder } = useQuery(
      ["salesorder", selected, "get"],
      () => {
        setTimeout(() => KTApp.block("#form-retur"), 100);
        return axios.get(`/salesorder/${selected}/get`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-retur"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected ? `/salesorder/${selected}/update` : "/salesorder/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-retur");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-retur");
        },
      }
    );

    return {
      salesorder,
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
      const data = new FormData(document.getElementById("form-retur"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          // vm.$parent.openRetur = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/salesorder/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
