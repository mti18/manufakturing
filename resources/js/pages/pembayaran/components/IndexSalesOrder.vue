<template>
    <section>
      <Form v-if="openForm" :selected="selected" />
      <UtangPiutang v-if="openUtangPiutang" :selected="selected" />
      <div class="card">
        <div class="card-header">
          <div class="card-title w-100">
            <h1>Pembayaran</h1>
            <ul class="nav nav-tabs nav-tabs-line justify-content-end ms-auto nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="pill" role="tab" href="#kt_tab_pane_1">Pembelian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" role="tab" href="#kt_tab_pane_2">Sales order</a>
                </li>
            </ul>
          </div>
        </div>
        <div class="card-body">
          <button v-if="!openForm" type="button" class="btn btn-primary btn-sm mb-8 mt-3 pojok" @click="openForm = true">
              <i class="las la-plus"></i>
              Tambah Sales Order 
          </button>
          <div class="tab-content mt-5" id="myTabContent">
              <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel" aria-labelledby="kt_tab_pane_1"
              >
                <mti-paginate
                  id="table-pembelians"
                  url="/pembelian/paginate"
                  :columns="columns"
                ></mti-paginate>
              </div>
              <div class="tab-pane fade " id="kt_tab_pane_2" role="tabpanel" aria-labelledby="kt_tab_pane_2">
                <mti-paginate
                  id="table-sales_orders"
                  url="/salesorder/paginate"
                  :columns="columns"
                ></mti-paginate>
              </div>
          </div>
          <!-- <mti-paginate id="table-customer" url="/customer/paginate" :columns="columns"></mti-paginate> -->
        </div>
      </div>
    </section>
  </template>
  
  <script>
  import { ref, h } from "vue";
  import { useQueryClient } from "vue-query";
  import { createColumnHelper } from "@tanstack/vue-table";
  const columnHelper = createColumnHelper();
  
  import Form from "./components/FormSalesOrder.vue";
  import UtangPiutang from "./components/UtangPiutang.vue";
  import { useDelete } from "@/libs/hooks";
  
  export default {
    components: {
      Form,
      UtangPiutang,
    },
    setup() {
      const queryClient = useQueryClient();
      const selected = ref();
      const openForm = ref(false);
      const openUtangPiutang = ref(false);
      
      const { delete: deletecustomer } = useDelete({
        onSuccess: () => {
          queryClient.invalidateQueries(["/customer/paginate"]);
        }
      })
  
      const columns = [
        columnHelper.accessor("no_surat", {
          header: "Nomor Surat",
          style: {
            width: "25px",
          },
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("profile.nama", {
          header: "Nama Perusahaan",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("supplier.nama", {
          header: "Nama Pemesan",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("tempo", {
          header: "Jatuh Tempo",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("npwp", {
          header: "Sisa Bayar",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("uuid", {
          header: "Aksi",
          cell: (cell) => openForm.value ? null : h('div', { class: 'd-flex gap-2' }, [
              h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-active-light-warning",
                    onClick: () => {
                      KTUtil.scrollTop();
                      selected.value = cell.getValue();
                      openForm.value = true;
                    },
                  },
                  h("i", { class: "la la-money-bill text-warning fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-active-light-danger",
                    onClick: () => {
                      KTUtil.scrollTop();
                      selected.value = cell.getValue();
                      openUtangPiutang.value = true;
                    },
                  },
                  h("i", { class: "la la-balance-scale text-success fs-2" })
                ),
            ]),
        }),
      ]
  
      return {
        selected,
        openForm,
        openUtangPiutang,
        columns,
      }
    }
  }
  </script>
  
  <style scoped>
    .pojok{
        margin-left: 857px;
    }
    .nav-item{
        margin-bottom: -30px;
    }
  </style>