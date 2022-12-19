<template>
    <section>
      <Form v-if="openForm" :selected="selected" />
      <Detail v-if="openDetail" :selected="selected" />
      <div class="card">
        <div class="card-header">
          <div class="card-title w-100">
            <h1>Pembelian</h1>
            <button v-if="!openForm" type="button" class="btn btn-primary btn-sm ms-auto me-5" @click="openDetail = true" >
              <i class="las la-eye" style="margin-left: 0;"></i>
              Detail
            </button>
            <button v-if="!openForm" type="button" class="btn btn-success btn-sm " @click="openForm = true" style="margin-left: 0;">
              <i class="las la-plus"></i>
              Pembelian
            </button>
          </div>
        </div>
        <div class="card-body">
          <mti-paginate id="table-pembelian" url="/pembelian/paginate" :columns="columns"></mti-paginate>
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
  import Detail from "./Detail.vue";
  import { useDelete } from "@/libs/hooks";
  
  export default {
    components: {
      Form,
      Detail,
    },
    setup() {
      const queryClient = useQueryClient();
      const selected = ref();
      const openForm = ref(false);
      const openDetail = ref(false);
      
      const { delete: deletepembelian } = useDelete({
        onSuccess: () => {
          queryClient.invalidateQueries(["/pembelian/paginate"]);
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
        columnHelper.accessor("supplier.nama", {
          header: "Nama",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("no_surat", {
          header: "No Surat",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("bukti_permintaan", {
          header: "Bukti Permintaan",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("diketahui_oleh.name", {
          header: "User",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("pembayaran", {
          header: "Pembayaran",
          cell: (cell) => cell.getValue()=='yes' ?
            h('div', { class: 'd-flex gap-2 ms-8' }, [
              h('span', { class: 'badge badge-success', style: 'border-radius:50px' }, 'Yes')
            ])
              : h('div', { class: 'd-flex gap-2 ms-8' }, [
                h('span', { class: 'badge badge-danger', style:'border-radius:50px' }, 'No'),
            ]),
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
                deletepembelian(`/pembelian/${cell.getValue()}/destroy`);
              }}, h('i', { class: 'la la-trash fs-2' }))
            ]),
        }),
      ]
  
      return {
        selected,
        openDetail,
        openForm,
        columns,
      }
    }
  }
  </script>
  
  <style>
  
  </style>