<template>
    <div class="card card-custom mb-4 card-create">
      <div class="card-body">
        <form @submit.prevent="getDataMonth" class="row">
          <div class="form-group col-md-12">
            <label >Tahun</label>
            <select2 class="form-control"

            @change="changetahun($event)"

             v-model="$parent.formRequest.tahun">

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
              <div   v-if="
              status == false &&
              $parent.formRequest.tahun != ''
            ">
                <button
                id="btnSearch"
                @click="sendFilter()"
                button
                type="button"
                class="btn btn-success btn-sm ms-2"
                >
                <i class="las la-eye-slash"></i> TUTUP PERIODE
              </button>
              </div>
              <div 
              v-if="status == true && $parent.formRequest.tahun != ''">

                <button
                id="btnSearch"
                @click="hapus()"
                button
              
                type="button"
                class="btn btn-danger btn-sm ms-2"
                >
                <i class="las la-eye-slash"></i> HAPUS PERIODE INI
              </button>
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
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  // AUTO GENERATE VUE FILE BY MCFLYON ARTISAN COMMAND
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
        status: false,
        account: [],
        formRequest: {
          tahun: "",
          account_id: "",
         
        },
      };
    },
    methods: {
      changetahun(event) {
        var app = this;
  
        app.$http.post("worksheet/check", { tahun: event }).then((res) => {
          app.status = res.data.status;
        });
      },
      labarugi(tahun) {
      var app = this;
      app.$http.post("laporan/labarugi", { tahun: tahun }).then((res) => {
        app.$parent.dataTutup = res.data;
        app.$parent.dataTutup[0].class = "active show";
        app.$parent.tahun = app.$parent.dataTutup[0].tahun;
        app.$parent.openFilters = false;
      });
    },
    sendFilter() {
      var app = this;

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "Apakah Anda Yakin Menutup periode ini?",
          text: "silahkan check terlebih dahulu tentang asset dan penyusutannya, jangan sampai terlupa, anda tidak bisa mengedit jurnal setelah aperiode ditutup",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Ya, saya sudah mengechecknya",
          cancelButtonText: "No, cancel!",
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            app.$http
              .post("laporan/pratutup", { data: app.$parent.formRequest })
              .then((res) => {
                app.labarugi(res.data);
              })
              .catch((err) => {
                toastr.error(err.response.data.message);
                app.$parent.formRequest = {
                  tahun: "",
        
                };
              });
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              "Cancelled",
              "Your imaginary file is safe :)",
              "error"
            );
          }
        });
      },
      hapus() {
      var app = this;
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "Apakah Anda Yakin Menghapus Penutup periode ini?",
          text: "Penutup periode diatas tahun yang anda pilih akan dihapus juga",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Ya, Saya Yakin",
          cancelButtonText: "No, cancel!",
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
           
              app.$http
              .post("laporan/hapus", {
                form: app.$parent.formRequest.tahun,
              })
              .then((res) => {
                app.getTahun();
                toastr.success("sukses menghapus periode", "sukses");
               app.$parent.formRequest.tahun = "";
              });
                
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              "Cancelled",
              "Your imaginary is safe :)",
              "error"
            );
          }
        });
    },
  
    getTahun() {
        var app = this;
  
        app.$http
          .get("laporan/tahun")
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
      cetak() {
        var app = this;
        app.downloadExcel("laporan/laporan/" + app.$parent.formRequest.tahun, "POST", { account_id: app.$parent.formRequest.account_id });
      },
    },
    mounted() {
        var app = this;
         app.getTahun();
        //  app.getAccountPajak();
        //  app.getAccountBank();
    },
  };
  </script>
  