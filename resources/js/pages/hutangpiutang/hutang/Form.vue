<template>
    <form class="card mb-12" id="form-hutang" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ hutang?.uuid ? `Edit Hutang : ${hutang.type}` : "Tambah Hutang"  }}
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
          <div class="col-6" >
            <div class="mb-8">
              <label for="profile_id" class="form-label required"> Profile : </label>
              <select2 
                name="profile_id" 
                id="profile"
                class="form-control"  
                v-model="form.profile_id" >
                <option value="" disabled>Pilih Profile</option>
                <option v-for="profile in profiles" 

                :value="profile.id" 
                :key="profile.uuid"
                > {{ profile.nama }} </option>
              </select2>
            </div>
          </div>
            <div class="col-6">
            <div class="mb-8">
              <label for="metode_penyusutan" class="form-label required "> Account : </label>
              <select2
              class="form-control"
              name="account_id"
              id="account"
              v-model="form.account_id"
              placeholder="Pilih Account"
              required
            > 
              <option value="" disabled>Pilih Account</option>
              <option
                v-for="item in account"
                :value="item.id" :key="item.uuid"
              >
              {{ item.nm_account }}
              </option>
             
            </select2>
            </div>
          </div>
          
            <div class="row">
            <div class="col-4">
          <div class="mb-8">
            <label for="" class="form-label required ">
              Tanggal :
            </label>
           
            <input
            
              name="tanggal"
              id="kt_datepicker_1"
              placeholder="Pilih Tanggal"
              class="form-control "
              required
              v-model="form.tanggal"
            />
           
          </div>
        </div>
          <div class="col-4">
          <div class="mb-8">
            <label for="code" class="form-label required">
              Jatuh Tempo :
            </label>
            <div class="input-group">
              <input
                type="number"
                min="0"
                name="tempo"
                id="tempo"
                placeholder="Hari"
                class="form-control"
                required
                autoComplete="off"
                v-model="form.tempo"
              />
              <div class="input-group-append">
                <span class="input-group-text">Hari</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-4">
          <div class="mb-8 ">
                <label for="" class="form-label required">
                        Jumlah :
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"> Rp </span></div> 
                         <Money3 v-model="form.jumlah" class="form-control" type="text" id="jumlah" name="jumlah" v-bind="config" required ></Money3>
                    </div>
                  </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
            <div class="mb-8">
              <label for="keterangan" class="form-label required "> Keterangan : </label>
              <textarea type="text" name="keterangan" id="keterangan" placeholder="Keterangan"
              class="form-control" required autoComplete="off" v-model="form.keterangan" />
            </div>
          </div>
      </div>
      <div class="row">
        <div class="col-12">
          <label for="upload" class="form-label required">
            Upload Bukti :
          </label>
          <file-upload
            :files="fileUpload"
            :allow-multiple="true"
            v-on:updatefiles="onUpdateFilesUpload"
            labelIdle='Drag & Drop your files or <span class ="filepond-label-action">Browse</span>'
            required
            :accepted-file-types="['image/*', 'application/pdf' ,'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ]"></file-upload>
  
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
   import { Money3, Money3Component } from 'v-money3'
  import { Money3Directive } from 'v-money3';
  import { ref } from "vue";
  import { useQuery, useMutation } from "vue-query";
  import axios from "@/libs/axios";
  import { useQueryClient } from "vue-query";
  
  export default {
    props: {
        selected: {
            components: { money3: Money3Component },
            directives: { money3: Money3Directive },
            type: String,
            default: null,
        }
    },
    data() {
        return {
          Hutang: '',
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
        const fileUpload = ref([]);

        
        const { data: account } = useQuery(["accounts"], () =>
         axios.get("/hutangpiutang/getHutang").then((res) => res.data
         ));
        const { data: profiles } = useQuery(["profiles"], () => 
          axios.get("/profile/get").then((res) => res.data
        ));
        const { data: hutang} = useQuery(["hutang", selected, "edit"], () => {
            setTimeout(() => KTApp.block("#form-hutang"), 100);
            return axios.get(`/hutangpiutang/${selected}/editHutang`).then((res) => res.data);
        }, {
            enabled: !!selected,
            cacheTime: 0,
            onSuccess: data => {form.value = data,
            fileUpload.value = data.file_bukti_hutang
            },
            onSettled: () => KTApp.unblock("#form-hutang"),
        });
        const { mutate: submit } = useMutation((data) => axios.post(selected ? `//${selected}/update` : "/hutangpiutang/store", data).then(res => res.data), {
            onMutate: () => {
                KTApp.block("#form-hutang");
            },
            onError: (error) => {
                toastr.error(error.response.data.message);
            },
            onSettled: () => {
                KTApp.unblock("#form-hutang");
            }
        });
        return {
          fileUpload,
            profiles,
            account,
            hutang,
            submit,
            form,
            queryClient
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
            const data = new FormData(document.getElementById("form-hutang"));
             this.fileUpload.forEach(bukti => data.append("bukti[]", bukti.file));
            this.submit(data, {
                onSuccess: (data) => {
                    toastr.success(data.message);
                    vm.$parent.openForm = false;
                    vm.$parent.selected = undefined;
                    vm.queryClient.invalidateQueries(["/hutangpiutang/{uuid}/paginate"], { exact: true });
                }
            });
        },
        loadDate() {
            $("#kt_datepicker_1").flatpickr();
        },
        getAccount() {
            setTimeout(() => {
                var app = this;
                var app = app.form.account_id;
                axios
                    .get(`hutangpiutang/getHutang`)
                    .then((res) => {
                    app.Hutang = res.data;
                })
                    .catch((err) => {
                    toastr.error("sesuatu error terjadi", "gagal");
                });
            }, 500);
        },
    },
    mounted() {
        this.loadDate();
    },
    components: { Money3 }
};
  </script>
  
  <style>
  </style>
  