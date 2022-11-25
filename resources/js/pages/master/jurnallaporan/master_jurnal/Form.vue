<template>
    <form class="card mb-12" id="form-masterjurnal" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ masterjurnal?.uuid ? `Edit Jurnal : ${masterjurnal.kd_jurnal}` : "Tambah Jurnal"  }}
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
            <div class="mb-8">
              <label for="tanggal" class="form-label required input-date"> Tanggal : </label>
              <input type="text" name="tanggal" id="tanggal" 
              placeholder="Pilih Tanggal"
              @change.prevent="getCode()"
                :disabled="$parent.type == 'edit' ? true : false"
                class="form-control" required autoComplete="off" v-model="form.tanggal" />
            </div>
          </div>
          <div class="col-6">
            <label for="upload" class="form-label required"> Upload Bukti : </label>
            <file-upload :files="selected && form?.upload ? `/${form.upload}` : fileUpload" :allow-multiple="true"
              v-on:updatefiles="onUpdateFilesUpload" labelIdle='Drag & Drop your files or <span class ="filepond-label-action">Browse</span>' required
              :accepted-file-types="['image/*']"></file-upload>
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
    setup({ selected }) {
      const queryClient = useQueryClient();
      const form = ref({});
      const fileUpload = ref([]);
  
      const { data: masterjurnal} = useQuery(
        ["masterjurnal", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-masterjurnal"), 100);
          return axios.get(`/masterjurnal/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
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
        fileUpload,
        masterjurnal,
        submit,
        form,
        queryClient
      }
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
    },
    created(){
      setTimeout(() => {

        $("#tanggal").daterangepicker({
          singleDatePicker: true,
          showDropdowns: true,
          minYear: 1901,
          maxYear: parseInt(moment().format("YYYY"),12),
          locale: {
            format: "YYYY-MM-DD"
          }
        }, 
        );
      }, 300);
    },
    mounted() {
    this.loadDate();
    
  },
    
  };
  

  </script>
  
  <style>
  </style>