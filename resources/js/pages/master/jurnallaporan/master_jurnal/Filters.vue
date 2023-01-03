<template>
    <div class="card card-custom mb-4 card-create">
      <div class="card-body">
        <form @submit.prevent="getDataMonth" class="row">
          <div class="form-group col-md-6">
            <label>Bulan</label>
            <select2 class="form-control" v-model="$parent.formRequest.bulan">
              <option value="" disabled selected>Bulan</option>
              <option
                v-for="bulan in bulans"
                :value="bulan.id"
                :data="bulan"
                :key="bulan.uuid"
              >
                {{ bulan.name }}
              </option>
            </select2>
          </div>
          <div class="form-group col-md-6">
            <label>Tahun</label>
            <select2 class="form-control" v-model="$parent.formRequest.tahun">
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
          <div class="row mt-10">
            <div class="d-grid gap-2">
              <button
              id="btnSearch"
                @click="sendFilter"
                button
                type="button"
                class="btn btn-success btn-sm ms-2"
              >
                <i class="las la-save"></i> Filter
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  // AUTO GENERATE VUE FILE BY MCFLYON ARTISAN COMMAND
  
  export default {
    data() {
      return {
        bulans: [
          { id: 1, name: "Januari" },
          { id: 2, name: "Februari" },
          { id: 3, name: "Maret" },
          { id: 4, name: "April" },
          { id: 5, name: "Mei" },
          { id: 6, name: "Juni" },
          { id: 7, name: "Juli" },
          { id: 8, name: "Agustus" },
          { id: 9, name: "September" },
          { id: 10, name: "Oktober" },
          { id: 11, name: "November" },
          { id: 12, name: "Desember" },
        ],
        tahuns: [],
        formRequest: {
          bulan: "",
          tahun: "",
        },
      };
    },
    methods: {
      
      
      // reload(){
      //     var app = this;

      //       if(app.formRequest.bulan != '' && app.formRequest.tahun != ''){
      //          $('#btnSearch').prop('disabled', true);
      //               KTApp.progress($('#btnSearch'))
      //               app.showList = true;
      //               setTimeout(function(){
      //                   app.$refs.keluar.reload();
      //                   $('#btnSearch').prop('disabled', false);
      //                   KTApp.unprogress($('#btnSearch'))
      //               }, 1500);
      //           }else{
      //               $('.form-control').addClass('error-filter');
      //               setTimeout(function(){
      //                   $('.form-control').removeClass('error-filter');
      //               }, 2000);
      //           }
      //       },
      sendFilter() {
        var app = this;
        if (app.$parent.formRequest.bulan == "" || app.$parent.formRequest.tahun == "") {
          app.$toast.error("Bulan dan Tahun harus diisi");
        } else {
          app.$parent.openFilters = false;(
            app.formRequest.bulan,
            app.formRequest.tahun
          );
             app.$parent.checkTambah();
  
        }
      },
      
      // loadJs() {
      //   var vm = this;
      //   $("#kt_daterangepicker_1")
      //     .daterangepicker()
      //     .val(vm.$parent.formFilter.date)
      //     .on("apply.daterangepicker", function (val, picker) {
      //       vm.$parent.formFilter.date = $("#kt_daterangepicker_1").val();
      //       vm.$parent.formFilter.start_date = picker.startDate.format("YYYY-MM");
      //       vm.$parent.formFilter.end_date = picker.endDate.format("YYYY-MM-DD");
      //     });
      // },
      getTahun(){
        var app = this;
        app.tahuns = _.range(new Date().getFullYear(), 1999);
        
      },
    },
    mounted() {
      var app = this;
      app.getTahun();
    },
  };
  </script>
  