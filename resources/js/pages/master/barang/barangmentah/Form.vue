<template>
  <form class="card mb-12" id="form-barangmentah" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            barangmentah?.uuid
              ? `Edit Barang Mentah : ${barangmentah.nm_barangmentah}`
              : "Tambah Barang Mentah   "
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
              Barang Mentah :
            </label>
            <input
              type="text"
              name="nm_barangmentah"
              id="name"
              placeholder="Nama Barang Mentah"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.nm_barangmentah"
            />
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Stok : </label>
            <input
              type="number"
              name="stok"
              id="name"
              placeholder="Stok Barang"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.stok"
            />
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="nm_satuan" class="form-label required">
              Nama Satuan :
            </label>
            <select2
              class="form-control"
              name="nm_satuan"
              placeholder="Pilih Nama Satuan"
              id="nm_satuan"
              v-model="form.nm_satuan"
              required
            >
              <option v-if="user?.uuid"></option>
              <option
                v-for="group in userGroups"
                :value="group.uuid"
                :key="group.uuid"
              >
                {{ group.name }}
              </option>
            </select2>
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Satuan : </label>
            <input
              type="text"
              name="satuan"
              id="name"
              placeholder="Satuan"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.stok"
              disabled
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
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({});

    const { data: barangmentah } = useQuery(
      ["barangmentah", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-barangmentah"), 100);
        return axios
          .get(`/barangmentah/${selected}/edit`)
          .then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-barangmentah"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected
              ? `/barangmentah/${selected}/update`
              : "/barangmentah/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-barangmentah");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-barangmentah");
        },
      }
    );

    return {
      barangmentah,
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
      const data = new FormData(document.getElementById("form-barangmentah"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/barangmentah/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
