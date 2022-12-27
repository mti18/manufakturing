<template>
  <section>
    <div class="card">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>Konfirmasi Order</h1>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate
          id="table-kategori"
          url="konfirmasipimpinan/order/paginateOrder"
          :columns="columns"
        ></mti-paginate>
      </div>
    </div>

    <div class="card" style="margin-top: 3rem">
      <div class="card-body">
        <mti-paginate
          id="table-kategori"
          url="konfirmasipimpinan/order/paginateOrder"
          :columns="columns"
        ></mti-paginate>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
const columnHelper = createColumnHelper();

export default {
  components: {},
  setup() {
    const selected = ref();
    const openForm = ref(false);

    const columns = [
      columnHelper.accessor("nomor", {
        header: "#",
        style: {
          width: "25px",
        },
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("nm_kategori", {
        header: "No Pemesanan",
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
          openForm.value
            ? null
            : h("div", { class: "d-flex gap-2" }, [
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-success",
                    // onClick: () => {
                    //   KTUtil.scrollTop();
                    //   selected.value = cell.getValue();
                    //   openForm.value = true;
                    // },
                  },
                  h("i", { class: "la la-file-pdf fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-info",
                    // onClick: () => {
                    //   KTUtil.scrollTop();
                    //   selected.value = cell.getValue();
                    //   openForm.value = true;
                    // },
                  },
                  h("i", { class: "la la-file-pdf fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-secondary",
                    // onClick: () => {
                    //   KTUtil.scrollTop();
                    //   selected.value = cell.getValue();
                    //   openForm.value = true;
                    // },
                  },
                  h("i", { class: "la la-file-pdf fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-success",
                    // onClick: () => {
                    //   KTUtil.scrollTop();
                    //   selected.value = cell.getValue();
                    //   openForm.value = true;
                    // },
                  },
                  h("i", { class: "la la-check fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-danger",
                    // onClick: () => {
                    //   KTUtil.scrollTop();
                    //   selected.value = cell.getValue();
                    //   openForm.value = true;
                    // },
                  },
                  h("i", { class: "la la-close fs-2" })
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
