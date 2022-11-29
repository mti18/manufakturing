<template>
    <section>
      <Filters v-if="openFilters" />
      <template v-else>
          <Form v-if="openForm" :selected="selected" />
          <div class="card">
            <div class="card-header">
                <h1 >Master Jurnal</h1>
              <div class="card-title ">
                <button v-if="!openFilters" type="button" class="back btn btn btn-danger btn-sm ms-auto " @click="openFilters = true">
                  <i class="fas fa-angle-left"></i>
                  BACK
                </button>
                <button v-if="!openForm" type="button" class="tambah btn btn-primary btn-sm ms-auto" @click="openForm = true">
                  <i class="las la-plus"></i>
                  Tambah Master
                </button>
               
              </div>
            </div>
            <div class="card-body">
              <mti-paginate id="table-masterjurnal" 
              :url="`/masterjurnal/paginate/${formRequest.bulan}/${formRequest.tahun}`" 
              :columns="columns"
               :callback="callback"></mti-paginate>
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
  
  import Form from "./Form.vue";
  import Filters from "./Filters.vue";
  import { useDelete } from "@/libs/hooks";
  
  export default {
    components: {
      Form, Filters
    },
    data(){
      return{
        formRequest: {
          bulan: '',
          tahun: '',
        }
      }
    },
    setup() {
      const queryClient = useQueryClient();
      const selected = ref();
      const openForm = ref(false);
      const openFilters = ref(true);
      
     
      const { delete: deletemasterjurnal } = useDelete({
        onSuccess: () => {
          queryClient.invalidateQueries(["/masterjurnal/paginate"]);
        }
      })
  
      const columns = [
        columnHelper.accessor("nomor", {
          header: "#",
          style: {
            width: "25px",
          },
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("kd_jurnal", {
          header: "Kode",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("type", {
          header: "Tipe",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("tanggal", {
          header: "Tanggal",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("upload", {
          header: "Bukti",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("uuid", {
          header: "Aksi",
          cell: (cell) => openForm.value ? null : h('div', { class: 'd-flex gap-2' }, [
              h('button', { class: 'btn btn-sm btn-icon btn-warning', onClick: () => {
                KTUtil.scrollTop();
                selected.value = cell.getValue();
                openForm.value = true;
              }}, h('i', { class: 'la la-pencil fs-2' })), 
              h('button', { class: 'btn btn-sm btn-icon btn-danger', onClick: () => {
                deletemasterjurnal(`/masterjurnal/${cell.getValue()}/destroy`);
              }}, h('i', { class: 'la la-trash fs-2' }))
            ]),
        }),
      ]
  
      return {
      
        selected,
        openForm,
        openFilters,
        columns,
      }
    },
    methods:{
      checkTambah(){
      var app = this;
      app.axios.get('masterjurnal/checkTambah/'+ app.formRequest.tahun).then((res) => {
        if(_.isEmpty(res.data)){
          app.status = true;
        } else {
        
          app.status = false;
        }
      }).catch((err) => {
        toastr.error('seuatu error sedang terjadi', "Error");
      });
    },
    }
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