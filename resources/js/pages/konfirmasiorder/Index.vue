<template>
  <section>
    <Detail v-if="openDetail" :selected="selected"></Detail>
    <div class="card">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>Konfirmasi Order</h1>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate
          id="table-kategori"
          url="/kategori/paginate"
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

import Detail from "./Detail.vue";

export default {
  components: {
    Detail,
  },
  setup() {
    const queryClient = useQueryClient();
    const selected = ref();
    const openDetail = ref(false);

    const columns = [
      columnHelper.accessor("nomor", {
        header: "#",
        style: {
          width: "25px",
        },
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("nm_kategori", {
        header: "Nomor Pemesanan",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("nm_kategori", {
        header: "Nama Perusahaan",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("nm_kategori", {
        header: "Nama Pemesan",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("nm_kategori", {
        header: "User",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("uuid", {
        header: "Aksi",
        cell: (cell) =>
          openDetail.value
            ? null
            : h("div", { class: "d-flex gap-2" }, [
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-success",
                  },
                  h("i", { class: "la fa-file-pdf fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-primary",
                    onClick: () => {
                      openDetail.value = true;
                    },
                  },
                  h("i", { class: "la la-eye fs-2" })
                ),
              ]),
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
