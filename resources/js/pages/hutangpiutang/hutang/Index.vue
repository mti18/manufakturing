<template>
    <section>
      <Form v-if="openForm" :selected="selected" />
      <div class="card">
        <div class="card-header">
          <div class="card-title w-100">
            <h1>Hutang</h1>
            <button v-if="!openForm" type="button" class="btn btn-primary btn-sm ms-auto" @click="openForm = true">
              <i class="las la-plus"></i>
              Tambahkan
            </button>
          </div>
        </div>
        <div class="card-body">
          <mti-paginate id="table-position" url="hutangpiutang/{uuid}/paginate" :columns="columns"></mti-paginate>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  import { ref, h } from "vue";
  import { useQueryClient } from "vue-query";
  import { createColumnHelper } from "@tanstack/vue-table";
  const columnHelper = createColumnHelper();
  import Form from "./Form.vue";
  import { useDelete } from "@/libs/hooks";
  
  export default {
    components: {
      Form,
    },
    setup() {
      const queryClient = useQueryClient();
      const selected = ref();
      const openForm = ref(false);
      
      const { delete: deleteHutang } = useDelete({
        onSuccess: () => {
          queryClient.invalidateQueries(["/hutangpiutang/{uuid}/paginate"]);
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
        columnHelper.accessor("nm_golongan", {
          header: "Nama",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("metode_penyusutan", {
          header: "Metode",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("masa", {
          header: "Masa",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("rate", {
          header: "Rate",
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
                deleteGolongan(`/golongan/${cell.getValue()}/destroy`);
              }}, h('i', { class: 'la la-trash fs-2' }))
            ]),
        }),
      ]
  
      return {
        deleteHutang,
        selected,
        openForm,
        columns,
      }
    }
  }
  </script>
  
  <style>
  
  </style>