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
              name="barangsatuan_id"
              placeholder="Pilih Nama Satuan"
              id="barangsatuan_id"
              @change="getChild()"
              v-model="form.barangsatuan_id"
              required
            >
              <option value="" disabled>Pilih Satuan</option>
              <option
                v-for="item in barangsatuan"
                :value="item.id"
                :key="item.id"
              >
                {{ item.nm_satuan }}
              </option>
            </select2>
          </div>
        </div>

        <div class="col-6">
          <div class="mb-8">
            <label for="nm_satuan_child" class="form-label required">
              Satuan :
            </label>
            <select2
              class="form-control"
              name="satuan"
              placeholder="Pilih Satuan"
              id="satuan"
              v-model="form.satuan"
              :disabled="
                form.barangsatuan_id == undefined || form.barangsatuan_id == ''
              "
              required
            >
              <option value="" disabled>Pilih Satuan</option>
              <option
                v-for="item in satuan_child"
                :value="item.id"
                :key="item.id"
              >
                {{ item.nm_satuan_children }}
              </option>
            </select2>
          </div>
        </div>

        <div class="col-md-12 form-group">
          <label class="required form-label">Kategori</label>
          <div
            class="form-check mb-2 form-check-custom form-check-solid form-check-sm"
            v-for="item in kategoris"
            :value="item.id"
          >
            <input
              class="form-check-input"
              type="checkbox"
              :value="item.id"
              id="flexRadioLg"
              @change="addkategori(item)"
              :checked="
                form.kategoris?.findIndex((cat) => cat.id == item.id) != -1
                  ? true
                  : false
              "
            />
            <label class="form-check-label" for="flexRadioLg">
              {{ item.nm_kategori }}
            </label>
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
      satuan_child: [],
    };
  },
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({
      kategoris: [],
    });

    const { data: barangsatuan } = useQuery(
      ["barang_satuans"],
      () => axios.get("/barangsatuan/get").then((res) => res.data),
      {
        placeholderData: [],
      }
    );

    const { data: kategoris = [] } = useQuery(["kategoris"], () =>
      axios.get("/kategori/get").then((res) => res.data)
    );

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
      barangsatuan,
      kategoris,
      submit,
      form,
      queryClient,
    };
  },
  methods: {
    getChild() {
      setTimeout(() => {
        var app = this;
        var id = app.form.barangsatuan_id;
        axios
          .get(`barangsatuan/${id}/child`)
          .then((res) => {
            app.satuan_child = res.data.data;

            if (app.form.satuan_id != "" && app.form.satuan_id != undefined) {
              app.form.satuan = app.form.satuan_id;
            }
          })
          .catch((err) => {
            toastr.error("sesuatu error terjadi", "gagal");
          });
      }, 500);
    },

    addkategori(item) {
      var app = this;

      // console.log(app.form.kategoris);
      // return;

      var index = app.form.kategoris.findIndex((cat) => cat.id == item.id);

      if (index == -1) {
        app.form.kategoris.push(item);
      } else {
        app.form.kategoris.splice(index, 1);
      }
    },

    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      var data = vm.form;

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
