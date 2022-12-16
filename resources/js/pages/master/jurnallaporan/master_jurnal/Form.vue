<template>
  <form class="card mb-12" id="form-masterjurnal" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            masterjurnal?.uuid
              ? `Edit Jurnal : ${masterjurnal.kd_jurnal}`
              : "Tambah Jurnal"
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
        <div class="mb-8">
          <label for="kd_jurnal" class="form-label required"> Kode : </label>
          <input
            type="text"
            name="kd_jurnal"
            id="kd_jurnal"
            placeholder="Code System "
            class="form-control"
            required
            autoComplete="off"
            readonly="true"
            v-model="form.kd_jurnal"
          />
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="type" class="form-label required"> Tipe : </label>
            <select2
              class="form-control"
              placeholder="Pilih Tipe"
              search="hide"
              v-on:change="getCode()"
              name="type"
              v-model="form.type"
            >
              <option value="umum">Umum</option>
              <option value="penyesuaian">Penyesuaian</option>
              <option value="penutup">Penutup</option>
            </select2>
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="tanggal" class="form-label required input-date">
              Tanggal :
            </label>
            <input
              type="date"
              name="tanggal"
              id="tanggal"
              placeholder="Pilih Tanggal"
              @change.prevent="getCode()"
              :disabled="form.type == 'edit' ? true : false"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.tanggal"
            />
          </div>
        </div>

        <div class="col-6">
          <label for="upload" class="form-label required">
            Upload Bukti :
          </label>
          <file-upload
            :files="selected && form?.upload ? `/${form.upload}` : fileUpload"
            :allow-multiple="true"
            v-on:updatefiles="onUpdateFilesUpload"
            labelIdle='Drag & Drop your files or <span class ="filepond-label-action">Browse</span>'
            required
            :accepted-file-types="['image/*']"
          ></file-upload>
        </div>
      </div>

      <hr />

      <div class="row" v-for="(item, index) in form.jurnal_items">
        <div class="col-2">
          <div class="mb-8">
            <label for="nm_account" class="form-label required">
              Account :
            </label>
            <select2
              class="form-control"
              name="account_id"
              placeholder="Pilih Account"
              :id="'account_id' + index"
              @change="saldo($event, index)"
              v-model="form.jurnal_items[index].account_id"
              required
            >
              <option value="" disabled>Pilih Account</option>
              <option
                v-for="item in account"
                :disabled="
                  form.jurnal_items.findIndex(
                    (el) => el.account_id == item.id
                  ) != -1
                "
                :value="item.id"
              >
                {{ item.kode_account }} - {{ item.nm_account }}
              </option>
              <!-- <option v-for="kitem in account" :value="kitem.id" :key="kitem.id">
                {{ kitem.nm_account }} - {{kitem.kode_account}}
              </option> -->
            </select2>
          </div>
        </div>

        <div class="col-2" v-if="item.account_id == null">
          <div class="mb-8">
            <label for="nm_account" class="form-label required">
              Debit :
            </label>

            <div class="input-group">
              <input
                type="text"
                placeholder="Isi Nominal"
                v-money3="{
                  thousands: '.',
                  decimal: ',',
                  precision: 2,
                }"
                class="form-control"
                @change="hitungSaldo()"
                oninput="this.value = this.value.rupiah()"
                name="debit"
                disabled
                v-model="form.jurnal_items[index].debit"
                required
              />
            </div>
          </div>
        </div>

        <div class="col-2" v-else>
          <div class="mb-8">
            <label for="nm_account" class="form-label required">
              Debit :
            </label>

            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Rp</span>
              </div>
              <input
                type="text"
                placeholder="Isi Nominal"
                class="form-control"
                v-money3="{
                  thousands: '.',
                  decimal: ',',
                  precision: 2,
                }"
                @change="hitungSaldo()"
                oninput="this.value = this.value.rupiah()"
                name="debit"
                v-model="form.jurnal_items[index].debit"
                required
              />
            </div>
          </div>
        </div>

        <div class="col-2" v-if="item.account_id == null">
          <div class="mb-8">
            <label for="nm_account" class="form-label required">
              Kredit :
            </label>

            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Rp</span>
              </div>
              <input
                type="text"
                id="kredit"
                placeholder="Isi Nominal"
                oninput="this.value = this.value.rupiah()"
                v-money3="{
                  thousands: '.',
                  decimal: ',',
                  precision: 2,
                }"
                @change="hitungSaldo()"
                name="kredit"
                disabled
                class="form-control"
                required
                v-model="form.jurnal_items[index].kredit"
              />
            </div>
          </div>
        </div>

        <div class="col-2" v-else>
          <div class="mb-8">
            <label for="nm_account" class="form-label required">
              Kredit :
            </label>

            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Rp</span>
              </div>
              <input
                type="text"
                placeholder="Isi Nominal"
                oninput="this.value = this.value.rupiah()"
                v-money3="{
                  thousands: '.',
                  decimal: ',',
                  precision: 2,
                }"
                @change="hitungSaldo()"
                name="kredit"
                class="form-control"
                required
                v-model="form.jurnal_items[index].kredit"
              />
            </div>
          </div>
        </div>

        <div :class="item.type != 'edit' ? 'col-md-3' : 'col-md-5'">
          <div class="mb-4">
            <label class="required form-label">Keterangan :</label>
            <input
              type="text"
              class="form-control"
              placeholder="Keterangan/narasi"
              name="keterangan"
              v-model="form.jurnal_items[index].keterangan"
            />
          </div>
        </div>
        <div class="col-md-2" v-if="form.jurnal_items[index].type != 'edit'">
          <div class="mb-4">
            <label class="form-label">Saldo :</label>
            <input
              type="text"
              disabled
              class="form-control"
              placeholder="Saldo"
              name="saldo"
              v-model="form.jurnal_items[index].saldo"
            />
          </div>
        </div>

        <div class="col-1">
          <div class="mt-9 mb-8">
            <div class="delete btn btn-sm btn-icon btn-danger">
              <i
                class="la la-trash fs-2"
                @click.prevent="delJurnalItems(index)"
              ></i>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-11"></div>
        <div class="col-md-1">
          <button
            class="btn btn-block btn-light-success"
            type="button"
            title="tambah aksi"
            @click.prevent="addJurnalItems()"
          >
            <i class="fa fa-plus"></i>
          </button>
        </div>
      </div>
      <hr />
      <div class="row">
        <div class="col-md-3">
          Debit: {{ debit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,") }}
        </div>
        <div class="col-md-3">
          Kredit: {{ kredit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,") }}
        </div>
        <div class="col-md-3">
          Selisih:
          {{ (debit - kredit).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,") }}
        </div>
        <div class="col-md-3">
          status: {{ debit == kredit ? "balance" : "tidak balance" }}
        </div>
      </div>
      <hr />

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
import { Money3Directive } from "v-money3";
import { ref } from "vue";
import { useQuery, useMutation } from "vue-query";
import axios from "@/libs/axios";
import { useQueryClient } from "vue-query";

export default {
  directives: { money3: Money3Directive },
  props: {
    selected: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      debit: 0,
      kredit: 0,
      formRequest: {
        saldo: "",
      },
    };
  },
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({
      jurnal_items: [{}],
    });
    const fileUpload = ref([]);

    const { data: account } = useQuery(["accounts"], () =>
      axios.get("/masterjurnal/child").then((res) => res.data)
    );

    const { data: masterjurnal } = useQuery(
      ["masterjurnal", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-masterjurnal"), 100);
        return axios
          .get(`/masterjurnal/${selected}/edit`)
          .then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-masterjurnal"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected
              ? `/masterjurnal/${selected}/update`
              : "/masterjurnal/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-masterjurnal");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-masterjurnal");
        },
      }
    );

    return {
      account,
      fileUpload,
      masterjurnal,
      submit,
      form,
      queryClient,
    };
  },
  methods: {
    onUpdateFiles(files) {
      this.file = files;
    },
    onUpdateFilesUpload(filesUpload) {
      this.fileUpload = filesUpload;
    },
    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-masterjurnal"));
      data.append("upload", this.fileUpload[0].file);
      vm.form.jurnal_items.forEach((item, i) => {
        data.append(`jurnal_items[${i}][account_id]`, item.account_id);
        data.append(`jurnal_items[${i}][debit]`, item.debit);
        data.append(`jurnal_items[${i}][kredit]`, item.kredit);
        data.append(`jurnal_items[${i}][keterangan]`, item.keterangan);
        // data.append('jurnal_items[].keterangan', item.saldo);
      });
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/masterjurnal/paginate"], {
            exact: true,
          });
        },
      });
    },

    loadDate() {
      var vm = this;

      var max = new Date(
        vm.$parent.formRequest.tahun,
        vm.$parent.formRequest.bulan,
        0
      );

      var min = new Date(
        vm.$parent.formRequest.tahun,
        vm.$parent.formRequest.bulan - 1
      );

      setTimeout(function () {
        $(".input-date").flatpickr({
          enableTime: false,
          dateFormat: "Y-m-d",
          minDate: `${min.getFullYear()}-${
            min.getMonth() + 1
          }-${min.getDate()}`,
          maxDate: `${vm.$parent.formRequest.tahun}-${
            vm.$parent.formRequest.bulan
          }-${max.getDate()}`,
        });

        $(".input-date")
          .val(vm.form.tanggal)
          .on("change", function (val) {
            vm.form.tanggal = val;
          });
      }, 100);
    },
    getCode() {
      var app = this;
      var bulan = app.$parent.formRequest.bulan;
      var tahun = app.$parent.formRequest.tahun;
      var type = app.form.type;
      if (
        bulan == "" ||
        (bulan == null && tahun == "") ||
        (tahun == null && type == "") ||
        type == null
      ) {
      } else {
        setTimeout(() => {
          app.axios
            .post("masterjurnal/getCode", {
              tanggal: app.form.tanggal,
              bulan: bulan,
              tahun: tahun,
              type: type,
            })
            .then(function (response) {
              app.form.kd_jurnal = response.data;
            });
        }, 200);
      }
    },
    addJurnalItems() {
      this.form.jurnal_items.push({});
    },
    delJurnalItems(index) {
      this.form.jurnal_items.splice(index, 1);
    },
    hitungSaldo() {
      var app = this;

      var debit = 0;
      var kredit = 0;

      for (let index = 0; index < app.form.jurnal_itemsL.length; index++) {
        const element = app.form.jurnal_items[index];
        debit += parseFloat(
          element.debit.replaceAll(".", "").replaceAll(",", ".")
        );
        kredit += parseFloat(
          element.kredit.replaceAll(".", "").replaceAll(",", ".")
        );
      }
      app.debit = debit;
      app.kredit = kredit;
    },
    saldo(event, i) {
      var app = this;

      var ic = app.account.findIndex(function (item) {
        return item.id == event;
      });

      app.changeSaldo(i, ic);
    },
    changeSaldo(i, ic) {
      var app = this;
      app.form.jurnal_items[i].saldo = app.account[ic].saldo;
    },
    // getAccount() {
    //   setTimeout(() => {
    //     var app = this;
    //     var app = app.form.account_id;
    //     axios
    //       .get(`masterjurnal/child`)
    //       .then((res) => {
    //         app.child= res.data;
    //       })
    //       .catch((err) => {
    //         toastr.error("sesuatu error terjadi", "gagal");
    //       });
    //   }, 500);
    // },
  },

  mounted() {
    this.loadDate();
  },
};
</script>

<style></style>
