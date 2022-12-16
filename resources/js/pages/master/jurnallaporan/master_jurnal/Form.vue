<template>
    <form class="card mb-12" id="form-masterjurnal" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ masterjurnal?.uuid ? `Edit Jurnal : ` : "Tambah Jurnal"  }}
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
            <div class="mb-8">
              <label for="kd_jurnal" class="form-label required"> Kode : </label>
              <input type="text" name="kd_jurnal" id="kd_jurnal" placeholder="Code System "
                class="form-control" required autoComplete="off" readonly="true" v-model="form.kd_jurnal" />
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
            <!-- <div class="mb-8">
              <label   class="form-label required input-date"> Tanggal : </label>
              <input type="date" name="tanggal" id="tanggal" 
              placeholder="Pilih Tanggal"
              @change.prevent="getCode()"
                :disabled="form.type == 'edit' ? true : false"
                class="form-control" required autoComplete="off" v-model="form.tanggal" />
            </div> -->
              <div class="mb-0">
                <label class="form-label" for="tanggal" required  >Tanggal :</label>
                <input  @change.prevent="getCode()" name="tanggal" 
                :disabled="form.type == 'edit' ? true : false"
                class="form-control input-date" required autoComplete="off" v-model="form.tanggal" placeholder="Pick date rage" id="kt_datepicker_1"  />
            </div>
          </div>
          
          <div class="col-12" style="">
            <label for="file" class="form-label required"> Upload Bukti : </label>
            <file-upload :files="fileUpload" :allow-multiple="true"
              v-on:updatefiles="onUpdateFilesUpload" labelIdle='Drag & Drop your files or <span class ="filepond--label-action">Browse</span>'
              required
              :accepted-file-types="['image/*', 'application/pdf' ,'application/vnd.ms-excel' ]"></file-upload>
          </div>
        </div>

        <hr />

        <div class="row" v-for="(item, index) in form.jurnal_item" :key="item.id">
          <div class="col-2">
          <div class="mb-8">
            <label for="nm_account" class="form-label required">
              Account   :
            </label>
            <select2
              class="form-control"
              name="account_id"
              placeholder="Pilih Account"
              :id="'account_id'+index"
              @change="saldo($event, index)"
              v-model="item.account_id"
              required
            > 
              <option value="" disabled>Pilih Account</option>
              <option v-for="item in account" :disabled="form.jurnal_item.findIndex((el) => el.account_id == item.id ) != -1" :value="item.id">
                      {{ item.kode_account }} - {{ item.nm_account }}
                    </option>
              
            </select2>
          </div>
        </div>

              <div class="col-2" v-if="item.account_id == null" >
                <div class="mb-8">
                <label for="nm_account" class="form-label required">
                        Debit :
                      </label>
                    
                    <div class="input-group">
                       <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                       <money3 v-model="item.debit" id="debit" class="form-control" type="text" name="debit" @change="hitungSaldo()" v-bind="config" required 
                       disabled="" ></money3>
                    
                    </div>
                  </div>
                  </div>

                  <div class="col-2" v-else>
                <div class="mb-8">
                <label for="nm_account" class="form-label required">
                        Debit :
                      </label>
                    
                      <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                        <money3 v-model="item.debit" id="debit" class="form-control" type="text" name="debit" @change="hitungSaldo()" v-bind="config" required ></money3>
                    
                    </div>
                  </div>
                  </div>
                  
                  <div class="col-2" v-if="item.account_id == null">
                <div class="mb-8">
                <label for="nm_account" class="form-label required">
                        Kredit :
                      </label>
                    
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <money3 v-model="item.kredit" class="form-control" type="text" id="kredit" name="kredit" @change="hitungSaldo()" v-bind="config" required disabled ></money3>
                   
                    </div>
                  </div>
                  </div>

                  <div class="col-2"  v-else>
                <div class="mb-8">
                <label for="nm_account" class="form-label required">
                        Kredit :
                      </label>
                     
                      
                      <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                         <money3 v-model="item.kredit" class="form-control" type="text" id="kredit" name="kredit" @change="hitungSaldo()" v-bind="config" required ></money3>
                     
                    </div>
                  </div>
                  </div>


                  <div class=" col-md-3">
                <div class="mb-4">
                  <label class="required form-label">Keterangan :</label>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Keterangan/narasi"
                    name="keterangan"
                    v-model="item.keterangan"
                    required
                  />
                </div>
              </div>
              <div class="col-md-2">
                <div class="mb-4">
                  <label class="form-label">Saldo :</label>
                  <input
                    type="text"
                    disabled
                    class="form-control"
                    placeholder="Saldo"
                    name="saldo_berjalan"
                    v-model="item.saldo"
                  />
                </div>
              </div>

              <div class="col-1">
                <div class="mt-9 mb-8">
                    
                <div class="delete btn btn-sm btn-icon btn-danger ">
                  <i class="la la-trash fs-2" 
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
              <div class="col-md-3">Debit: {{ debit.toFixed(2)
                        .replace(/\d(?=(\d{3})+\,)/g, "$&.") }}</div>
              <div class="col-md-3">Kredit: {{ kredit.toFixed(2)
                        .replace(/\d(?=(\d{3})+\,)/g, "$&.") }}</div>
              <div class="col-md-3">Selisih: {{ (debit - kredit).toFixed(2)
                        .replace(/\d(?=(\d{3})+\,)/g, "$&.") }}</div>
              <div class="col-md-3">
                status: {{ debit == kredit ? "balance" : "tidak balance" }}
              </div>
            </div>
            <hr />
            
           
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
      }
    },
    setup({ selected }) {
      const queryClient = useQueryClient();
      const form = ref({
        jurnal_item: selected ? [] : [ {},]
      });
      const debit = ref(0.00);
      const kredit = ref(0.00);
      const fileUpload = ref([]);

      const { data: account } = useQuery(["accounts"], () =>
      axios.get("/masterjurnal/child").then((res) => res.data)
    );
      
  
      const { data: masterjurnal} = useQuery(
        ["masterjurnal", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-masterjurnal"), 100);
          return axios.get(`/masterjurnal/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => {
            form.value = data;
            fileUpload.value = data.file_bukti_master;
          },
          onSettled: () => KTApp.unblock("#form-masterjurnal"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/masterjurnal/${selected}/update` : '/masterjurnal/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-masterjurnal");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-masterjurnal");
        }
      });
  
      return {
      
        account,
        fileUpload,
        masterjurnal,
        submit,
        form,
        queryClient,
        debit, 
        kredit,
      }
    },
    methods: {
      
      onUpdateFiles(files) {
        this.file = files;
      },

      loaDDate(){
        $("#kt_datepicker_1").flatpickr();
      },

      onUpdateFilesUpload(filesUpload) {
        this.fileUpload = filesUpload;
      },
      onSubmit() {
        const vm = this;
        const data = new FormData(document.getElementById("form-masterjurnal"));
        this.fileUpload.forEach(file => data.append("file[]", file.file));
        vm.form.jurnal_item.forEach((item, i) => {
          data.append(`jurnal_item[${i}][account_id]`, item.account_id);
          data.append(`jurnal_item[${i}][debit]`, item.debit);
          data.append(`jurnal_item[${i}][kredit]`, item.kredit);
          data.append(`jurnal_item[${i}][keterangan]`, item.keterangan);
          // data.append(`jurnal_item[${i}][saldo]`, item.saldo);
          // data.append('jurnal_item[].keterangan', item.saldo);
        });
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false; 
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/masterjurnal/paginate"], { exact: true });
          }
        });
      },
      
    loadDate() {
      var vm = this;

      var max = new Date(vm.$parent.formRequest.tahun, vm.$parent.formRequest.bulan, 0);

      var min = new Date(vm.$parent.formRequest.tahun, vm.$parent.formRequest.bulan - 1);

      setTimeout(function () {
        $(" .input-date").flatpickr({
          enableTime: false,
          dateFormat: "Y-m-d",
          minDate: `${min.getFullYear()}-${
            min.getMonth() + 1
          }-${min.getDate()}`,
          maxDate: `${vm.$parent.formRequest.tahun}-${
            vm.$parent.formRequest.bulan
          }-${max.getDate()}`,
        });

        $(" .input-date")
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
          app.axios.post("masterjurnal/getCode", {
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

      this.form.jurnal_item.push({

      });
    },
    delJurnalItems(index) {
      this.form.jurnal_item.splice(index, 1);
    },
    hitungSaldo() {
      var app = this;

      var debit = 0;
      var kredit = 0;

      
      for (let index = 0; index < app.form.jurnal_item.length; index ++) {
        const element = app.form.jurnal_item[index];
          debit += parseFloat(element.debit);
          kredit += parseFloat(element.kredit);
      }

     
      // console.log(debit , kredit);
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
      app.form.jurnal_item[i].saldo = app.account[ic].saldo_berjalan;
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
    this.hitungSaldo();
    this.loadDate();
    this.loaDDate();

    
  
    
  },
    
  };
  

  </script>
  
  <style>
  </style>