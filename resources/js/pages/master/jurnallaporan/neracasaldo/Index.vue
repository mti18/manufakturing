<template>
    <section>
      <Filters v-if="openFilters" />
      <template v-else>
          <div class="card">
            <div class="card-header">
                <h1 class="mt-6">Neraca Saldo</h1>
              <div class="card-title ">
                <button v-if="!openFilters" type="button" class="back btn btn btn-danger btn-sm ms-auto " @click="openFilters = true">
                  <i class="fas fa-angle-left"></i>
                  BACK
                </button>
                <button @click="cetak()" type="button" class="back btn btn btn-success btn-sm ms-auto " >
                  <i class="las la-file-excel"></i>
                  CETAK
                </button>
              </div>
            </div>
            <div>
            <transition
              enter-active-class="animated slideInDown"
              leave-active-class="animated slideOutRight"
            >
              <div class="card card-custom">
                <div class="table-responsive">
                  <table
                    class="
                      table table-hover table-rounded table-striped
                      border
                      gy-7
                      gs-7
                    "
                  >
                    <thead>
                      <tr
                        class="
                          fw-semibold
                          fs-6
                          text-gray-800
                          border-bottom-2 border-gray-200
                        "
                      >
                        <th class="text-center" style="width: 150px">Kode Akun</th>
                        <th class="text-center">Nama Akun</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Kredit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in data">
                        <td class="text-center">{{ item.kode_account.rupiah() }}</td>
                        <td>{{ item.nm_account }}</td>
                        <td class="text-center">
                          {{ item.sum > 0 ? item.sum.rupiah() : "-" }}
                        </td>
                        <td class="text-center">
                          {{ item.sum > 0 ? "-" : Math.abs(item.sum).rupiah() }}
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2"><h4 class="text-end">Total</h4></td>
                        <td class="font-weight-bold text-center">
                          {{ debit.rupiah() }}
                        </td>
                        <td class="font-weight-bold text-center">
                          {{ kredit.rupiah() }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </transition>
          </div>
          </div>
        
      </template>
    </section>
  </template>
  
  <script>
  import { ref, h } from "vue";
  import { useQueryClient } from "vue-query";
  import { createColumnHelper } from "@tanstack/vue-table";
  const columnHelper = createColumnHelper();

  import Filters from "./Filters.vue";
  import { useDelete } from "@/libs/hooks";
  
  export default {
    components: {
     Filters, 
    },
    data(){
      return{
        data: [],
        debit: 0,
        kredit: 0,
        account: [],
        formRequest: {
          bulan: '',
          tahun: '',
          type: "",
        }
      }
    },
    setup() {
      const queryClient = useQueryClient();
      const selected = ref();
      const openFilters = ref(true);
  
      const columns = [
        
      ]
  
      return {

        selected,
        openFilters,
        columns,
      }
    },
    methods:{
      cetak() {
      var app = this;
      app
        .$http({
          url:
            "neraca/reportneraca/" +
            app.formRequest.bulan +
            "/" +
            app.formRequest.tahun +
            "/" +
            app.formRequest.type,
          method: "GET",
          responseType: "arraybuffer",
          beforeSend: function (xhr) {
            xhr.setRequestHeader(
              "Authorization",
              "Bearer " + localStorage.getItem("authToken")
            );
          },
        })
        .then(function (res) {
          var headers = res.headers;
          KTApp.unblock(".download_excel");
          var blob = new Blob([res.data], {
            type: "application/vnd.ms-excel",
          });

          var link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          link.download = headers["content-disposition"]
            .split('filename="')[1]
            .split('"')[0];
          link.click();

          window.respon = { status: true, message: "Berhasil Download!" };
        });
    },
       

     getAccount(){
        var app = this;
        app.axios.get('account/show').then((res) => {
        app.account = res.data.data
      }).catch((err) => {
        toastr.error('sesuatu error terjadi', 'error');
      })
    },
      
    },
     mounted() {
    var app = this;
    app.getAccount();
  },
  }
  </script>
  
  <style>
  .tambah{
    padding: auto;
    margin-left: 30px;
  }
  .back{
    padding: auto;
    margin-right:  30px;
  }
  
  </style>