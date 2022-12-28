<template>
    <div class="card card-custom mb-4 card-create">
      <div class="card-body">
        <form @submit.prevent="getDataMonth" class="row">
          <div class="form-group col-md-12">
            <label required>Tahun</label>
            <select2 class="form-control"

            @change="changetahun($event)"

             v-model="formRequest.tahun">

              <option value="" disabled selected>Tahun</option>
              <option
                v-for="tahun in tahuns"
                :value="tahun.id"
                :data="tahun"
                :key="tahun.uuid">
                {{ tahun }}
              </option>
            </select2>
          </div>
          <div class="row mt-5">
            <div class="">
              <button
              id="btnSearch"
                @click="cetak()"
                button
                type="button"
                class="btn btn-success btn-sm ms-2"
              >
                <i class="las la-file-excel"></i> CETAK
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  // AUTO GENERATE VUE FILE BY MCFLYON ARTISAN COMMAND
  import { useDownloadExcel } from "@/libs/hooks";

  export default {
    setup() {
        const {download: downloadExcel} = useDownloadExcel();
        return {downloadExcel}
    },
    data() {
      return {
        tahuns: [],
        data: [],
        account: [],
        formRequest: {
          tahun: "",
          account_id: "",
         
        },
      };
    },
    methods: {
        changetahun(event) {
        console.log(event);
  
        var app = this;
  
        app.$http.post("worksheet/check", { tahun: event }).then((res) => {
          app.status = res.data.status;
        });
      },
        cetak() {
        var app = this;
        app.downloadExcel("jurnal/worksheet/" + app.formRequest.tahun, "POST", { account_id: app.formRequest.account_id });
      },
      getAccount(){
        var app = this;
        app.axios.get('account/show').then((res) => {
        app.account = res.data.data
      }).catch((err) => {
        toastr.error('sesuatu error terjadi', 'error');
      })
    },
    getTahun() {
        var app = this;
  
        app.$http
          .get("jurnal/tahun")
          .then((res) => {
            if (!_.isEmpty(res.data)) {
              app.tahuns = _.range(
                new Date().getFullYear(),
                parseInt(res.data.tahun) - 1
              );
            } else {
              app.tahuns = _.range(new Date().getFullYear(), 2000);
            }
          })
          .catch((err) => {
            toastr.error("sesuatu error terjadi", "error");
          });
      },
    },
    mounted() {
        var app = this;
         app.getAccount();
         app.getTahun();
    },
  };
  </script>
  