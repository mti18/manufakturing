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
        <div class="col-4">
          <div class="mb-6">
            <label for="name" class="form-label"> Foto : </label>
            <file-upload
              :files="selected && form?.foto ? `/${form.foto}` : file"
              :allow-multiple="false"
              v-on:updatefiles="onUpdateFiles"
              labelIdle='Drag & Drop your files or <span class="filepond--label-action">Browse</span>'
              required
              :accepted-file-types="['image/*']"
            ></file-upload>
          </div>
        </div>

        <div class="col-4">
          <div class="mb-8">
            <label for="name" class="form-label required"> Kode : </label>
            <input
              readonly
              type="text codes"
              name="kd_barang_mentah"
              id="name"
              placeholder="Kode (digenerate oleh system)"
              class="form-control"
              autoComplete="off"
              v-model="form.kd_barang_mentah"
            />
          </div>
        </div>

        <div class="col-4">
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
              @input.prevent="kd"
              autoComplete="off"
              v-model="form.nm_barangmentah"
            />
          </div>
        </div>
      </div>

      <div class="row">
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
      </div>
      <div class="col-6">
        <div class="mb-8">
          <label for="nm_account" class="form-label required"> Harga : </label>

          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">Rp</span>
            </div>
            <money3
              v-model="form.harga"
              id="harga"
              class="form-control"
              type="text"
              name="harga"
              v-bind="config"
              required
            ></money3>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="nm_gudang" class="form-label required">
              Gudang :
            </label>
            <select2
              class="form-control"
              name="gudang_id"
              placeholder="Pilih Gudang"
              id="gudang_id"
              @change="getRak()"
              v-model="form.gudang_id"
              required
            >
              <option value="" disabled>Pilih Gudang</option>
              <option v-for="item in gudang" :value="item.id" :key="item.id">
                {{ item.nm_gudang }}
              </option>
            </select2>
          </div>
        </div>

        <div class="col-6" v-if="!selected || form.rak_id">
          <div class="mb-8">
            <label for="nm_satuan_child" class="form-label required">
              Rak :
            </label>
            <select2
              class="form-control"
              name="rak_id"
              placeholder="Pilih Rak"
              id="rak_id"
              v-model="form.rak_id"
              :disabled="form.gudang_id == undefined || form.gudang_id == ''"
              required
            >
              <option value="" disabled>Pilih Rak</option>
              <option v-for="item in rak" :value="item.id" :key="item.id">
                {{ item.nm_rak }}
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
              name="barangmentahkategoris[]"
              @change="addkategori(item)"
              :checked="
                form.barangmentahkategoris?.findIndex(
                  (cat) => cat.id == item.id
                ) != -1
                  ? true
                  : false
              "
            />
            <label class="form-check-label" for="flexRadioLg">
              {{ item.nm_kategori }}
            </label>
          </div>
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
      satuan_child: [],
      code: null,
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
    const rak = ref([]);
    const form = ref({
      barangmentahkategoris: [],
    });
    const file = ref([]);

    const { data: gudang } = useQuery(["gudangs"], () =>
      axios.get("/gudang/get").then((res) => res.data)
    );
    const { data: barangsatuan } = useQuery(["barang_satuans"], () =>
      axios.get("/barangsatuan/get").then((res) => res.data)
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
      gudang,
      rak,
      submit,
      form,
      file,
      queryClient,
    };
  },
  methods: {
    // getChild() {
    //   setTimeout(() => {
    //     var app = this;
    //     var id = app.form.barangsatuan_id;
    //     axios
    //       .get(`barangsatuan/${id}/child`)
    //       .then((res) => {
    //         app.satuan_child = res.data.data;

    //         if (app.form.satuan_id != "" && app.form.satuan_id != undefined) {
    //           app.form.satuan = app.form.satuan_id;
    //         }
    //       })
    //       .catch((err) => {
    //         toastr.error("sesuatu error terjadi", "gagal");
    //       });
    //   }, 500);
    // },

    addkategori(item) {
      var app = this;

      // console.log(app.form.kategoris);
      // return;

      var index = app.form.barangmentahkategoris.findIndex(
        (cat) => cat.id == item.id
      );

      if (index == -1) {
        app.form.barangmentahkategoris.push(item);
      } else {
        app.form.barangmentahkategoris.splice(index, 1);
      }
    },

    getRak() {
      setTimeout(() => {
        var app = this;
        var id = app.form.gudang_id;
        axios
          .get(`gudang/${id}/rak`)
          .then((res) => {
            app.rak = res.data.data;
          })
          .catch((err) => {
            toastr.error("sesuatu error terjadi", "gagal");
          });
      }, 500);
    },

    kd() {
      var vm = this;
      var myString = this.form.nm_barangmentah;
      var splits = myString.split(" ");
      var codes = "";
      for (var i = 0; i < splits.length; i++) {
        if (splits[i][0]) {
          codes = codes + splits[i][0];
        }
      }
      if (this.type == "store") {
        if (codes != "") {
          vm.kd_barang_mentah =
            codes.replace(/[^A-Za-z]/g, "").toUpperCase() +
            "BM" +
            "-" +
            vm.code;
          vm.form.kd_barang_mentah = vm.kd_barang_mentah;
          $(".codes").val(vm.kd_barang_mentah);
        } else {
          vm.kd_barang_mentah = "";
          vm.form.kd_barang_mentah = vm.kd_barang_mentah;
          $(".codes").val(vm.kd_barang_mentah);
        }
      } else {
        if (codes != "") {
          vm.kd_barang_mentah =
            codes.replace(/[^A-Za-z]/g, "").toUpperCase() +
            "BM" +
            "-" +
            vm.code;
          vm.form.kd_barang_mentah = vm.kd_barang_mentah;
          $(".codes").val(vm.kd_barang_mentah);
        } else {
          vm.kd_barang_mentah = "";
          vm.form.kd_barang_mentah = vm.kd_barang_mentah;
          $(".codes").val(vm.kd_barang_mentah);
        }
      }
    },

    getcode() {
      this.$http
        .get("barangmentah/getcode")
        .then((res) => {
          console.log(res.data);
          this.code = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    editGetcode() {
      this.$http
        .get("barangmentah/" + this.selected + "/getcode")
        .then((res) => {
          console.log(res.data);
          this.code = res.data;
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
      var data = new FormData(document.getElementById("form-barangmentah"));
      data.append("foto", this.file[0].file);
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
  mounted() {
    if (!this.selected) {
      this.getcode();
      return;
    }
    this.editGetcode();
  },
};
</script>

<style></style>
