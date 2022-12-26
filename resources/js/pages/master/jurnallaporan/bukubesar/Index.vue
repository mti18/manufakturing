<template>
    <section>
      <Filters v-if="openFilters" />
      <template v-else>
          <div class="card">
            <div class="card-header">
                <h1 class="mt-6">Buku Besar</h1>
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
            <div class="card-body">
              <mti-paginate id="table-bukubesar" 
              :url="`/bukubesar/paginate/${formRequest.bulan}/${formRequest.tahun}`"
              :columns="columns"
             ></mti-paginate>
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
        account: [],
        formRequest: {
          bulan: '',
          tahun: '',
        }
      }
    },
    setup() {
      const queryClient = useQueryClient();
      const selected = ref();
      const openFilters = ref(true);
  
      const columns = [
        columnHelper.accessor("action", {
          header: "Data Buku Besar",
          cell: (cell) => openFilters.value ? null : h('div', { class: 'col-12' }, [
              h('div', {innerHTML:cell.getValue()})
            ],),
        }),
        
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
            "bukubesar/reportbukubesar/" +
            app.formRequest.bulan +
            "/" +
            app.formRequest.tahun,
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