<template>
    <form class="card mb-12" id="form-asset" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ assetjurnal?.uuid ? `Edit Asset : ${assetjurnal.nm_asset}` : "Tambah Asset "  }}
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
          <div class="col-6">
            <div class="mb-8">
              <label for="nm_asset" class="form-label required"> Nama Asset : </label>
              <input type="text" name="nm_asset" id="nm_asset" placeholder="Nama Asset"
              class="form-control" required autoComplete="off" v-model="form.nm_asset" />
            </div>
          </div>
         
          <div class="col-6">
          <div class="mb-8">
            <label for="nm_golongan" class="form-label required">
              Golongan :
            </label>
            <select2
              class="form-control"
              name="golongan_id"
              placeholder="Pilih Nama Golongan"
              id="golongan_id"
              v-model="form.golongan_id"
              required
            >
              <option value="" disabled>Pilih Golongan</option>
              <option
                v-for="item in golongan"
                :value="item.id"
                :key="item.id"
              >
               {{item.metode_penyusutan}} - {{ item.nm_golongan}}
              </option>
            </select2>
          </div>
        </div>
      </div>
        <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="nm_asset_group" class="form-label required">
              Asset Group :
            </label>
            <select2
              class="form-control"
              name="asset_group_id"
              placeholder="Pilih Nama Golongan"
              id="asset_group_id"
              v-model="form.asset_group_id"
              required
            >
              <option value="" disabled>Pilih Asset Group</option>
              <option
                v-for="item in assetgroup"
                :value="item.id"
                :key="item.id"
              >
                {{ item.nm_asset_group}}
              </option>
            </select2>
          </div>
         </div>
         <div class="col-6">
        <div class="mb-8">
              <label for="name" class="form-label required"> Akun Asset Tetap : </label>
              <select2
              class="form-control"
              name="akun_id"
              placeholder="Pilih Account"
              id="akun_id"
              v-model="form.akun_id"
              required
            > 
              <option value="" disabled>Pilih Account</option>
              <option
                v-for="item in account"
                :value="item.id" :key="item.uuid"
              >
              {{ item.kode_account }} - {{ item.nm_account }}
              </option>
             
            </select2>
            </div>
          </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="mb-8">
              <label for="name" class="form-label required"> Akun Beban : </label>
              <select2
              class="form-control"
              name="beban_id"
              placeholder="Pilih Account"
              id="beban_id"
              v-model="form.beban_id"
              required
            > 
              <option value="" disabled>Pilih Account</option>
              <option
                v-for="item in account"
                :value="item.id" :key="item.uuid"
              >
              {{ item.kode_account }} - {{ item.nm_account }}
              </option>
             
            </select2>
            </div>
          </div>
            <div class="col-6">
              <div class="mb-8">
              <label for="name" class="form-label required"> Akun Penyusutan : </label>
              <select2
              class="form-control"
              name="penyusutan_id"
              placeholder="Pilih Account"
              id="penyusutan_id"
              v-model="form.penyusutan_id"
              required
            > 
              <option value="" disabled>Pilih Account</option>
              <option
                v-for="item in account"
                :value="item.id" :key="item.uuid"
              >
              {{ item.kode_account }} - {{ item.nm_account }}
              </option>
             
            </select2>
            </div>
          </div>
          </div>
          <div class="row">
        <div class="col-4">
            <div class="mb-8">
                <label class="required form-label">Price :</label>
                  <div class="input-group">
                   <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                   <money3 v-model="form.price" class="form-control" type="text" id="price" name="price" v-bind="config" required ></money3>
                 </div>
                </div>
              </div>
        <div class="col-4">
            <div class="mb-8">
              <label for="input-number" class="form-label required "> Tanggal : </label>
              <datepicker name="number" id="input-number" placeholder="Tanggal"
              class="form-control" required autoComplete="off" :options="monthOptions" @change="changeTanggal" />
            </div>
          </div>
          <div class="col-4">
              <div class="mb-8">
              <label for="name" class="form-label required"> Akun Yang Dikreditkan : </label>
              <select2
              class="form-control"
              name="kredit_id"
              placeholder="Pilih Account"
              id="kredit_id"
              v-model="form.kredit_id"
              required
            > 
              <option value="" disabled>Pilih Account</option>
              <option
                v-for="item in account"
                :value="item.id" :key="item.uuid"
              >
              {{ item.kode_account }} - {{ item.nm_account }}
              </option>
             
            </select2>
            </div>
          </div>
        </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-sm ms-auto mt-8 d-block">
              <i class="las la-save"></i>
              Simpan
            </button>
          </div>
        </div>

    </form>
  </template>
  
  <script>
  
  import { Money3Component } from 'v-money3'
  import { Money3Directive } from 'v-money3';
  import { ref } from "vue";
  import { useQuery, useMutation } from "vue-query";
  import axios from "@/libs/axios";
 
  import { useQueryClient } from "vue-query";
  import monthSelectPlugin from "flatpickr/dist/plugins/monthSelect";
  import "flatpickr/dist/plugins/monthSelect/style.css";
  
  export default {
    components: { money3: Money3Component },
    directives: { money3: Money3Directive },
    props: {
      selected: {
        type: String,
        default: null,
      }
    },
    data(){
      return{
        config: {
          prefix: '',
          suffix: '',
          thousands: '.',
          decimal: ',',
          precision: 2,
          disableNegative: false,
          disabled: false,
          min: null,
          max: null,
          allowBlank: false,
          minimumNumberOfCharacters: 0,
        },
        monthOptions: {
          plugins: [
              new monthSelectPlugin({
                shorthand: true, //defaults to false
                dateFormat: "F Y", //defaults to "F Y"
                altFormat: "F Y", //defaults to "F Y"
                theme: "light" // defaults to "light"
              })
          ]
        }
      }
    },
    setup({ selected }) {
      const queryClient = useQueryClient();
      const form = ref({});

      const { data: account } = useQuery(["accounts"], () =>
      axios.get("/masterjurnal/child").then((res) => res.data)
    );
    

      const { data: golongan } = useQuery(["golongans"], () =>
      axios.get("/golongan/get").then((res) => res.data.data)
    );
  
      const { data: assetgroup } = useQuery(["asset_groups"], () =>
      axios.get("/assetgroup/get").then((res) => res.data.data)
    );
  
      const { data: assetjurnal } = useQuery(
        ["assetjurnal", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-asset"), 100);
          return axios.get(`/assetjurnal/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data.data,
          onSettled: () => KTApp.unblock("#form-asset"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/assetjurnal/${selected}/update` : '/assetjurnal/store', data).then(res => res.data), {
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
        account,
        assetgroup,
        golongan,
        assetjurnal,
        submit,
        form,
        queryClient,
        monthSelectPlugin
      }
    },
    methods: {
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
            vm.queryClient.invalidateQueries(["/assetjurnal/paginate"], { exact: true });
          }
        });
      },
      changeTanggal(ev) {
        if (typeof ev === 'string') {
          this.form.number = ev;
        }
        // console.log(ev)
      },
      // loadDate() {
      //       var vm = this;
      //       setTimeout(function () {
      //           $(".input-number").flatpickr({
      //           plugins: [
      //               new monthSelectPlugin({
      //               shorthand: true, //defaults to false
      //               dateFormat: "m.y", //defaults to "F Y"
      //               altFormat: "F Y", //defaults to "F Y"
      //               theme: "dark" // defaults to "light"
      //               }),
      //           ],
      //           });
      //       }, 100);
      //       },
      getAccount() {
        setTimeout(() => {
        var app = this;
        axios
          .get(`masterjurnal/child`)
          .then((res) => {
            app.child= res.data;
          })
          .catch((err) => {
            toastr.error("sesuatu error terjadi", "gagal");
          });
      }, 500);
    },
    },
    mounted() {
    this.getAccount();
    // this.loadDate();
  },
  };
  </script>
  
  <style>
  </style>
  