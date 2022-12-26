<template>
    <form class="card mb-12" id="form-permintaaninternal" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{
              permintaaninternal?.uuid
                ? `Edit Permintaan Internal : ${permintaaninternal.name}`
                : "Tambah Permintaan Internal"
            }}
          </h3>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="mb-8">
              <label for="tipe_barang" class="form-label required"> Tipe Barang : </label>
              <select2
                class="form-control"
                name="tipe_barang"
                placeholder="Pilih Tipe Barang"
                id="tipe_barang"
                v-model="form.tipe_barang"
                required
              >
                <option value="" disabled>Pilih Tipe Barang</option>
                <option value="barang_jadi">Barang Jadi</option>
                <option value="barang_mentah">Barang Mentah</option>
              </select2>
            </div>
          </div>

          <div class="col-6" v-if="form.tipe_barang === 'barang_mentah'">
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
                @change="getSatuanMentah()"
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

          <div class="col-6" v-else-if="form.tipe_barang === 'barang_jadi'">
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
                @change="getSatuanJadi()"
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

          <div class="col-6" v-else>
            <div class="mb-8">
              <label for="" class="form-label required"> Nama Barang : </label>
              <select2
                class="form-control"
                name=""
                placeholder="Pilih Tipe Barang Terlebih Dahulu "
                id=""
                required
              >
                <option value="" disabled>
                  Pilih Tipe Barang Terlebih Dahulu
                </option>
              </select2>
            </div>
          </div>

          <div class="col-4">
            <div class="mb-8">
              <label for="" class="form-label required"> Volume : </label>
              <input
                type="number"
                name="volume"
                id="volume"
                placeholder="Volume"
                class="form-control"
                required
                autoComplete="off"
                v-model="form.volume"
              />
            </div>
          </div>
          <div class="col-4">
            <div class="mb-8">
              <label for="" class="form-label required"> Harga : </label>
              <money3
                type="text"
                name="harga"
                id="harga"
                placeholder="Harga"
                class="form-control"
                v-bind="config"
                required
                autoComplete="off"
                v-model="form.harga"
              />
            </div>
          </div>
          <div class="col-4">
            <div class="mb-8">
              <label for="" class="form-label required"> Jumlah : </label>
              <money3
                type="text"
                name="jumlah"
                id="jumlah"
                placeholder="Jumlah"
                class="form-control"
                v-bind="config"
                required
                autoComplete="off"
                v-model="form.jumlah"
              />
            </div>
          </div>
          <div class="col-12">
            <div class="mb-8">
              <label for="" class="form-label"> Keterangan : </label>
              <textarea
                name="keterangan"
                id="keterangan"
                placeholder="Keterangan"
                class="form-control"
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
  import { Money3Component } from "v-money3";
  import { Money3Directive } from "v-money3";
  
  export default {
    components: { money3: Money3Component },
    directives: { money3: Money3Directive },

    props: {
      selected: {
        type: String,
        default: null,
      },
    },

    data() {
      return {
        config: {
          prefix: "",
          suffix: "",
          thousands: ".",
          decimal: ",",
          precision: 2,
          disableNegative: false,
          disabled: false,
          min: null,
          max: null,
          allowBlank: false,
          minimumNumberOfCharacters: 0,
        },
      };
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
  
      const { data: permintaaninternal } = useQuery(
        ["permintaaninternal", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-permintaaninternal"), 100);
          return axios.get(`/permintaaninternal/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: (data) => (form.value = data),
          onSettled: () => KTApp.unblock("#form-permintaaninternal"),
        }
      );
  
      const { mutate: submit } = useMutation(
        (data) =>
          axios
            .post(
              selected ? `/permintaaninternal/${selected}/update` : "/permintaaninternal/store",
              data
            )
            .then((res) => res.data),
        {
          onMutate: () => {
            KTApp.block("#form-permintaaninternal");
          },
          onError: (error) => {
            toastr.error(error.response.data.message);
          },
          onSettled: () => {
            KTApp.unblock("#form-permintaaninternal");
          },
        }
      );
  
      return {
        permintaaninternal,
        barangmentahs,
        barangjadis,
        submit,
        form,
        queryClient,
      };
    },
    methods: {
      getSatuanJadi() {
        setTimeout(() => {
          var app = this;
          var id = app.form.barangjadi_id;

          var index = app.barangjadis.findIndex((cat) => cat.id == id);
          id = app.barangjadis[index].barangsatuanjadi_id;
          axios
            .get(`barangsatuanjadi/${id}/child`)
            .then((res) => {
              app.form.child = res.data.data;
            })
            .catch((err) => {
              toastr.error("sesuatu error terjadi", "gagal");
            });
        }, 500);
      },

      getSatuanMentah() {
        setTimeout(() => {
          var app = this;
          var id = app.form.barangmentah_id;

          var index = app.barangmentahs.findIndex((cat) => cat.id == id);
          id = app.barangmentahs[index].barangsatuan_id;
          axios
            .get(`barangsatuan/${id}/child`)
            .then((res) => {
              app.form.child = res.data.data;
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
        const data = new FormData(document.getElementById("form-permintaaninternal"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openFormInternal = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/permintaaninternal/paginate"], {
              exact: true,
            });
          },
        });
      },
    },
  };
  </script>
  
  <style></style>
  