<template>
  <form class="card mb-12" id="form-stokmasuk" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            stokmasuk?.uuid
              ? `Edit Stok Masuk : ${stokmasuk.name}`
              : "Tambah Stok Masuk"
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
            <label for="tipe_barang" class="form-label required">
              Tipe Barang :
            </label>
            <select2
              class="form-control"
              name="tipe_barang"
              placeholder="Pilih Tipe Barang"
              id="tipe_barang"
              v-model="form.tipe_barang"
              @change="getSatuan()"
              required
            >
              <option value="" disabled>Pilih Tipe Barang</option>
              <option value="Barang Jadi">Barang Jadi</option>
              <option value="Barang Mentah">Barang Mentah</option>
            </select2>
          </div>
        </div>

        <div class="col-6" v-if="form.tipe_barang === 'Barang Mentah'">
          <div class="mb-8">
            <label for="nm_barang_mentah" class="form-label required">
              Nama Barang Mentah :
            </label>
            <select2
              class="form-control"
              name="barangmentah_id"
              placeholder="Pilih Nama Barang Mentah"
              id="barangmentah_id"
              v-model="form.barangmentah_id"
              required
            >
              <option value="" disabled>Pilih Barang Mentah</option>
              <option
                v-for="item in barangmentahs"
                :value="item.id"
                :key="item.id"
              >
                {{ item.nm_barangmentah }}
              </option>
            </select2>
          </div>
        </div>

        <div class="col-6" v-else-if="form.tipe_barang === 'Barang Jadi'">
          <div class="mb-8">
            <label for="nm_barang_jadi" class="form-label required">
              Nama Barang Jadi :
            </label>
            <select2
              class="form-control"
              name="barangjadi_id"
              placeholder="Pilih Nama Barang Jadi"
              id="barangjadi_id"
              v-model="form.barangjadi_id"
              required
            >
              <option value="" disabled>Pilih Barang Jadi</option>
              <option
                v-for="item in barangjadis"
                :value="item.id"
                :key="item.id"
              >
                {{ item.nm_barang_jadi }}
              </option>
            </select2>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="barang_masuk" class="form-label required">
              Jumlah Stok Masuk :
            </label>
            <input
              type="text"
              name="barang_masuk"
              id="barang_masuk"
              placeholder="Jumlah Stok Masuk"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.barang_masuk"
            />
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="satuan_jadi" class="form-label required">
              Nama Satuan Jadi :
            </label>
            <select2
              class="form-control"
              name="satuan_jadi"
              placeholder="Pilih Satuan_jadi"
              id="satuan_jadi"
              v-model="form.satuan_jadi"
              required
            >
              <option value="" disabled>Pilih Satuan</option>
              <!-- <option
                v-for="item in barangjadi"
                :value="item.id"
                :key="item.id"
              >
                {{ item.nm_barang_jadi }}
              </option> -->
            </select2>
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="tanggal_masuk" class="form-label required">
              Tanggal :
            </label>
            <input
              type="date"
              name="tanggal_masuk"
              id="tanggal_masuk"
              placeholder="Kode"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.tanggal_masuk"
            />
          </div>
        </div>

        <div class="col-12">
          <div class="mb-8">
            <label for="keterangan" class="form-label required">
              Keterangan :
            </label>
            <textarea
              name="keterangan"
              id="keterangan"
              placeholder="Keterangan"
              class="form-control"
              v-model="form.keterangan"
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

    const { data: barangjadis } = useQuery(["barang_jadis"], () =>
      axios.get("/barangjadi/get").then((res) => res.data)
    );

    const { data: barangmentahs } = useQuery(["barang_mentahs"], () =>
      axios.get("/barangmentah/get").then((res) => res.data)
    );

    const { data: stokmasuk } = useQuery(
      ["stokmasuk", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-stokmasuk"), 100);
        return axios.get(`/stokmasuk/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-stokmasuk"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected ? `/stokmasuk/${selected}/update` : "/stokmasuk/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-stokmasuk");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-stokmasuk");
        },
      }
    );

    return {
      stokmasuk,
      barangjadis,
      barangmentahs,
      submit,
      form,
      queryClient,
    };
  },
  methods: {
    getSatuan() {
      if (this.form.tipe_barang == "Barang Jadi") {
        this.getSatuanJadi();
      } else {
        this.getSatuanMentah();
      }
    },

    getSatuanJadi() {
      setTimeout(() => {
        var app = this;
        var id = app.form.barangjadi_id;

        var index = app.barangjadis.findIndex((cat) => cat.id == id);
        id = app.barangjadis[index].barangsatuanjadi_id;
        axios
          .get(`barangsatuanjadi/${id}/child`)
          .then((res) => {
            app.form.satuan_jadi_child = res.data.data;

            // if (app.form.satuan_id != "" && app.form.satuan_id != undefined) {
            //   app.form.satuan = app.form.satuan_id;
            // }
          })
          .catch((err) => {
            toastr.error("sesuatu error terjadi", "gagal");
          });
      }, 500);
    },

    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-stokmasuk"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/stokmasuk/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
