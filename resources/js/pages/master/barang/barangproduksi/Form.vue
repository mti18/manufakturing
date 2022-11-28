<template>
  <form class="card mb-12" id="form-barangproduksi" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            barangproduksi?.uuid
              ? `Edit Barang Produksi : ${barangproduksi.barangproduksibarangjadi.nm_barang_jadi}`
              : "Tambah Barang Produksi"
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
            <label for="nm_satuan_jadi" class="form-label required">
              Barang Jadi yang Akan Diproduksi :
            </label>
            <select2
              class="form-control"
              name="barangjadi_id"
              placeholder="Pilih Barang Jadi yang Akan Diproduksi"
              id="barangjadi_id"
              @change="satuanjadichild()"
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

        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required">
              Stok Produksi :
            </label>
            <input
              type="number"
              name="stok_jadi"
              id="name"
              placeholder="Stok Jadi"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.stok_jadi"
            />
          </div>
        </div>

        <div class="col-6">
          <div class="mb-8">
            <label for="nm_satuan_jadi" class="form-label required">
              Satuan Produksi :
            </label>
            <select2
              class="form-control"
              name="satuan_produksi"
              placeholder="Pilih Barang Jadi yang Akan Diproduksi"
              id="satuan_produksi"
              v-model="form.satuan_produksi"
              required
            >
              <option value="" disabled>Pilih Barang Jadi</option>
              <option
                v-for="item in form.satuan_jadi_child"
                :value="item.id"
                :key="item.id"
                :selected="
                  item.nm_satuan_jadi_children ==
                  form.satuan_jadi_child[0].nm_satuan_jadi_children
                "
              >
                {{ item.nm_satuan_jadi_children }}
              </option>
            </select2>
          </div>
        </div>

        <hr class="mb-8" />

        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr
                class="fw-bold fs-6 text-gray-800"
                style="border-bottom: solid"
              >
                <th>#</th>
                <th>Barang Mentah yang Tersedia</th>
                <th>Stok yang Dipakai</th>
                <th>Satuan</th>
                <th>
                  <a href="javascript:void(0)">
                    <i
                      @click.prevent="plus()"
                      class="la la-plus icon-lg text-success mr-5"
                      style="font-size: 25px"
                    ></i>
                  </a>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in form.barangproduksibarangmentahs">
                <td>{{ index + 1 }}</td>
                <td>
                  <select2
                    class="form-control"
                    placeholder="Pilih Barang Mentah"
                    name="barang_mentah_id"
                    :id="'barangproduksibarangmentahs' + index"
                    v-model="item.barang_mentah_id"
                    @change="satuanchild(index, $event)"
                    required
                  >
                    <option value="" disabled>Pilih Barang Mentah</option>
                    <option
                      v-for="item in barangmentahs"
                      :value="item.id"
                      :key="item.id"
                      :disabled="
                        form.barangproduksibarangmentahs.findIndex(
                          (cat) => cat.barang_mentah_id == item.id
                        ) == -1
                          ? false
                          : true
                      "
                    >
                      {{ item.nm_barangmentah }}
                    </option>
                  </select2>
                </td>
                <td>
                  <input
                    type="number"
                    name="stok_digunakan"
                    id="name"
                    placeholder="Stok yang Digunakan"
                    class="form-control"
                    requiredautoComplete="off"
                    v-model="item.stok_digunakan"
                  />
                </td>
                <td>
                  <select2
                    class="form-control"
                    placeholder="Pilih Satuan"
                    name="satuan_id"
                    v-model="item.satuan_id"
                    required
                  >
                    <option value="" disabled>Pilih Satuan</option>
                    <option
                      v-for="sc in item.satuan_child"
                      :value="sc.id"
                      :key="sc.id"
                    >
                      {{ sc.nm_satuan_children }}
                    </option>
                  </select2>
                </td>
                <td>
                  <a href="javascript:void(0)">
                    <i
                      @click.prevent="minus(index)"
                      class="la la-minus icon-lg text-danger mr-5"
                      style="font-size: 25px"
                    ></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
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
    return {};
  },
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({
      barangproduksibarangmentahs: [{}],
    });

    const { data: barangmentahs = [] } = useQuery(["barang_mentahs"], () =>
      axios.get("/barangmentah/get").then((res) => res.data)
    );

    const { data: barangjadis } = useQuery(["barang_jadis"], () =>
      axios.get("/barangjadi/get").then((res) => res.data)
    );

    const { data: barangproduksi } = useQuery(
      ["barangproduksi", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-barangproduksi"), 100);
        return axios
          .get(`/barangproduksi/${selected}/edit`)
          .then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-barangproduksi"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected
              ? `/barangproduksi/${selected}/update`
              : "/barangproduksi/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-barangproduksi");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-barangproduksi");
        },
      }
    );

    return {
      barangproduksi,
      barangmentahs,
      barangjadis,
      submit,
      form,
      queryClient,
    };
  },
  methods: {
    plus() {
      this.form.barangproduksibarangmentahs.push({
        barang_mentah_id: null,
        stok_digunakan: null,
        satuan_id: null,
      });
    },

    minus(index) {
      this.form.barangproduksibarangmentahs.splice(index, 1);
    },

    satuanchild(index, satuan_id) {
      var app = this;
      var i = app.barangmentahs.findIndex((cat) => cat.id == satuan_id);

      satuan_id = app.barangmentahs[i]?.barangsatuan_id;

      var app = this;
      axios.get(`barangsatuan/${satuan_id}/child`).then((res) => {
        app.form.barangproduksibarangmentahs[index].satuan_child =
          res.data.data;
      });
    },

    satuanjadichild() {
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
      vm.form.barangproduksibarangmentahs;
      this.submit(vm.form, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/barangproduksi/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
  mounted() {},
};
</script>

<style></style>
