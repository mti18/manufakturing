<template>
    <section>
      <FormPembelian v-if="openFormPembelian"/>
      <FormInternal v-if="openFormInternal"/>
      <div>
        <button v-if="!openFormPembelian" type="button" class="btn btn-primary btn-sm mb-8 mt-3 pojok" @click="openFormPembelian = true">
            <i class="las la-plus"></i>
            Tambah 
          </button>
          <button v-if="!openFormInternal" type="button" class="btn btn-primary btn-sm mb-8 mt-3 pojok" @click="openFormInternal = true">
            <i class="las la-plus"></i>
            Tambah 
          </button>
      </div>
      <div class="card">
        <div class="card-header">
          <div class="card-title w-100">
            <h1>Permintaan</h1>
            <ul class="nav nav-tabs nav-tabs-line justify-content-end ms-auto nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" role="tab" href="#kt_tab_pane_1">Pembelian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" role="tab" href="#kt_tab_pane_2">Internal</a>
                </li>
            </ul>
          </div>
        </div>
        <div class="card-body">
          <mti-paginate
            id="table-permintaan_barangs"
            url="/permintaan/paginate"
            :columns="columns"
          ></mti-paginate>
        </div>
        <div class="tab-content mt-5" id="myTabContent">
            <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel" aria-labelledby="kt_tab_pane_1">Tab content 1</div>
            <div class="tab-pane fade " id="kt_tab_pane_2" role="tabpanel" aria-labelledby="kt_tab_pane_2">Tab content 2</div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  import { ref, h } from "vue";
  import { useQueryClient } from "vue-query";
  import { createColumnHelper } from "@tanstack/vue-table";
  const columnHelper = createColumnHelper();
  
  import FormInternal from "./components/FormInternal.vue";
  import FormPembelian from "./components/FormPembelian.vue";
//   import IndexInternal from "./components/IndexInternal.vue";
//   import IndexPembelian from "./componets/IndexPembelian.vue";
  import { useDelete } from "@/libs/hooks";
  
  export default {
    components: {
      FormInternal,
      FormPembelian,
    //   IndexInternal,
    //   IndexPembelian,
    },
    setup() {
      const queryClient = useQueryClient();
      const selected = ref();
      const openFormPembelian = ref(false);
      const openFormInternal = ref(false);
  
      const { delete: deletepermintaan_barang } = useDelete({
        onSuccess: () => {
          queryClient.invalidateQueries(["/permintaan_barang/paginate"]);
        },
      });
  
      const columns = [
        columnHelper.accessor("nomor", {
          header: "#",
          style: {
            width: "25px",
          },
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("volume", {
          header: "Nama",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("harga", {
          header: "Kode",
          cell: (cell) => cell.getValue(),
        }),
        columnHelper.accessor("uuid", {
          header: "Aksi",
          cell: (cell) =>
            openFormPembelian.value
              ? null
              : h("div", { class: "d-flex gap-2" }, [
                  h(
                    "button",
                    {
                      class: "btn btn-sm btn-icon btn-warning",
                      onClick: () => {
                        KTUtil.scrollTop();
                        selected.value = cell.getValue();
                        openFormPembelian.value = true;
                      },
                    },
                    h("i", { class: "la la-pencil fs-2" })
                  ),
                  h(
                    "button",
                    {
                      class: "btn btn-sm btn-icon btn-danger",
                      onClick: () => {
                        deletepermintaan_barang(`/permintaan_barang/${cell.getValue()}/destroy`);
                      },
                    },
                    h("i", { class: "la la-trash fs-2" })
                  ),
                ]),
        }),
      ];
  
      return {
        selected,
        // openIndexPembelian,
        // openIndexInternal,
        openFormInternal,
        openFormPembelian,
        columns,
      };
    },
  };
  </script>
  
  <style scoped>
    .pojok{
        margin-left: 857px;
    }
    .nav-item{
        margin-bottom: -30px;
    }
</style>
  