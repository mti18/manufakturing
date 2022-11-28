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

export default {
  components: {},
  setup() {
    const queryClient = useQueryClient();
    const selected = ref();

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
          h("div", { class: "d-flex gap-2" }, [
            h(
              "button",
              {
                class: "btn btn-sm btn-icon",
              },
              h("i", { class: "la la-check fs-2 text-success" })
            ),
            h(
              "button",
              {
                class: "btn btn-sm btn-icon",
              },
              h("i", { class: "la la-close fs-2 text-danger" })
            ),
          ]),
      }),
    ];

    return {
      selected,
      columns,
    };
  },
};
</script>

<style></style>
