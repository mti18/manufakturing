<template>
    <section>
      <Form v-if="openForm" :selected="selected" />
      <div class="card">
        <div class="card-header">
          <div class="card-title w-100">
            <h1>Penyusutan</h1>
            <!-- <button v-if="!openForm" type="button" class="btn btn-primary btn-sm ms-auto" @click="openForm = true">
              <i class="las la-plus"></i>
              Penyusutan Baru
            </button> -->
          </div>
        </div>
        <div class="card-body">
          <mti-paginate id="table-position" url="/penyusutan/paginate" :columns="columns"></mti-paginate>
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
  import axios from "@/libs/axios";
  import { useQuery, useMutation } from "vue-query";
  
  export default {
    components: {
      Form,
    },
    setup() {
      const queryClient = useQueryClient();
      const selected = ref();
      const openForm = ref(false);

      const { mutate: susutkan } = useMutation((data) => axios.post('/penyusutan/susutkan', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#table-position");
          
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#table-position");
        }
      });
  

  
      const columns = [
      
        columnHelper.accessor("nm_asset", {
          header: "Nama",
          cell: (cell) => cell.getValue(),
        }),
        // columnHelper.accessor("penyusutan_first.tanggal", {
        //   header: "Penyusutan terakhir",
        //   cell: (cell) => cell.getValue(),
        // }),
        columnHelper.accessor("price", {
          header: "Jumlah",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("nilai_buku", {
          header: "Nilai",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("susut", {
          header: "Nilai Susutan",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("id", {
          header: "Aksi",
          cell: (cell) => openForm.value ? null : h('div', { class: 'd-flex gap-2' }, [
              h('button', { class: 'btn btn-sm btn-icon btn-primary', onClick: () => {
                KTUtil.scrollTop();
                susutkan({ id: cell.getValue() });
              }}, h('i', { class: 'la la-eye fs-2' }))
            ]),
        }),
      ]
  
      return {
        selected,
        openForm,
        columns,
      }
    }
  }
  </script>
  
  <style>
  
  </style>