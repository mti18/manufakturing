<template>
    <form class="card mb-12" id="form-asset" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ asset?.uuid ? `Edit  : Asset ${asset.name}` : "Tambah Assets"  }}
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
                class="form-control" disabled  autoComplete="off" v-model="profile_id" >
                <option value="" disabled></option>
                <option v-for="profile in profiles" :value="profile.id" :key="profile.uuid"> {{ profile.nama }} </option>
              </select2>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-10">
              <label for="code" class="form-label required"> Jenis Asset : </label>
              <select2 name="jenisasset_id" id="jenisasset_id"
                class="form-control" disabled  autoComplete="off" v-model="jenisasset_id" >
                <option value="" disabled></option>
                <option v-for="jenisasset in jenisasset" :value="jenisasset.id" :key="jenisasset.uuid">{{ jenisasset.nama }}</option>
              </select2>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-1">
            <label for="name" class="form-label required"> No: </label>
          </div>
          <div class="col-3">
            <div class="mb-8">
              <label for="nm_asset" class="form-label required"> Nama Asset : </label>
            </div>
          </div>
          <div class="col-1" style="width: 10%">
            <div class="mb-8">
              <label for="code" class="form-label required"> Tahun : </label>
            </div>
          </div>
          <div class="col-2" style="width: 20.6%">
            <div class="mb-8">
              <label for="code" class="form-label required"> Kelompok : </label>
            </div>
          </div>
          <div class="col-2">
            <div class="mb-8">
              <label for="code" class="form-label required"> Tarif Penyusutan : </label>
            </div>
          </div>
          <div class="col-1" style="width: 11%">
            <div class="mb-8">
              <label for="code" class="form-label required"> Jumlah : </label>
            </div>
          </div>
          <div class="col-1">
            <label for="code" class="form-label required"><button type="submit" class="btn btn-sm btn-clean btn-icon" @click.prevent="plus()">
            <i class="la la-plus fs-2x kt-font-success"></i>
            </button></label>
          </div>
        </div>
        <div v-for="(form, index) in form.assetdetail">
          <div class="row">
            <div class="col-1">
              <label>{{ index + 1 }}</label>
            </div>
            <div class="col-3">
              <div class="mb-8">
                {{ form.nm_assets }}
              </div>
            </div>
            <div class="col-1" style="width: 10%">
              <div class="mb-8">
                {{ form.tahun }}
              </div>
            </div>
            <div class="col-2" style="width: 20.6%">
              <div class="mb-8">
                {{ form.kelompok_id }}
              </div>
            </div>
            <div class="col-2" >
              <div class="mb-8">
                {{ form.tarif }}
              </div>
            </div>
            
            <div class="col-1" style="width: 11%">
              <div class="mb-8">
                {{ form.jumlah }}
              </div>
            </div>
            <div class="col-1">
              <button type="submit" class="btn btn-sm btn-clean btn-icon" @click.prevent="minus(index)">
              <i class="la la-minus fs-2x kt-font-success"></i>
              </button>   
            </div>
          </div>   
        </div>
        <div class="row assetdetail">
          <div class="col-1">
            <label>{{ form.assetdetail.length + 1  }}</label>
          </div>
          <div class="col-3">
            <div class="mb-8">
              <input type="text" name="nm_assets" id="nm_asset" placeholder="Nama Asset"
                class="form-control" required autoComplete="off" v-model="formAssetDetail.nm_assets" />
            </div>
          </div>
          <div class="col-1" style="width: 10%">
            <div class="mb-8">
              <select2 name="tahun" 
                class="form-control" required autoComplete="off" v-model="formAssetDetail.tahun" >
                <option value="" disabled></option>
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
              <select2 name="kelompok_id"  @change="tarif($event, index)"
                class="form-control" required autoComplete="off" v-model="formAssetDetail.kelompok_id" >
                <option value="" disabled>Pilih</option>
                <option v-for="kelompok in kelompoks" 
                :value="kelompok.id" 
                :key="kelompok.uuid"
                
                >
                {{ kelompok.nama }}</option>
              </select2>
            </div>
          </div>
          <div class="col-2" >
            <div class="mb-8">
              <input type="text" name="tarif" id="tarif" placeholder="Tarif Penyusutan"
                class="form-control" required disabled autoComplete="off" v-model="formAssetDetail.tarif" />
            </div>
          </div>
          
          <div class="col-1" style="width: 11%">
            <div class="mb-8">
              <input type="text" name="jumlah" id="jumlah" placeholder="Jumlah"
                class="form-control" required autoComplete="off" v-model="formAssetDetail.jumlah" />
            </div>
          </div>
          <div class="col-1">
            <button type="submit" class="btn btn-sm btn-clean btn-icon" @click.prevent="plus()">
            <i class="la la-plus fs-2x kt-font-success"></i>
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
        jenisasset_id: this.$parent.selected,
      profile_id: this.$parent.$parent.selected
      };
    },
    props: {
      selected: {
        type: String,
        default: null,
      },
    },
    setup({ selected }) {
      const queryClient = useQueryClient();
      const form = ref({
        assetdetail: [{}],
      });


      
      const formAssetDetail = ref({});
      const { data: kelompoks } = useQuery(["kelompoks"], () => axios.get("/kelompok/get").then((res) => res.data), {
      });
      const { data: profiles } = useQuery(["profiles"], () => axios.get("/profile/get").then((res) => res.data), {
      });
      const { data: jenisasset } = useQuery(["jenisasset"], () => axios.get("/jenisasset/get").then((res) => res.data), {
      });
  
      const { data: jenisassetEdit } = useQuery(
        ["jenisasset", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-asset"), 100);
          return axios.get(`/jenisasset/${selected}/edit`).then((res) => res.data);
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

      
      
  
      return {
        jenisassetEdit,
        kelompoks,
        profiles,
        jenisasset,
        submit,
        form,
        queryClient,
        formAssetDetail
      }
    },
    methods: {
      plus() {
      var app = this;
      KTApp.block(" .assetdetail",{
          overlayColor:"#000000",
          type:"v2",
          state:"primary",
          message:"Processing...",
          opacity: 0.2
      });
      app.formAssetDetail.jenisasset_id = app.form.id
      app.axios.post('asset/store', app.formAssetDetail).then((res) => {
        toastr.success(res.data.message, 'success');
        KTApp.unblock(" .assetdetail",{
          overlayColor:"#000000",
          type:"v2",
          state:"primary",
          message:"Processing...",
          opacity: 0.2
      });
          app.form.assetdetail.push(app.formAssetDetail);
          app.formAssetDetail = {};
      }).catch((err) => {
        console.error(err)
        toastr.error('sesuatu error terjadi', 'error');
      })
    },


    minus(index) {
      this.form.assetdetail.splice(index, 1);

      
    },
    tarif(event) {
      if (!event) return
      var app = this;

      var ic = app.kelompoks.find(function (item) {
        return item.id == event;
      });
      app.formAssetDetail.tarif = ic.tarif
    },
    changeSaldo(i, ic) {
      var app = this;
      app.form.assetdetail[i].tarif = ic.tarif;
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