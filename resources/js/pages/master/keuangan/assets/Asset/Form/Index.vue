<template>
    <form class="card mb-12" id="form-asset" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ asset?.uuid ? `Edit Jabatan : ${asset.name}` : "Tambah Assets"  }}
          </h3>
          <button
            type="button"
            class="btn btn-light-danger btn-sm ms-auto"
          >
            
            
          </button>
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
        <!------- form ------------>
        <div class="row">
          <div class="col-6">
            <div class="mb-10">
              <label for="code" class="form-label required"> Profile : </label>
              <select2 name="profile_id" id="profile"
                class="form-control" required autoComplete="off" v-model="form.profile_id" >
                <option v-for="profile in profiles" :value="profile.id" :key="profile.uuid">{{ profile.nama }}</option>
              </select2>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-10">
              <label for="code" class="form-label required"> Jenis Asset : </label>
              <select2 name="jenisasset_id" id="jenisasset_id"
                class="form-control" required autoComplete="off" v-model="form.jenisasset_id" >
                <option v-for="jenisasset in jenisasset" :value="jenisasset.id" :key="jenisasset.uuid">{{ jenisasset.nama }}</option>
              </select2>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-1">
            <label for="name" class="form-label required"> No: </label>
            <p>1</p>
          </div>
          <div class="col-3">
            <div class="mb-8">
              <label for="nm_asset" class="form-label required"> Nama Asset : </label>
              <input type="text" name="nm_assets" id="nm_asset" placeholder="Nama Asset"
                class="form-control" required autoComplete="off" v-model="form.nm_assets" />
            </div>
          </div>
          <div class="col-1" style="width: 10%">
            <div class="mb-8">
              <label for="code" class="form-label required"> Tahun : </label>
              <select2 name="tahun" id="tahun"
                class="form-control" required autoComplete="off" v-model="form.tahun" >
                <option
                v-for="tahun in tahuns"
                :value="tahun.id"
                :data="tahun"
                :key="tahun.uuid">
                {{ tahun }}
                </option>
              </select2>
            </div>
          </div>
          <div class="col-2" style="width: 20.6%">
            <div class="mb-8">
              <label for="code" class="form-label required"> Kelompok : </label>
              <select2 name="kelompok_id" id="kelompok_id" @change="tarif($event)"
                class="form-control" required autoComplete="off" v-model="form.kelompok_id" >
                <option disabled>Pilih</option>
                <option v-for="kelompok in kelompoks" :value="kelompok.id" :key="kelompok.uuid">{{ kelompok.nama }}</option>
              </select2>
            </div>
          </div>
          <div class="col-2">
            <div class="mb-8">
              <label for="code" class="form-label required"> Tarif Penyusutan : </label>
              <input type="text" name="tarif" id="tarif" placeholder="Kode"
                class="form-control" required readonly autoComplete="off" v-model="form.tarif" />
            </div>
          </div>
          <div class="col-1" style="width: 11%">
            <div class="mb-8">
              <label for="code" class="form-label required"> Jumlah : </label>
              <input type="text" name="jumlah" id="jumlah" placeholder="Kode"
                class="form-control" required autoComplete="off" v-model="form.jumlah" />
            </div>
          </div>
          <div class="col-1">
            <label for="code" class="form-label required"> Aksi : </label>
            <button type="submit" class="btn btn-sm btn-clean btn-icon">
            <i class="la la-plus fs-2x kt-font-success"></i>
            </button>
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
    data() {
      return {
        tahuns: [],
      };
    },
    props: {
      selected: {
        type: String,
        default: null,
      }
    },
    setup({ selected }) {
      const queryClient = useQueryClient();
      const form = ref({});
  
      const { data: asset } = useQuery(
        ["asset", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-asset"), 100);
          return axios.get(`/asset/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-asset"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/asset/${selected}/update` : '/asset/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-asset");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-asset");
        }
      });

      const { data: kelompoks } = useQuery(["kelompoks"], () => axios.get("/kelompok/get").then((res) => res.data), {
      placeholderData: []
      });
      const { data: profiles } = useQuery(["profiles"], () => axios.get("/profile/get").then((res) => res.data), {
      placeholderData: []
      });
      const { data: jenisasset } = useQuery(["jenisasset"], () => axios.get("/jenisasset/get").then((res) => res.data), {
      placeholderData: []
      });
      
  
      return {
        asset,
        kelompoks,
        profiles,
        jenisasset,
        submit,
        form,
        queryClient
      }
    },
    methods: {

      profile(e){
        var app=this;
        var index = app.kelompoks.findIndex((cat) => cat.id==e);
        app.form.tarif = app.kelompoks[index].tarif;
      },

      tarif(e){
        var app=this;
        var index = app.kelompoks.findIndex((cat) => cat.id==e);
        app.form.tarif = app.kelompoks[index].tarif;
      },

      onUpdateFiles(files) {
        this.file = files;
      },
      onSubmit() {
        const vm = this;
        const data = new FormData(document.getElementById("form-asset"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/asset/paginate"], { exact: true });
          }
        });
      },

      getTahun(){
        var app = this;
        app.tahuns = _.range(new Date().getFullYear(), 1990);

      },
    },
      
    mounted() {
      var app = this;
      app.getTahun();
    },
  };
  </script>
  
  <style>
  </style>