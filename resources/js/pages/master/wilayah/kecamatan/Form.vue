<template>
  <form class="card mb-12" id="form-kecamatan" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            kecamatan?.uuid
              ? `Edit Kecamatan : ${kecamatan.name}`
              : "Tambah Kecamatan"
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
              Nama Kecamatan:
            </label>
            <input
              type="text"
              name="nm_kecamatan"
              id="nm_kecamatan"
              placeholder="Nama"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.nm_kecamatan"
            />
          </div>
          <div class="mb-8">
            <label for="name" class="form-label required">
              Kota/Kabupaten:
            </label>
            <select2
              name="kab_kota_id"
              id="kab_kota_id"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.kab_kota_id"
            >
              <option v-for="kota in kotas" :value="kota.id" :key="kota.uuid">
                {{ kota.nm_kab_kota }}
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
    const form = ref({});

    const { data: kecamatan } = useQuery(
      ["kecamatan", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-kecamatan"), 100);
        return axios.get(`/kecamatan/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-kecamatan"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected ? `/kecamatan/${selected}/update` : "/kecamatan/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-kecamatan");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-kecamatan");
        },
      }
    );

    const { data: kotas } = useQuery(
      ["kotas"],
      () => axios.get("/kota/get").then((res) => res.data),
      {
        placeholderData: [],
      }
    );

    return {
      kecamatan,
      submit,
      form,
      kotas,
      queryClient,
    };
  },
  methods: {
    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-kecamatan"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/kecamatan/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
