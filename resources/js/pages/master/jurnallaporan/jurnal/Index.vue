<template>
    <section>
      <Filters v-if="openFilters" />
      <template v-else>
          <div class="card">
            <div class="card-header">
                <h1 class="mt-6">Jurnal</h1>
              <div class="card-title ">
                <button v-if="!openFilters" type="button" class="back btn btn btn-danger btn-sm ms-auto " @click="openFilters = true">
                  <i class="fas fa-angle-left"></i>
                  BACK
                </button>
                <button @click="cetak()"  type="button" class="back btn btn btn-success btn-sm ms-auto " >
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
              <div  class="card card-custom">
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
                        <th class="text-left" style="width: 150px">Tanggal</th>
                        <th class="text-left">Akun</th>
                        <th class="text-left">Keterangan</th>
                        <th class="text-left">Debit</th>
                        <th class="text-left">Kredit</th>
                      </tr>
                    </thead>
                    <tbody>
                       <template v-for="item in masterjurnal">
                                <tr v-for="(kitem, index) in item.jurnal_item">
                                <td v-if="index == 0" :rowspan="item.jurnal_item.length">
                                    {{ item.tanggal.indo() }}
                                </td>
                                <td>{{ kitem.account.nm_account }}</td>
                                <td>{{ kitem.keterangan }}</td>
                                <td>
                                    Rp.
                                    {{
                                    kitem.debit
                                        .toFixed(2)
                                        .replace(/\d(?=(\d{3})+\.)/g, "$&,")
                                    }}
                                </td>
                                <td>
                                    Rp.
                                    {{
                                    kitem.kredit
                                        .toFixed(2)
                                        .replace(/\d(?=(\d{3})+\.)/g, "$&,")
                                    }}
                                </td>
                                </tr>
                            </template>
                            <tr>
                                <td colspan="3"><h3>Total</h3></td>
                                <td>
                                <h4>
                                    Rp.
                                    {{ debit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,") }}
                                </h4>
                                </td>
                                <td>
                                <h4>
                                    Rp.
                                    {{ kredit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,") }}
                                </h4>
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
        masterjurnal: [],
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
            "jurnal/reportjurnal/" +
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