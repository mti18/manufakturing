<template>
  <form class="card mb-12" id="form-supplier" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{ supplier?.uuid ? `Edit Customer : ${supplier.nama}` : "Tambah Data Customer"  }}
        </h3>
        <button
          type="button"
          class="btn btn-light-danger btn-sm ms-auto"
          @click="($parent.openForm = false, $parent.selected = undefined)"
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
            <label for="name" class="form-label required"> Nama : </label>
            <input type="text" name="nama" id="nama" placeholder="Nama Supplier"
              class="form-control" required autoComplete="off"
              @input.prevent="kd" v-model="form.nama" />
          </div>
        </div>
        <div class="col-6">
          <!-- <div class="mb-3">
            <label class="form-label required"> Account : </label>
            <select name="provinsi" id="alamat" placeholder="Alamat"
              class="form-control" required autoComplete="off" v-model="form.alamat" />
              <option></option>
          </div> -->
          <div class="mb-8">
            <label class="form-label required"> Telepon : </label>
            <input type="text" name="telepon" id="telepon" placeholder="Telepon  Supplier"
              class="form-control" required autoComplete="off" v-model="form.telepon" />
          </div>
          <div class="mb-8">
            <label class="form-label required"> Kode : </label>
            <input type="text" name="kode" id="kode" placeholder="Kode"
              class="form-control" readonly required autoComplete="off" v-model="form.kode" />
          </div>
          <div class="mb-8">
            <label class="form-label required"> NPWP : </label>
            <input type="text" name="npwp" id="npwp" placeholder="NPWP"
              class="form-control" oninput="this.value = this.value.replace(/\D/g, '')" v-mask="'99.999.999.9-999.999'" v-model="form.npwp" />
          </div>
          <div class="mb-8">
            <label class="form-label required"> NPPKP : </label>
            <input type="text" name="nppkp" id="nppkp" placeholder="NPPKP"
              class="form-control"  v-model="form.nppkp" />
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Provinsi: </label>
            <select2 name="provinsi_id" id="provinsi_id"
              class="form-control" required autoComplete="off" v-model="form.provinsi_id" >
              <option v-for="provinsi in provinsis" :value="provinsi.id" :key="provinsi.uuid">{{ provinsi.nm_provinsi }}</option>
            </select2>
          </div>
          <div class="mb-8">
            <label for="name" class="form-label required"> Kota/Kabupaten: </label>
            <select2 name="kab_kota_id" id="kab_kota_id"
              class="form-control" required autoComplete="off" v-model="form.kab_kota_id" >
              <option v-for="kota in kotas" :value="kota.id" :key="kota.uuid">{{ kota.nm_kab_kota }}</option>
            </select2>
          </div>
          <div class="mb-8">
            <label for="name" class="form-label required"> Kecamatan: </label>
            <select2 name="kecamatan_id" id="kecamatan_id"
              class="form-control" required autoComplete="off" v-model="form.kecamatan_id" >
              <option v-for="kecamatan in kecamatans" :value="kecamatan.id" :key="kecamatan.uuid">{{ kecamatan.nm_kecamatan }}</option>
            </select2>
          </div>
          <div class="mb-8">
            <label class="form-label required"> Nama Kontak Person : </label>
            <input type="text" name="nama_kontak" id="nama_kontak" placeholder="Nama Kontak Person"
              class="form-control"  v-model="form.namakontak" />
          </div>
          <div class="mb-8">
            <label class="form-label required"> Telepon Kontak Person : </label>
            <input type="text" name="telp_kontak" id="telp_kontak" placeholder="Telepon Kontak Person"
              class="form-control"  v-model="form.telpkontak" />
          </div>
        </div>
        <div class="col-12">
          <div class="mb-8">
            <label class="form-label required"> Alamat : </label>
            <textarea name="alamat" id="alamat" placeholder="Alamat"
              class="form-control" required autoComplete="off" v-model="form.alamat" />
          </div>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary btn-sm ms-auto mt-8 d-block">
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
    }
  },

  data() {
    return {
      code: null,
    };
  },

  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({});

    const { data: supplier } = useQuery(
      ["supplier", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-supplier"), 100);
        return axios.get(`/customer/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: data => form.value = data,
        onSettled: () => KTApp.unblock("#form-supplier"),
      }
    );

    const { mutate: submit } = useMutation((data) => axios.post(selected ? `/customer/${selected}/update` : '/customer/store', data).then(res => res.data), {
      onMutate: () => {
        KTApp.block("#form-supplier");
      },
      onError: (error) => {
        toastr.error(error.response.data.message);
      },
      onSettled: () => {
        KTApp.unblock("#form-supplier");
      }
    });

    const { data: provinsis } = useQuery(["provinsis"], () => axios.get("/provinsi/get").then((res) => res.data), {
    placeholderData: []
    });
    const { data: kotas } = useQuery(["kotas"], () => axios.get("/kota/get").then((res) => res.data), {
    placeholderData: []
    });
    const { data: kecamatans } = useQuery(["kecamatans"], () => axios.get("/kecamatan/get").then((res) => res.data), {
    placeholderData: []
    });

    return {
      supplier,
      submit,
      form,
      provinsis,
      kotas,
      kecamatans,
      queryClient
    }
  },
  methods: {

    kd: _.debounce(function () {
      var vm = this;
      var myString = this.form.nama;
      var splits = myString.split(" ");
      var codes = "";
      for (var i = 0; i < splits.length; i++) {
        if (splits[i][0]) {
          codes = codes + splits[i][0];
        }
      }
      if (this.type == "store") {
        if (codes != "") {
          vm.kode =
            "C" + codes.replace(/[^A-Za-z]/g, "").toUpperCase() + "-" + vm.code;
          vm.form.kode = vm.kode;
          $(".codes").val(vm.kode);
        } else {
          vm.kode = "";
          vm.form.kode = vm.kode;
          $(".codes").val(vm.kode);
        }
      } else {
        if (codes != "") {
          vm.kode =
            "C" + codes.replace(/[^A-Za-z]/g, "").toUpperCase() + "-" + vm.code;
          vm.form.kode = vm.kode;
            $(".codes").val(vm.kode);
          } else {
            vm.kode = "";
            vm.form.kode = vm.kode;
            $(".codes").val(vm.kode);
          }
        }
      }, 1000),

    getcode() {
      this.$http
        .get("customer/getcode")
        .then((res) => {
          console.log(res.data)
          this.code = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    editGetcode() {
      this.$http
        .get("customer/" + this.selected + "/getcode")
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
      const data = new FormData(document.getElementById("form-supplier"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/customer/paginate"], { exact: true });
        }
      });
    }
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

<style>
</style>