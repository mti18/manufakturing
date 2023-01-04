<template>
  <section>
    <Detail v-if="openDetail" :selected="selected"></Detail>
    <div class="card" v-if="!openDetail">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>Konfirmasi Order</h1>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate
          id="table-konfirmasiorderprocess"
          url="/konfirmasiorder/paginate/process"
          :columns="columns"
        ></mti-paginate>
      </div>
    </div>

    <div class="card" style="margin-top: 3rem" v-if="!openDetail">
      <div class="card-body">
        <mti-paginate
          id="table-konfirmasiorderdone"
          url="/konfirmasiorder/paginate/success"
          :columns="columns"
        ></mti-paginate>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, h } from "vue";
import { useQueryClient } from "vue-query";
import { createColumnHelper } from "@tanstack/vue-table";
const columnHelper = createColumnHelper();

import { useDownloadPdf } from "@/libs/hooks";

import Detail from "./Detail.vue";

export default {
  components: {
    Detail,
  },
  setup() {
    const queryClient = useQueryClient();
    const selected = ref();
    const openDetail = ref(false);

    const { download: downloadPdf } = useDownloadPdf();

    const columns = [
      columnHelper.accessor("no_pemesanan", {
        header: "No Pemesanan",
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
      columnHelper.accessor("user.name", {
        header: "User",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("uuid", {
        header: "Aksi",
        cell: (cell) => {
          if (openDetail.value) return null;
          const id = cell.row.original.id;

          const buttons = [];
          buttons.push(
            h("div", { class: "d-flex gap-2" }, [
              h(
                "button",
                {
                  class: "btn btn-sm btn-icon btn-secondary",
                  onClick: () =>
                    downloadPdf(
                      `/konfirmasipimpinan/order/${cell.getValue()}/generatepdforder`,
                      "GET"
                    ),
                },
                h("i", { class: "la la-file-pdf fs-2" })
              ),
              h(
                "button",
                {
                  class: "btn btn-sm btn-icon btn-primary",
                  onClick: () => {
                    KTUtil.scrollTop();
                    selected.value = cell.getValue();
                    openDetail.value = true;
                  },
                },
                h("i", { class: "la la-eye fs-2" })
              ),
            ])
          );

          return h("div", { class: "d-inlineblock gap-2" }, buttons);
        },
      }),
    ];

    return {
      selected,
      openDetail,
      columns,
    };
  },
};
</script>

<style></style>
