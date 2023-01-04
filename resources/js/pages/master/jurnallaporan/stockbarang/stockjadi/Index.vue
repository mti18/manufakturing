<template>
    <section>
      <Filters v-if="openFilters" />
      <template v-else>
          <div class="card">
            <div class="card-header">
                <h1 class="mt-6">Laporan Barang Jadi </h1>
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
            <div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Details</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <button></button>
                    </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <mti-paginate id="table-laporan" 
              :url="`/laporanstock/indexStockJadi/${formRequest.bulan}/${formRequest.tahun}`"
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
        stokkeluar: '',
        stokmasuk: '',
        barangjadi: '',
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
        columnHelper.accessor("nomor", {
          header: "#",
          style: {
            width: "25px",
          },
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("nm_barang_jadi", {
          header: "Nama Barang",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("kd_barang_jadi", {
          header: "Kode Barang",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("barangjadigudangs.nm_gudang", {
          header: "Gudang",
          cell: (cell) => cell.getValue(),
        }),

        columnHelper.accessor("uuid", {
          header: "Aksi",
          cell: (cell) => h('div', { class: 'd-flex gap-2' }, [
              h('button', { class: 'btn btn-sm btn-icon btn-primary', onClick: () => {
                KTUtil.scrollTop();
                $('#exampleModal').modal('show')
              }}, h('i', { class: 'la la-pencil fs-2' })), 
              h('button', { class: 'btn btn-sm btn-icon btn-danger', onClick: () => {
                deleteasset(`/assetjurnal/${cell.getValue()}/destroy`);
              }}, h('i', { class: 'la la-trash fs-2' }))
            ]),
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
       

     getStokMasuk(){
        var app = this;
        app.axios.get('stokmasuk/get').then((res) => {
        app.stokmasuk = res.data.data
      }).catch((err) => {
        toastr.error('sesuatu error terjadi', 'error');
      })
    },
     getStokKeluar(){
        var app = this;
        app.axios.get('stokkeluar/get').then((res) => {
        app.stokkeluar = res.data.data
      }).catch((err) => {
        toastr.error('sesuatu error terjadi', 'error');
      })
    },
     getBarangJadi(){
        var app = this;
        app.axios.get('barangjadi/get').then((res) => {
        app.barangjadi = res.data.data
      }).catch((err) => {
        toastr.error('sesuatu error terjadi', 'error');
      })
    },
      
    },
     mounted() {
    var app = this;
    app.getStokMasuk();
    app.getStokKeluar();
    app.getBarangJadi();
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